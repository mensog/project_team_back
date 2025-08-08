<?php

namespace App\Repositories;

use App\Models\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
        if (!empty($data['user_id']) && \App\Models\User::find($data['user_id'])->is_admin) {
            $data['is_approved'] = true;
        }
        $project = $this->model->create($data);
        if (!empty($data['user_id'])) {
            $this->addParticipant($project->id, $data['user_id']);
        }
        if (!empty($data['participants']) && is_array($data['participants'])) {
            $this->addParticipants($project->id, $data['participants']);
        }
        return $project->refresh()->load('user', 'participants');
    }

    public function update(int $id, array $data): Project
    {
        $project = $this->find($id);
        $project->update($data);
        if (isset($data['participants']) && is_array($data['participants'])) {
            $this->addParticipants($id, $data['participants']);
        }
        return $project->refresh()->load('user', 'participants');
    }

    public function delete(int $id): void
    {
        $project = $this->find($id);
        if ($project->preview_image) {
            Storage::disk('public')->delete($project->preview_image);
        }
        if ($project->certificate) {
            Storage::disk('public')->delete($project->certificate);
        }
        $project->delete();
    }

    public function getByUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->with(['user', 'participants' => function ($query) {
            $query->select('users.id');
        }]);

        if (auth()->user()->id !== $userId && !auth()->user()->is_admin) {
            Log::warning('Unauthorized access attempt to projects', [
                'requested_user_id' => $userId,
                'auth_user_id' => auth()->id(),
            ]);
            abort(403, 'Недостаточно прав для просмотра проектов другого пользователя');
        }

        return $query->where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->orWhereHas('participants', function ($subQuery) use ($userId) {
                      $subQuery->where('user_id', $userId);
                  });
        })->paginate($perPage);
    }

    public function addParticipant(int $projectId, int $userId): void
    {
        $project = $this->find($projectId);
        $project->participants()->syncWithoutDetaching([$userId]);
    }

    public function addParticipants(int $projectId, array $userIds): void
    {
        $project = $this->find($projectId);
        $validUserIds = array_filter($userIds, fn($id) => is_numeric($id) && (int)$id > 0);
        if (!empty($validUserIds)) {
            $project->participants()->syncWithoutDetaching($validUserIds);
        }
    }

    public function removeParticipant(int $projectId, int $userId): void
    {
        $project = $this->find($projectId);
        $project->participants()->detach($userId);
    }

    public function approve(int $id): Project
    {
        $project = $this->find($id);
        $project->update(['is_approved' => true]);
        $this->addParticipant($id, $project->user_id);
        return $project->refresh()->load('user', 'participants');
    }

    public function reject(int $id): void
    {
        $this->delete($id);
    }

    public function uploadCertificate(int $id, UploadedFile $file): Project
    {
        $project = $this->find($id);
        if ($project->certificate) {
            Storage::disk('public')->delete($project->certificate);
        }
        $path = $file->store('project_certificates', 'public');
        $project->update(['certificate' => $path]);
        return $project->refresh()->load('user', 'participants');
    }
}
