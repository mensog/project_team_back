<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
        // $this->authorizeResource(User::class, 'user');
    }

    public function index()
    {
        $users = $this->userService->all();
        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $userData = $request->validated();
        $userData['password'] = bcrypt($request->password);

        $user = $this->userService->create($userData);

        return new UserResource($user);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $userData = $request->validated();

        if ($request->has('password')) {
            $userData['password'] = bcrypt($request->password);
        }

        $user = $this->userService->update($user->id, $userData);

        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $this->userService->delete($user->id);

        return response()->json(null, 204);
    }
}
