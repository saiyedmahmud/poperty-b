<?php

use App\Http\Controllers\Crm\Task\CrmTaskController;
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
Route::middleware('permission:create-task')->post("/", [CrmTaskController::class, 'createTask']);

Route::middleware('permission:readAll-task')->get("/", [CrmTaskController::class, 'getAllTask']);

Route::middleware('permission:readSingle-task')->get("/{id}", [CrmTaskController::class, 'getSingleTask']);

Route::middleware('permission:update-task')->put("/{id}", [CrmTaskController::class, 'updateTask']);

Route::middleware('permission:delete-task')->patch("/{id}", [CrmTaskController::class, 'deleteTask']);
