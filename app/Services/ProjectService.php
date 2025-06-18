<?php

namespace App\Services;

use App\Models\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Services\Interfaces\ProjectServiceInterface;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectService implements ProjectServiceInterface
{
    protected $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function all()
    {
        Gate::authorize('viewAny', \App\Models\Project::class);
        return $this->projectRepository->all();
    }

    public function find(int $id): Project
    {
        $project = $this->projectRepository->find($id);
        Gate::authorize('view', $project);
        return $project;
    }

    public function create(array $data): Project
    {
        Gate::authorize('create', \App\Models\Project::class);
        return $this->projectRepository->create($data);
    }

    public function update(int $id, array $data): Project
    {
        $project = $this->projectRepository->find($id);
        Gate::authorize('update', $project);
        return $this->projectRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        $project = $this->projectRepository->find($id);
        Gate::authorize('delete', $project);
        return $this->projectRepository->delete($id);
    }

    public function getByUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        Gate::authorize('viewAnyForUser', [\App\Models\Project::class, $userId]);
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
}
