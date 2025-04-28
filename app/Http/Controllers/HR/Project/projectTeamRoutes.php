<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HR\Project\ProjectTeamController;


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

Route::middleware('permission:create-team')->post("/", [ProjectTeamController::class, 'createProjectTeam']);

Route::middleware('permission:readAll-team')->get("/", [ProjectTeamController::class, 'getAllProjectTeams']);

Route::middleware('permission:readAll-team')->get("/{id}/project", [ProjectTeamController::class, 'getProjectTeamByProjectId']);

Route::middleware('permission:readSingle-team')->get("/{id}", [ProjectTeamController::class, 'getSingleProjectTeam']);

Route::middleware('permission:update-team')->put("/{id}", [ProjectTeamController::class, 'updateProjectTeam']);

Route::middleware('permission:delete-team')->patch("/{id}", [ProjectTeamController::class, 'deleteProjectTeam']);
