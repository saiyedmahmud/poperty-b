<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Building\BuildingController;

Route::middleware('permission:create-building')->post('/', [BuildingController::class, 'createSingleBuilding']);

Route::middleware('permission:readAll-building')->get('/', [BuildingController::class, 'getAllBuilding']);

Route::middleware('permission:readSingle-building')->get('/{id}', [BuildingController::class, 'getSingleBuilding']);

Route::middleware('permission:update-building')->put('/{id}', [BuildingController::class, 'updateSingleBuilding']);

Route::middleware('permission:delete-building')->delete('/{id}', [BuildingController::class, 'deleteSingleBuilding']);
