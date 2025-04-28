<?php

use App\Http\Controllers\Crm\Task\CrmTaskStatusController;
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

Route::middleware('permission:create-taskStatus')->post("/", [CrmTaskStatusController::class, 'createCrmTaskStatus']);

Route::middleware('permission:readAll-taskStatus')->get("/", [CrmTaskStatusController::class, 'getAllCrmTaskStatus']);

Route::middleware('permission:readSingle-taskStatus')->get("/{id}", [CrmTaskStatusController::class, 'getSingleCrmTaskStatus']);

Route::middleware('permission:update-taskStatus')->put("/{id}", [CrmTaskStatusController::class, 'updateCrmTaskStatus']);

Route::middleware('permission:delete-taskStatus')->delete("/{id}", [CrmTaskStatusController::class, 'deleteCrmTaskStatus']);

