<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class UserService implements UserServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function all(int $perPage = 10): LengthAwarePaginator
    {
        Gate::authorize('viewAny', User::class);
        return $this->userRepository->paginate($perPage);
    }

    public function find(int $id): User
    {
        $user = $this->userRepository->find($id);
        Gate::authorize('view', $user);
        if (!$user) {
            throw new \Exception("Пользователь с ID $id не найден.", 404);
        }
        return $user;
    }

    public function create(array $data): User
    {
        Gate::authorize('create', User::class);
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $this->userRepository->create($data);
    }

    public function update(int $id, array $data): User
    {
        $user = $this->userRepository->find($id);
        Gate::authorize('update', $user);
        if (isset($data['avatar']) && $data['avatar']->isValid()) {
            $data['avatar'] = $data['avatar']->store('avatars', 'public');
        }
        return $this->userRepository->update($id, $data);
    }

    public function uploadAvatar(int $userId, UploadedFile $avatar): User
    {
        try {
            Log::debug('Попытка загрузки аватарки', [
                'user_id' => $userId,
                'file_name' => $avatar->getClientOriginalName(),
                'file_size' => $avatar->getSize(),
            ]);

            $user = $this->userRepository->find($userId);
            Gate::authorize('uploadAvatar', $user);

            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $extension = $avatar->getClientOriginalExtension();
            $fileName = "avatar_{$userId}_" . time() . ".{$extension}";
            $path = "avatars/{$fileName}";

            $image = Image::read($avatar)
                ->scale(200, 200)
                ->encodeByExtension($extension, quality: 85);

            Storage::disk('public')->put($path, $image);

            $user = $this->userRepository->update($userId, ['avatar' => $path]);

            Log::info('Аватарка успешно загружена', [
                'user_id' => $userId,
                'path' => $path,
                'size' => $avatar->getSize(),
            ]);

            return $user;
        } catch (\Exception $e) {
            Log::error('Ошибка при загрузке аватарки', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'trace' => $e->getTraceAsString(),
            ]);
            throw new \Exception('Не удалось загрузить аватарку: ' . $e->getMessage(), $e->getCode() ?: 500);
        }
    }

    public function delete(int $id): void
    {
        $user = $this->userRepository->find($id);
        Gate::authorize('delete', $user);
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }
        $this->userRepository->delete($id);
    }

    public function login(array $data): ?User
    {
        if (auth()->attempt($data)) {
            return auth()->user();
        }
        return null;
    }
}
