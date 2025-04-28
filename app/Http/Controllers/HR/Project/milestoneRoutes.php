<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HR\Project\MilestoneController;

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
Route::middleware('permission:create-milestone')->post('/', [MilestoneController::class, 'createMilestone']);
Route::middleware('permission:readAll-milestone')->get('/', [MilestoneController::class, 'getAllMilestone']);
Route::middleware('permission:readAll-milestone')->get('/{id}/project', [MilestoneController::class, 'getMilestoneByProjectId']);
Route::middleware('permission:readSingle-milestone')->get('/{id}', [MilestoneController::class, 'getSingleMilestone']);
Route::middleware('permission:update-milestone')->put('/{id}', [MilestoneController::class, 'updateMilestone']);
Route::middleware('permission:delete-milestone')->patch('/{id}', [MilestoneController::class, 'deleteMilestone']);

