<?php

namespace App\Services\Interfaces;

use App\Models\News;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface NewsServiceInterface
{
    public function all(int $perPage = 10): LengthAwarePaginator;
    public function allPublic(int $perPage = 10): LengthAwarePaginator;
    public function find(int $id): News;
    public function findPublic(int $id): News;
    public function create(array $data): News;
    public function update(int $id, array $data): News;
    public function delete(int $id): void;
    public function byStatus(string $status, int $perPage = 10): LengthAwarePaginator;
}
