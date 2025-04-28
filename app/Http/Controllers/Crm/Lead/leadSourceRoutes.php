<?php

use App\Http\Controllers\Crm\Lead\LeadSourceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes

|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These 
routes are loaded by the RouteServiceProvider and all of them will be 
assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('permission:create-leadSource')->post("/", [LeadSourceController::class, 'createLeadSource']);
Route::middleware('permission:readAll-leadSource')->get("/", [LeadSourceController::class, 'getAllLeadSource']);
Route::middleware('permission:readSingle-leadSource')->get("/{id}", [LeadSourceController::class, 'getSingleLeadSource']);
Route::middleware('permission:update-leadSource')->put("/{id}", [LeadSourceController::class, 'updateLeadSource']);
Route::middleware('permission:delete-leadSource')->delete("/{id}", [LeadSourceController::class, 'deleteLeadSource']);