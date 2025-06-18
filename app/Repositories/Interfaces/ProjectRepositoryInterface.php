<?php

namespace App\Repositories\Interfaces;

use App\Models\Project;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProjectRepositoryInterface
{
    public function all(): \Illuminate\Support\Collection;
    public function find(int $id): ?Project;
    public function create(array $data): Project;
    public function update(int $id, array $data): ?Project;
    public function delete(int $id): bool;
    public function getByUser(int $userId, int $perPage): LengthAwarePaginator;
    public function addParticipant(int $projectId, int $userId): void;
    public function removeParticipant(int $projectId, int $userId): void;
}
