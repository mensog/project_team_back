<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Concerns\HandlesUploads;
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
    use HandlesUploads;

    protected UserRepositoryInterface $userRepository;

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

        return $user;
    }

    public function create(array $data): array
    {
        Gate::authorize('create', User::class);
        $generatedPassword = null;

        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            $generatedPassword = $this->generateSecurePassword();
            $data['password'] = Hash::make($generatedPassword);
        }

        $user = $this->userRepository->create($data);

        return ['user' => $user, 'password' => $generatedPassword];
    }

    protected function generateSecurePassword(int $length = 12): string
    {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789@#$%&*+-_';
        $characters = str_split($alphabet);
        $maxIndex = count($characters) - 1;

        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, $maxIndex)];
        }

        return $password;
    }

    public function update(int $id, array $data): User
    {
        $user = $this->userRepository->find($id);
        Gate::authorize('update', $user);
        if (isset($data['avatar']) && $data['avatar']->isValid()) {
            $this->deletePublicFile($user->avatar);
            $data['avatar'] = $this->storePublicFile($data['avatar'], 'avatars');
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

            $this->deletePublicFile($user->avatar);

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
        $this->deletePublicFile($user->avatar);
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
