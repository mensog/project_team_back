<?php

namespace App\Services\Interfaces;

use App\Models\Project;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProjectServiceInterface
{
    public function all();
    public function find(int $id): ?Project;
    public function create(array $data): Project;
    public function update(int $id, array $data): ?Project;
    public function delete(int $id): bool;
    public function getByUser(int $userId, int $perPage): LengthAwarePaginator;
    public function join(int $projectId, int $userId): void;
    public function leave(int $projectId, int $userId): void;
}
