<?php

use App\Http\Controllers\HR\Project\TaskStatusController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('permission:create-taskStatus')->post("/", [TaskStatusController::class, 'createTaskStatus']);

Route::middleware('permission:readAll-taskStatus')->get("/", [TaskStatusController::class, 'getAllTaskStatus']);

Route::middleware('permission:readAll-taskStatus')->get("/{id}/project", [TaskStatusController::class, 'getTaskStatusByProjectId']);

Route::middleware('permission:readSingle-taskStatus')->get("/{id}", [TaskStatusController::class, 'getSingleTaskStatus']);

Route::middleware('permission:update-taskStatus')->put("/{id}", [TaskStatusController::class, 'updateTaskStatus']);

Route::middleware('permission:delete-taskStatus')->delete("/{id}", [TaskStatusController::class, 'deleteTaskStatus']);

