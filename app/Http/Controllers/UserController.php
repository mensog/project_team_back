<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function index(): JsonResponse
    {
        $users = $this->userService->all(10);
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
        $user = $this->userService->find($id);
        return response()->json(['data' => new UserResource($user)]);
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = $this->userService->update($id, $request->validated());
        return response()->json([
            'message' => 'Профиль успешно обновлён!',
            'data' => new UserResource($user)
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->userService->delete($id);
        return response()->json(null, 204);
    }
}
