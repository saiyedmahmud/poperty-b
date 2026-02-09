<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Floor\FloorController;

Route::middleware('permission:create-floor')->post('/', [FloorController::class, 'createSingleFloor']);

Route::middleware('permission:readAll-floor')->get('/', [FloorController::class, 'getAllFloor']);

Route::middleware('permission:readSingle-floor')->get('/{id}', [FloorController::class, 'getSingleFloor']);

Route::middleware('permission:update-floor')->put('/{id}', [FloorController::class, 'updateSingleFloor']);

Route::middleware('permission:delete-floor')->delete('/{id}', [FloorController::class, 'deleteSingleFloor']);
