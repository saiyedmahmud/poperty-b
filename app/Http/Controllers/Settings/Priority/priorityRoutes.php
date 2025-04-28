<?php

use App\Http\Controllers\Settings\Priority\PriorityController;
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
Route::middleware('permission:create-priority')->post('/', [PriorityController::class, 'createPriority']);
Route::middleware('permission:readAll-priority')->get('/', [PriorityController::class, 'getAllPriority']);
Route::middleware('permission:readSingle-priority')->get('/{id}', [PriorityController::class, 'getSinglePriority']);
Route::middleware('permission:update-priority')->put('/{id}', [PriorityController::class, 'updatePriority']);
Route::middleware('permission:delete-priority')->delete('/{id}', [PriorityController::class, 'deletePriority']);

