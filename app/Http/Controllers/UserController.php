<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UploadAvatarRequest;
use App\Http\Resources\UserResource;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 10);

        return $this->paginatedResponse(
            $this->userService->all($perPage),
            UserResource::class
        );
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        ['user' => $user, 'password' => $password] = $this->userService->create($request->validated());

        return $this->messageResponse('Пользователь успешно создан!', 201, [
            'data' => new UserResource($user),
            'password' => $password,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        return $this->guardedOperation(
            fn () => $this->successResponse(new UserResource($this->userService->find($id))),
            'Error retrieving user',
            ['user_id' => $id],
            'Пользователь не найден',
            404
        );
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        return $this->guardedOperation(
            fn () => $this->messageResponse('Профиль успешно обновлён!', 200, [
                'data' => new UserResource(
                    $this->userService->update($id, $request->validated())
                ),
            ]),
            'Error updating user',
            ['user_id' => $id],
            'Ошибка при обновлении пользователя'
        );
    }

    public function uploadAvatar(UploadAvatarRequest $request): JsonResponse
    {
        return $this->guardedOperation(
            function () use ($request) {
                $user = $this->userService->uploadAvatar(auth()->id(), $request->file('avatar'));

                return $this->messageResponse('Аватарка успешно загружена!', 200, [
                    'data' => new UserResource($user),
                ]);
            },
            'Error uploading avatar',
            ['user_id' => auth()->id()],
            'Ошибка при загрузке аватарки'
        );
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->guardedOperation(
            function () use ($id) {
                $this->userService->delete($id);

                return $this->messageResponse("Пользователь с ID:$id удалён.");
            },
            'Error deleting user',
            ['user_id' => $id],
            'Ошибка при удалении пользователя'
        );
    }
}
