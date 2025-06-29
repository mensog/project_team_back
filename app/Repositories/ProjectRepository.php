<?php

namespace App\Repositories;

use App\Models\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProjectRepository implements ProjectRepositoryInterface
{
    protected $model;

    public function __construct(Project $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->with('user')->get();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->with('user')->paginate($perPage);
    }

    public function find(int $id): ?Project
    {
        return $this->model->with('user')->findOrFail($id);
    }

    public function create(array $data): Project
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): ?Project
    {
        $project = $this->find($id);
        if ($project) {
            $project->update($data);
        }
        return $project;
    }

    public function delete(int $id): bool
    {
        $project = $this->find($id);
        if ($project) {
            $project->delete();
        }
        return true;
    }

    public function getByUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->where(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->orWhereHas('participants', function ($subQuery) use ($userId) {
                          $subQuery->where('user_id', $userId);
                      });
            })
            ->with('user', 'participants')
            ->paginate($perPage);
    }

    public function addParticipant(int $projectId, int $userId): void
    {
        $project = $this->find($projectId);
        $project->participants()->syncWithoutDetaching([$userId]);
    }

    public function removeParticipant(int $projectId, int $userId): void
    {
        $project = $this->find($projectId);
        $project->participants()->detach($userId);
    }
}
