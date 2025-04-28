<?php

use App\Http\Controllers\Crm\Lead\LeadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('permission:create-lead')->post("/", [LeadController::class, 'createLead']);
Route::middleware('permission:readAll-lead')->get("/", [LeadController::class, 'getAllLead']);
Route::middleware('permission:readSingle-lead')->get("/{id}", [LeadController::class, 'getSingleLead']);
Route::middleware('permission:update-lead')->put("/{id}", [LeadController::class, 'updateLead']);
Route::middleware('permission:delete-lead')->patch("/{id}", [LeadController::class, 'deleteLead']);