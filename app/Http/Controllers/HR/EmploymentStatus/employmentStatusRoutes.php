<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HR\EmploymentStatus\EmploymentStatusController;




Route::middleware('permission:create-employmentStatus')->post("/", [EmploymentStatusController::class, 'createSingleEmployment']);

Route::middleware('permission:readAll-employmentStatus')->get("/", [EmploymentStatusController::class, 'getAllEmployment']);

Route::middleware('permission:readSingle-employmentStatus')->get("/{id}", [EmploymentStatusController::class, 'getSingleEmployment']);

Route::middleware('permission:delete-employmentStatus')->patch("/{id}", [EmploymentStatusController::class, 'deletedEmployment']);

