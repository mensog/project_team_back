<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Requests\Project\UploadPreviewRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\Interfaces\ProjectServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectServiceInterface $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => ProjectResource::collection($this->projectService->all())
        ]);
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $projectData = $request->validated();
        $project = $this->projectService->create($projectData);
        return response()->json([
            'message' => 'Проект успешно создан!',
            'data' => new ProjectResource($project)
        ], 201);
    }

    public function show(Project $project): JsonResponse
    {
        return response()->json([
            'data' => new ProjectResource($project)
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        $projectData = $request->validated();
        $project = $this->projectService->update($project->id, $projectData);
        return response()->json([
            'message' => 'Проект успешно обновлён!',
            'data' => new ProjectResource($project)
        ]);
    }

    public function destroy(Project $project): JsonResponse
    {
        $this->projectService->delete($project->id);
        return response()->json([
            'message' => 'Проект успешно удалён!'
        ], 204);
    }

    public function getByUser(Request $request): JsonResponse
    {
        $userId = $request->query('user_id') ?? auth()->id();
        $perPage = $request->query('per_page', 10);
        $projects = $this->projectService->getByUser((int)$userId, (int)$perPage);
        return response()->json([
            'data' => ProjectResource::collection($projects),
            'meta' => [
                'current_page' => $projects->currentPage(),
                'last_page' => $projects->lastPage(),
                'per_page' => $projects->perPage(),
                'total' => $projects->total(),
            ],
        ]);
    }

    public function join(Project $project): JsonResponse
    {
        $this->projectService->join($project->id, auth()->id());
        return response()->json([
            'message' => 'Вы успешно присоединились к проекту!'
        ], 201);
    }

    public function leave(Project $project): JsonResponse
    {
        $this->projectService->leave($project->id, auth()->id());
        return response()->json([
            'message' => 'Вы успешно покинули проект.'
        ]);
    }

    public function uploadPreview(UploadPreviewRequest $request, int $id): JsonResponse
    {
        $project = $this->projectService->uploadPreview($id, [
            'preview_image' => $request->file('preview_image')->store('project_previews', 'public')
        ]);
        return response()->json([
            'message' => 'Превью успешно загружено!',
            'data' => new ProjectResource($project)
        ]);
    }
}
