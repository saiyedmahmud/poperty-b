<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HR\Department\DepartmentController;



Route::middleware('permission:create-department')->post("/", [departmentController::class, 'createSingleDepartment']);

Route::middleware('permission:readAll-department')->get("/", [departmentController::class, 'getAllDepartment']);

Route::middleware('permission:readAll-department,readSingle-department')->get('/{id}', [departmentController::class, 'getSingleDepartment']);

Route::middleware('permission:update-department')->put("/{id}", [departmentController::class, 'updateSingleDepartment']);

Route::middleware('permission:delete-department')->patch("/{id}", [departmentController::class, 'deletedDepartment']);

