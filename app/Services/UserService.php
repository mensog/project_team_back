<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

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

    public function delete(int $id): void
    {
        $user = $this->userRepository->find($id);
        Gate::authorize('delete', $user);
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
