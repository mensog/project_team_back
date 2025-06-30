<?php

namespace App\Services;

use App\Models\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Services\Interfaces\ProjectServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;

class ProjectService implements ProjectServiceInterface
{
    protected $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function all(int $perPage = 10): LengthAwarePaginator
    {
        Gate::authorize('viewAny', Project::class);
        return $this->projectRepository->paginate($perPage);
    }

    public function find(int $id): Project
    {
        $project = $this->projectRepository->find($id);
        Gate::authorize('view', $project);
        return $project;
    }

    public function create(array $data): Project
    {
        Gate::authorize('create', Project::class);
        return $this->projectRepository->create($data);
    }

    public function update(int $id, array $data): Project
    {
        $project = $this->projectRepository->find($id);
        Gate::authorize('update', $project);
        return $this->projectRepository->update($id, $data);
    }

    public function delete(int $id): void
    {
        $project = $this->projectRepository->find($id);
        Gate::authorize('delete', $project);
        $this->projectRepository->delete($id);
    }

    public function getByUser(int $userId, int $perPage): LengthAwarePaginator
    {
        Gate::authorize('viewAnyForUser', [Project::class, $userId]);
        return $this->projectRepository->getByUser($userId, $perPage);
    }

    public function join(int $projectId, int $userId): void
    {
        $project = $this->projectRepository->find($projectId);
        Gate::authorize('join', $project);
        $this->projectRepository->addParticipant($projectId, $userId);
    }

    public function leave(int $projectId, int $userId): void
    {
        $project = $this->projectRepository->find($projectId);
        Gate::authorize('leave', $project);
        $this->projectRepository->removeParticipant($projectId, $userId);
    }

    public function uploadPreview(int $id, UploadedFile $file): Project
    {
        $project = $this->projectRepository->find($id);
        Gate::authorize('update', $project);
        $path = $file->store('project_previews', 'public');
        return $this->projectRepository->update($id, ['preview_image' => $path]);
    }
}
