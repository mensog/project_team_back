<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->middleware('auth:sanctum');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/users', [UserController::class, 'index']);
Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{id}', [NewsController::class, 'show']);
Route::get('/news/status/{status}', [NewsController::class, 'byStatus']);

Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{event}', [EventController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/projects/by-user', [ProjectController::class, 'getByUser']);
    Route::apiResource('users', UserController::class)->only(['show', 'update', 'destroy'])->parameters(['users' => 'id']);
    Route::post('/users/avatar', [UserController::class, 'uploadAvatar']);
    Route::apiResource('projects', ProjectController::class);
    Route::post('/projects/{project}/join', [ProjectController::class, 'join']);
    Route::post('/projects/{project}/leave', [ProjectController::class, 'leave']);
    Route::post('/projects/{project}/preview', [ProjectController::class, 'uploadPreview']);
    Route::post('/projects/{project}/approve', [ProjectController::class, 'approve']);
    Route::post('/projects/{project}/reject', [ProjectController::class, 'reject']);
    Route::post('/projects/{project}/upload-certificate', [ProjectController::class, 'uploadCertificate']);
    Route::apiResource('events', EventController::class)->only(['store', 'update', 'destroy']);
    Route::post('/events/{id}/preview', [EventController::class, 'uploadPreview']);
    Route::apiResource('news', NewsController::class)->only(['store', 'update', 'destroy']);
    Route::post('/news/{id}/preview', [NewsController::class, 'uploadPreview']);
    Route::apiResource('journals', JournalController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::apiResource('certificates', CertificateController::class)->only(['index', 'store', 'destroy']);
    Route::get('/certificates/by-user/{user}', [CertificateController::class, 'indexByUser']);
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::patch('/notifications/{uuid}/read', [NotificationController::class, 'markAsRead']);
});
