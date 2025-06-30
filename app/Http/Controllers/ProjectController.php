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

    public function index(Request $request): JsonResponse
    {
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
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $project = $this->projectService->create($request->validated());
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
            'message' => "Проект с ID:$project->id успешно удалён!"
        ], 200);
    }

    public function getByUser(Request $request): JsonResponse
    {
        $userId = $request->query('user_id');
        if (!$userId) {
            return response()->json(['message' => 'Параметр user_id обязателен'], 400);
        }
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
    }

    public function join(Project $project): JsonResponse
    {
        $this->projectService->join($project->id, auth()->id());
        return response()->json([
            'message' => "Вы присоединились к проекту $project->name!"
        ], 201);
    }

    public function leave(Project $project): JsonResponse
    {
        $this->projectService->leave($project->id, auth()->id());
        return response()->json([
            'message' => "Вы покинули проект $project->name!"
        ]);
    }

    public function uploadPreview(UploadPreviewRequest $request, Project $project): JsonResponse
    {
        $project = $this->projectService->uploadPreview($project->id, $request->file('preview_image'));
        return response()->json([
            'message' => 'Превью успешно загружено!',
            'data' => new ProjectResource($project),
        ]);
    }
}
