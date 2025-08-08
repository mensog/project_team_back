<?php

namespace App\Repositories\Interfaces;

use App\Models\Project;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProjectRepositoryInterface
{
    public function all(): Collection;
    public function paginate(int $perPage = 10): LengthAwarePaginator;
    public function find(int $id): Project;
    public function create(array $data): Project;
    public function update(int $id, array $data): Project;
    public function delete(int $id): void;
    public function getByUser(int $userId, int $perPage): LengthAwarePaginator;
    public function addParticipant(int $projectId, int $userId): void;
    public function addParticipants(int $projectId, array $userIds): void;
    public function removeParticipant(int $projectId, int $userId): void;
    public function approve(int $id): Project;
    public function reject(int $id): void;
    public function uploadCertificate(int $id, UploadedFile $file): Project;
}
