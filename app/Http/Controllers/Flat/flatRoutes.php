<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Flat\FlatController;

Route::middleware('permission:create-flat')->post('/', [FlatController::class, 'createSingleFlat']);

Route::middleware('permission:readAll-flat')->get('/', [FlatController::class, 'getAllFlat']);

Route::middleware('permission:readSingle-flat')->get('/{id}', [FlatController::class, 'getSingleFlat']);

Route::middleware('permission:update-flat')->put('/{id}', [FlatController::class, 'updateSingleFlat']);

Route::middleware('permission:delete-flat')->delete('/{id}', [FlatController::class, 'deleteSingleFlat']);
