<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\AuthController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class)->only(['index', 'show']);
    Route::apiResource('projects', ProjectController::class);
    Route::get('/projects/search', [ProjectController::class, 'search']);
    Route::apiResource('events', EventController::class);
    Route::get('/events/filter', [EventController::class, 'filter']);
    Route::apiResource('news', NewsController::class)->except(['index']);
    Route::get('/news/status/{status}', [NewsController::class, 'byStatus']);
    Route::apiResource('journal', JournalController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::apiResource('ratings', RatingController::class);
    Route::apiResource('certificates', CertificateController::class)->only(['index', 'store', 'destroy']);
    Route::get('/certificates/user/{userId}', [CertificateController::class, 'indexByUser']);
});
