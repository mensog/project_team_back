<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    public function find(int $id): User
    {
        $user = $this->model->find($id);
        if (!$user) {
            Log::warning('Пользователь не найден в UserRepository::find', ['id' => $id]);
            throw new \Exception("Пользователь с ID $id не найден.", 404);
        }
        return $user;
    }

    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): User
    {
        $user = $this->find($id);
        if ($user) {
            $user->update($data);
        }
        return $user;
    }

    public function delete(int $id): void
    {
        $user = $this->find($id);
        if ($user) {
            $user->delete();
        }
    }
}
