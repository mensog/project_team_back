<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;

class UserService implements UserServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function all(int $perPage = 10): LengthAwarePaginator
    {
        return $this->userRepository->all($perPage);
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
        $data['password'] = Hash::make($data['password']);
        if (isset($data['avatar']) && $data['avatar']->isValid()) {
            $data['avatar'] = $data['avatar']->store('avatars', 'public');
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

    public function login(array $credentials): ?User
    {
        if (!Auth::attempt($credentials)) {
            return null;
        }
        return Auth::user();
    }
}
