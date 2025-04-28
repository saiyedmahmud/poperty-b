<?php

use App\Http\Controllers\HR\Project\TaskController;
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

Route::middleware('permission:create-projectTask')->post("/", [TaskController::class, 'createTask']);

Route::middleware('permission:readAll-projectTask')->get("/", [TaskController::class, 'getAllTask']);

Route::middleware('permission:readSingle-projectTask')->get("/{id}", [TaskController::class, 'getSingleTask']);

Route::middleware('permission:update-projectTask')->put("/{id}", [TaskController::class, 'updateTask']);

Route::middleware('permission:delete-projectTask')->delete("/{id}", [TaskController::class, 'deleteTask']);

