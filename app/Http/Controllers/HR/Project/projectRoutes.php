<?php

use App\Http\Controllers\HR\Project\ProjectController;
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
Route::middleware('permission:create-project')->post('/', [ProjectController::class, 'createProject']);
Route::middleware('permission:readAll-project')->get('/', [ProjectController::class, 'getAllProjects']);
Route::middleware('permission:readSingle-project')->get('/{id}', [ProjectController::class, 'getSingleProject']);
Route::middleware('permission:update-project')->put('/{id}', [ProjectController::class, 'updateProject']);
Route::middleware('permission:delete-project')->patch('/{id}', [ProjectController::class, 'deleteProject']);

