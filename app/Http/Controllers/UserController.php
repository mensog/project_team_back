<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UploadAvatarRequest;
use App\Http\Resources\UserResource;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 10);
        $users = $this->userService->all($perPage);
        return response()->json([
            'data' => UserResource::collection($users),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
        ]);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->validated());
        return response()->json([
            'message' => 'Пользователь успешно создан!',
            'data' => new UserResource($user)
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        try {
            Log::debug('Вызван метод show', ['id' => $id, 'type' => gettype($id)]);
            $user = $this->userService->find($id);
            return response()->json(['data' => new UserResource($user)]);
        } catch (\Exception $e) {
            Log::error('Ошибка в методе show', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: 404);
        }
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        try {
            $user = $this->userService->update($id, $request->validated());
            return response()->json([
                'message' => 'Профиль успешно обновлён!',
                'data' => new UserResource($user)
            ]);
        } catch (\Exception $e) {
            Log::error('Ошибка при обновлении пользователя', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }

    public function uploadAvatar(UploadAvatarRequest $request): JsonResponse
    {
        try {
            Log::debug('Вызван метод uploadAvatar', ['user_id' => auth()->id()]);
            $user = $this->userService->uploadAvatar(auth()->id(), $request->file('avatar'));
            return response()->json([
                'message' => 'Аватарка успешно загружена!',
                'data' => new UserResource($user)
            ], 200);
        } catch (\Exception $e) {
            Log::error('Ошибка в uploadAvatar', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Ошибка при загрузке аватарки: ' . $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->userService->delete($id);
            return response()->json([
                'message' => "Пользователь с ID:$id удалён."
            ], 200);
        } catch (\Exception $e) {
            Log::error('Ошибка при удалении пользователя', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }
}
