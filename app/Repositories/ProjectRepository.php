<?php

namespace App\Repositories;

use App\Models\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        return $this->model->with('user', 'participants')->get();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->with('user', 'participants')->paginate($perPage);
    }

    public function find(int $id): Project
    {
        $project = $this->model->with('user', 'participants')->find($id);
        if (!$project) {
            throw new ModelNotFoundException("Проект с ID {$id} не найден.");
        }
        return $project;
    }

    public function create(array $data): Project
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Project
    {
        $project = $this->find($id);
        $project->update($data);
        return $project->refresh();
    }

    public function delete(int $id): void
    {
        $project = $this->find($id);
        $project->delete();
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
            ->with(['user', 'participants' => function ($query) {
                $query->select('users.id');
            }])
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
