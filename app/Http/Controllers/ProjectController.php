<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Requests\Project\UploadCertificateRequest;
use App\Http\Requests\Project\UploadPreviewRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\Interfaces\ProjectServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected ProjectServiceInterface $projectService;

    public function __construct(ProjectServiceInterface $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 10);

        return $this->guardedOperation(
            fn () => $this->paginatedResponse(
                $this->projectService->all($perPage),
                ProjectResource::class
            ),
            'Error retrieving projects list',
            ['user_id' => auth()->id()],
            'Ошибка при получении проектов'
        );
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        return $this->guardedOperation(
            fn () => $this->messageResponse('Проект успешно создан!', 201, [
                'data' => new ProjectResource(
                    $this->projectService->create($request->validated())
                ),
            ]),
            'Error creating project',
            ['user_id' => auth()->id()],
            'Ошибка при создании проекта'
        );
    }

    public function show(Project $project): JsonResponse
    {
        return $this->guardedOperation(
            fn () => $this->successResponse(new ProjectResource($this->projectService->find($project->id))),
            'Error showing project',
            ['project_id' => $project->id],
            'Ошибка при получении проекта'
        );
    }

    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        return $this->guardedOperation(
            fn () => $this->messageResponse('Проект успешно обновлён!', 200, [
                'data' => new ProjectResource(
                    $this->projectService->update($project->id, $request->validated())
                ),
            ]),
            'Error updating project',
            [
                'project_id' => $project->id,
                'user_id' => auth()->id(),
            ],
            'Ошибка при обновлении проекта'
        );
    }

    public function destroy(Project $project): JsonResponse
    {
        return $this->guardedOperation(
            function () use ($project) {
                $this->projectService->delete($project->id);

                return $this->messageResponse("Проект с ID:$project->id успешно удалён!");
            },
            'Error deleting project',
            [
                'project_id' => $project->id,
                'user_id' => auth()->id(),
            ],
            'Ошибка при удалении проекта'
        );
    }

    public function getByUser(Request $request): JsonResponse
    {
        $userId = (int) $request->query('user_id', auth()->id());
        $perPage = (int) $request->query('per_page', 10);

        return $this->guardedOperation(
            fn () => $this->paginatedResponse(
                $this->projectService->getByUser($userId, $perPage),
                ProjectResource::class
            ),
            'Error retrieving projects by user',
            [
                'requested_user_id' => $userId,
                'current_user_id' => auth()->id(),
            ],
            'Ошибка при получении проектов пользователя'
        );
    }

    public function join(Project $project): JsonResponse
    {
        return $this->guardedOperation(
            function () use ($project) {
                $this->projectService->join($project->id, auth()->id());

                return $this->messageResponse("Вы присоединились к проекту $project->name!", 201);
            },
            'Error joining project',
            [
                'project_id' => $project->id,
                'user_id' => auth()->id(),
            ],
            'Ошибка при присоединении к проекту'
        );
    }

    public function leave(Project $project): JsonResponse
    {
        return $this->guardedOperation(
            function () use ($project) {
                $this->projectService->leave($project->id, auth()->id());

                return $this->messageResponse("Вы покинули проект $project->name!");
            },
            'Error leaving project',
            [
                'project_id' => $project->id,
                'user_id' => auth()->id(),
            ],
            'Ошибка при выходе из проекта'
        );
    }

    public function uploadPreview(UploadPreviewRequest $request, Project $project): JsonResponse
    {
        return $this->guardedOperation(
            function () use ($project, $request) {
                $projectResource = new ProjectResource(
                    $this->projectService->uploadPreview($project->id, $request->file('preview_image'))
                );

                return $this->messageResponse('Превью успешно загружено!', 200, [
                    'data' => $projectResource,
                ]);
            },
            'Error uploading project preview',
            [
                'project_id' => $project->id,
                'user_id' => auth()->id(),
            ],
            'Ошибка при загрузке превью'
        );
    }

    public function approve(Request $request, Project $project): JsonResponse
    {
        return $this->guardedOperation(
            function () use ($project) {
                $approvedProject = $this->projectService->approve($project->id);

                return $this->messageResponse(
                    "Проект {$approvedProject->name} подтверждён!",
                    200,
                    ['data' => new ProjectResource($approvedProject)]
                );
            },
            'Error approving project',
            [
                'project_id' => $project->id,
                'user_id' => auth()->id(),
            ],
            'Ошибка при подтверждении проекта'
        );
    }

    public function reject(Request $request, Project $project): JsonResponse
    {
        return $this->guardedOperation(
            function () use ($project) {
                $this->projectService->reject($project->id);

                return $this->messageResponse("Проект {$project->name} отклонён!", 200);
            },
            'Error rejecting project',
            [
                'project_id' => $project->id,
                'user_id' => auth()->id(),
            ],
            'Ошибка при отклонении проекта'
        );
    }

    public function uploadCertificate(UploadCertificateRequest $request, Project $project): JsonResponse
    {
        return $this->guardedOperation(
            function () use ($project, $request) {
                $updatedProject = $this->projectService->uploadCertificate($project->id, $request->file('certificate'));

                return $this->messageResponse('Сертификат успешно загружен!', 200, [
                    'data' => new ProjectResource($updatedProject),
                ]);
            },
            'Error uploading project certificate',
            [
                'project_id' => $project->id,
                'user_id' => auth()->id(),
            ],
            'Ошибка при загрузке сертификата'
        );
    }
}
