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
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectServiceInterface $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->query('per_page', 10);
            $projects = $this->projectService->all($perPage);
            return response()->json([
                'data' => ProjectResource::collection($projects),
                'meta' => [
                    'current_page' => $projects->currentPage(),
                    'last_page' => $projects->lastPage(),
                    'per_page' => $projects->perPage(),
                    'total' => $projects->total(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ProjectController::index', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
            return response()->json(['message' => 'Ошибка при получении проектов'], 500);
        }
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        try {
            $project = $this->projectService->create($request->validated());
            return response()->json([
                'message' => 'Проект успешно создан!',
                'data' => new ProjectResource($project)
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error in ProjectController::store', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
            return response()->json(['message' => 'Ошибка при создании проекта'], 500);
        }
    }

    public function show(Project $project): JsonResponse
    {
        try {
            $project = $this->projectService->find($project->id);
            return response()->json([
                'data' => new ProjectResource($project)
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ProjectController::show', [
                'error' => $e->getMessage(),
                'project_id' => $project->id,
            ]);
            return response()->json(['message' => 'Ошибка при получении проекта'], 500);
        }
    }

    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        try {
            $projectData = $request->validated();
            $project = $this->projectService->update($project->id, $projectData);
            return response()->json([
                'message' => 'Проект успешно обновлён!',
                'data' => new ProjectResource($project)
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ProjectController::update', [
                'error' => $e->getMessage(),
                'project_id' => $project->id,
                'user_id' => auth()->id(),
            ]);
            return response()->json(['message' => 'Ошибка при обновлении проекта'], 500);
        }
    }

    public function destroy(Project $project): JsonResponse
    {
        try {
            $this->projectService->delete($project->id);
            return response()->json([
                'message' => "Проект с ID:$project->id успешно удалён!"
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in ProjectController::destroy', [
                'error' => $e->getMessage(),
                'project_id' => $project->id,
                'user_id' => auth()->id(),
            ]);
            return response()->json(['message' => 'Ошибка при удалении проекта'], 500);
        }
    }

    public function getByUser(Request $request): JsonResponse
    {
        try {
            $userId = $request->query('user_id', auth()->id());
            $perPage = $request->query('per_page', 10);
            $projects = $this->projectService->getByUser((int)$userId, $perPage);
            return response()->json([
                'data' => ProjectResource::collection($projects),
                'meta' => [
                    'current_page' => $projects->currentPage(),
                    'last_page' => $projects->lastPage(),
                    'per_page' => $projects->perPage(),
                    'total' => $projects->total(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ProjectController::getByUser', [
                'error' => $e->getMessage(),
                'user_id' => $request->query('user_id', auth()->id()),
            ]);
            return response()->json(['message' => 'Ошибка при получении проектов пользователя'], 500);
        }
    }

    public function join(Project $project): JsonResponse
    {
        try {
            $this->projectService->join($project->id, auth()->id());
            return response()->json([
                'message' => "Вы присоединились к проекту $project->name!"
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error in ProjectController::join', [
                'error' => $e->getMessage(),
                'project_id' => $project->id,
                'user_id' => auth()->id(),
            ]);
            return response()->json(['message' => 'Ошибка при присоединении к проекту'], 500);
        }
    }

    public function leave(Project $project): JsonResponse
    {
        try {
            $this->projectService->leave($project->id, auth()->id());
            return response()->json([
                'message' => "Вы покинули проект $project->name!"
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ProjectController::leave', [
                'error' => $e->getMessage(),
                'project_id' => $project->id,
                'user_id' => auth()->id(),
            ]);
            return response()->json(['message' => 'Ошибка при выходе из проекта'], 500);
        }
    }

    public function uploadPreview(UploadPreviewRequest $request, Project $project): JsonResponse
    {
        try {
            $project = $this->projectService->uploadPreview($project->id, $request->file('preview_image'));
            return response()->json([
                'message' => 'Превью успешно загружено!',
                'data' => new ProjectResource($project),
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ProjectController::uploadPreview', [
                'error' => $e->getMessage(),
                'project_id' => $project->id,
                'user_id' => auth()->id(),
            ]);
            return response()->json(['message' => 'Ошибка при загрузке превью'], 500);
        }
    }

    public function approve(Request $request, Project $project): JsonResponse
    {
        try {
            $project = $this->projectService->approve($project->id);
            return response()->json([
                'message' => "Проект $project->name подтверждён!",
                'data' => new ProjectResource($project)
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in ProjectController::approve', [
                'error' => $e->getMessage(),
                'project_id' => $project->id,
                'user_id' => auth()->id(),
            ]);
            return response()->json(['message' => 'Ошибка при подтверждении проекта'], 500);
        }
    }

    public function reject(Request $request, Project $project): JsonResponse
    {
        try {
            $this->projectService->reject($project->id);
            return response()->json([
                'message' => "Проект $project->name отклонён!"
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in ProjectController::reject', [
                'error' => $e->getMessage(),
                'project_id' => $project->id,
                'user_id' => auth()->id(),
            ]);
            return response()->json(['message' => 'Ошибка при отклонении проекта'], 500);
        }
    }

    public function uploadCertificate(UploadCertificateRequest $request, Project $project): JsonResponse
    {
        try {
            $project = $this->projectService->uploadCertificate($project->id, $request->file('certificate'));
            return response()->json([
                'message' => 'Сертификат успешно загружен!',
                'data' => new ProjectResource($project),
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ProjectController::uploadCertificate', [
                'error' => $e->getMessage(),
                'project_id' => $project->id,
                'user_id' => auth()->id(),
            ]);
            return response()->json(['message' => 'Ошибка при загрузке сертификата'], 500);
        }
    }
}
