<?php

namespace App\Services\Interfaces;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    public function all(int $perPage = 10): LengthAwarePaginator;
    public function find(int $id): User;
    public function create(array $data): User;
    public function update(int $id, array $data): User;
    public function delete(int $id): void;
    public function login(array $credentials): ?User;
    public function uploadAvatar(int $userId, UploadedFile $avatar): User;
}
