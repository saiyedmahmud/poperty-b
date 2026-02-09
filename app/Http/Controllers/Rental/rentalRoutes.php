<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Rental\RentalController;

Route::middleware('permission:create-rental')->post('/', [RentalController::class, 'createSingleRental']);

Route::middleware('permission:readAll-rental')->get('/', [RentalController::class, 'getAllRental']);

Route::middleware('permission:readSingle-rental')->get('/{id}', [RentalController::class, 'getSingleRental']);

Route::middleware('permission:update-rental')->put('/{id}', [RentalController::class, 'updateSingleRental']);

Route::middleware('permission:delete-rental')->delete('/{id}', [RentalController::class, 'deleteSingleRental']);
