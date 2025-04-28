<?php

use App\Http\Controllers\Crm\Task\CrmTaskTypeController;
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

Route::middleware('permission:create-taskType')->post("/", [CrmTaskTypeController::class, 'createCrmTaskType']);

Route::middleware('permission:readAll-taskType')->get("/", [CrmTaskTypeController::class, 'getAllCrmTaskType']);

Route::middleware('permission:readSingle-taskType')->get("/{id}", [CrmTaskTypeController::class, 'getSingleCrmTaskType']);

Route::middleware('permission:update-taskType')->put("/{id}", [CrmTaskTypeController::class, 'updateCrmTaskType']);

Route::middleware('permission:delete-taskType')->delete("/{id}", [CrmTaskTypeController::class, 'deleteCrmTaskType']);
