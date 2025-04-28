<?php

use App\Http\Controllers\Crm\Opportunity\OpportunityTypeController;
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

Route::middleware('permission:create-opportunityType')->post("/", [OpportunityTypeController::class, 'createOpportunityType']);

Route::middleware('permission:readAll-opportunityType')->get("/", [OpportunityTypeController::class, 'getAllOpportunityType']);

Route::middleware('permission:readSingle-opportunityType')->get("/{id}", [OpportunityTypeController::class, 'getSingleOpportunityType']);

Route::middleware('permission:update-opportunityType')->put("/{id}", [OpportunityTypeController::class, 'updateOpportunityType']);

Route::middleware('permission:delete-opportunityType')->delete("/{id}", [OpportunityTypeController::class, 'deleteOpportunityType']);

