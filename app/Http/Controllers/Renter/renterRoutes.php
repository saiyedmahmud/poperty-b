<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Renter\RenterController;

Route::middleware('permission:create-renter')->post('/', [RenterController::class, 'createSingleRenter']);

Route::middleware('permission:readAll-renter')->get('/', [RenterController::class, 'getAllRenter']);

Route::middleware('permission:readSingle-renter')->get('/{id}', [RenterController::class, 'getSingleRenter']);

Route::middleware('permission:update-renter')->put('/{id}', [RenterController::class, 'updateSingleRenter']);

Route::middleware('permission:delete-renter')->delete('/{id}', [RenterController::class, 'deleteSingleRenter']);
