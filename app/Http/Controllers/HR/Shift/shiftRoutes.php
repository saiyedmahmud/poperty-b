<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HR\Shift\ShiftController;




Route::middleware('permission:create-shift')->post("/", [ShiftController::class, 'createShift']);

Route::middleware('permission:readAll-shift')->get("/", [ShiftController::class, 'getAllShift']);

Route::middleware('permission:readSingle-shift')->get("/{id}", [ShiftController::class, 'getSingleShift']);

Route::middleware('permission:update-shift')->put("/{id}", [ShiftController::class, 'updateSingleShift']);

Route::middleware('permission:delete-shift')->patch("/{id}", [ShiftController::class, 'deleteSingleShift']);

