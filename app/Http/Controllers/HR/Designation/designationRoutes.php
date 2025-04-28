<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HR\Designation\DesignationController;



Route::middleware('permission:create-designation')->post("/", [DesignationController::class, 'createSingleDesignation']);

Route::middleware('permission:readAll-designation')->get("/", [DesignationController::class, 'getAllDesignation']);

Route::middleware('permission:readSingle-designation')->get("/{id}", [DesignationController::class, 'getSingleDesignation']);

Route::middleware('permission:update-designation')->put("/{id}", [DesignationController::class, 'updateSingleDesignation']);

Route::middleware('permission:delete-designation')->patch("/{id}", [DesignationController::class, 'deleteSingleDesignation']);
