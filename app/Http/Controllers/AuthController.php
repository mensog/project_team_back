<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function register(StoreUserRequest $request): JsonResponse
    {
        ['user' => $user, 'password' => $password] = $this->userService->create($request->validated());
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->payloadResponse([
            'user' => new UserResource($user),
            'token' => $token,
            'password' => $password,
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->userService->login($request->only(['email', 'password']));

        if (!$user) {
            return $this->errorResponse('Неверные учетные данные.', 401);
        }

        $token = $user->createToken('API Token')->plainTextToken;

        return $this->payloadResponse([
            'user' => new UserResource($user),
            'token' => $token
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();
        return $this->messageResponse('Выход выполнен, токены удалены!');
    }
}
