<?php

namespace App\Repositories\Interfaces;

use App\Models\News;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface NewsRepositoryInterface
{
    public function all(): Collection;
    public function paginate(int $perPage = 10): LengthAwarePaginator;
    public function byStatus(string $status, int $perPage = 10): LengthAwarePaginator;
    public function find(int $id): News;
    public function create(array $data): News;
    public function update(int $id, array $data): News;
    public function delete(int $id): bool;
}
