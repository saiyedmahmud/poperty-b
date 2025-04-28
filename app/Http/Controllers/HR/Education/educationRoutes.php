<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HR\Education\EducationController;


Route::middleware('permission:create-education')->post("/", [EducationController::class, 'createSingleEducation']);

Route::middleware('permission:readAll-education')->get("/", [EducationController::class, 'getAllEducation']);

Route::middleware('permission:readAll-education,readSingle-education')->get("/{id}", [EducationController::class, 'getSingleEducation']);

Route::middleware('permission:update-education')->put("/{id}", [EducationController::class, 'updateSingleEducation']);

Route::middleware('permission:delete-education')->delete("/{id}", [EducationController::class, 'deleteSingleEducation']);

