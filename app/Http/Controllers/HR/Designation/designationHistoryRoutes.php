<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HR\Designation\DesignationHistoryController;



Route::middleware('permission:create-designationHistory')->post("/", [DesignationHistoryController::class, 'createSingleDesignationHistory']);

Route::middleware('permission:readAll-designationHistory')->get("/", [DesignationHistoryController::class, 'getAllDesignationHistory']);

Route::middleware('permission:readSingle-designationHistory')->get("/{id}", [DesignationHistoryController::class, 'getSingleDesignationHistory']);

Route::middleware('permission:update-designationHistory')->put("/{id}", [DesignationHistoryController::class, 'updateSingleDesignationHistory']);

Route::middleware('permission:delete-designationHistory')->delete("/{id}", [DesignationHistoryController::class, 'deleteSingleDesignationHistory']);

