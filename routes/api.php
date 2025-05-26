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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/status/{status}', [NewsController::class, 'byStatus']);

Route::apiResource('users', UserController::class);
Route::apiResource('projects', ProjectController::class);
Route::apiResource('events', EventController::class);
Route::apiResource('news', NewsController::class)->except(['index']);
Route::apiResource('journal', JournalController::class);
Route::apiResource('ratings', RatingController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('certificates', CertificateController::class)->only(['index', 'store', 'destroy']);
    Route::get('/certificates/user/{userId}', [CertificateController::class, 'indexByUser']);
});

Route::get('/projects/search', [ProjectController::class, 'search']);
Route::get('/events/filter', [EventController::class, 'filter']);
