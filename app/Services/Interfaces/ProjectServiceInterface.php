<?php

namespace App\Services\Interfaces;

use App\Models\Project;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProjectServiceInterface
{
    public function all(int $perPage = 10): LengthAwarePaginator;
    public function find(int $id): Project;
    public function create(array $data): Project;
    public function update(int $id, array $data): Project;
    public function delete(int $id): void;
    public function getByUser(int $userId, int $perPage): LengthAwarePaginator;
    public function join(int $projectId, int $userId): void;
    public function leave(int $projectId, int $userId): void;
    public function uploadPreview(int $id, UploadedFile $file): Project;
}
