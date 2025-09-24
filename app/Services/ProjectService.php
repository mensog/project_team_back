<?php

namespace App\Services;

use App\Events\ProjectCreated;
use App\Models\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Services\Concerns\HandlesUploads;
use App\Services\Interfaces\ProjectServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;

class ProjectService implements ProjectServiceInterface
{
    use HandlesUploads;

    protected ProjectRepositoryInterface $projectRepository;

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

        $data['user_id'] = auth()->id();
        if (!auth()->user()->is_admin) {
            $data['status'] = 'active';
            $data['is_approved'] = false;
        }

        $this->syncUpload($data, 'certificate', null, 'project_certificates');
        $this->syncUpload($data, 'preview_image', null, 'project_previews');

        $project = $this->projectRepository->create($data);
        event(new ProjectCreated($project));
        return $project;
    }

    public function update(int $id, array $data): Project
    {
        $project = $this->projectRepository->find($id);
        Gate::authorize('update', $project);

        if (!auth()->user()->is_admin) {
            $data['status'] = $project->status;
            $data['is_approved'] = $project->is_approved;
            $data['user_id'] = $project->user_id;
            unset($data['participants']);
        }

        $this->syncUpload($data, 'certificate', $project->certificate, 'project_certificates');
        $this->syncUpload($data, 'preview_image', $project->preview_image, 'project_previews');

        return $this->projectRepository->update($id, $data);
    }

    public function delete(int $id): void
    {
        $project = $this->projectRepository->find($id);
        Gate::authorize('delete', $project);
        $this->deletePublicFile($project->preview_image);
        $this->deletePublicFile($project->certificate);
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
        $this->deletePublicFile($project->preview_image);
        $path = $this->storePublicFile($file, 'project_previews');

        return $this->projectRepository->update($id, ['preview_image' => $path]);
    }

    public function approve(int $id): Project
    {
        $project = $this->projectRepository->find($id);
        Gate::authorize('approve', $project);
        return $this->projectRepository->approve($id);
    }

    public function reject(int $id): void
    {
        $project = $this->projectRepository->find($id);
        Gate::authorize('reject', $project);
        $this->projectRepository->reject($id);
    }

    public function uploadCertificate(int $id, UploadedFile $file): Project
    {
        $project = $this->projectRepository->find($id);
        Gate::authorize('uploadCertificate', $project);
        return $this->projectRepository->uploadCertificate($id, $file);
    }
}
