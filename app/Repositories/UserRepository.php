<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    public function all(int $perPage): LengthAwarePaginator
    {
        return User::query()->select([
            'id',
            'first_name',
            'middle_name',
            'last_name',
            'avatar',
            'group'
        ])->paginate($perPage);
    }

    public function find(int $id): User
    {
        return User::findOrFail($id);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(int $id, array $data): User
    {
        $user = $this->find($id);
        $user->update($data);
        return $user;
    }

    public function delete(int $id): void
    {
        $user = $this->find($id);
        $user->delete();
    }
}
