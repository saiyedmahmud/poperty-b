<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HR\SalaryHistory\SalaryHistoryController;



Route::middleware('permission:create-salaryHistory')->post("/", [SalaryHistoryController::class, 'createSingleSalaryHistory']);

Route::middleware('permission:readAll-salaryHistory')->get("/", [SalaryHistoryController::class, 'getAllSalaryHistory']);

Route::middleware('permission:readSingle-salaryHistory')->get("/{id}", [SalaryHistoryController::class, 'getSingleSalaryHistory']);

Route::middleware('permission:update-salaryHistory')->put("/{id}", [SalaryHistoryController::class, 'updateSingleSalaryHistory']);

Route::middleware('permission:delete-salaryHistory')->delete("/{id}", [SalaryHistoryController::class, 'deleteSingleSalaryHistory']);

