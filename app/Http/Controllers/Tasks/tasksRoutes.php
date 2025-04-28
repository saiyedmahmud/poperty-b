<?php

use App\Http\Controllers\Tasks\TasksController;
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

Route::middleware('permission:create-task')->post("/", [TasksController::class, 'createTasks']);

Route::middleware('permission:readAll-task')->get("/", [TasksController::class, 'getAllTasks']);

Route::middleware('permission:readSingle-task')->get("/{id}", [TasksController::class, 'getSingleTasks']);

Route::middleware('permission:update-task')->put("/{id}", [TasksController::class, 'updateTasks']);

Route::middleware('permission:delete-task')->delete("/{id}", [TasksController::class, 'deleteTasks']);

