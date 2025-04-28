<?php

use App\Http\Controllers\Crm\Opportunity\OpportunitySourceController;
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

Route::middleware('permission:create-opportunitySource')->post("/", [OpportunitySourceController::class, 'createOpportunitySource']);

Route::middleware('permission:readAll-opportunitySource')->get("/", [OpportunitySourceController::class, 'getAllOpportunitySource']);

Route::middleware('permission:readSingle-opportunitySource')->get("/{id}", [OpportunitySourceController::class, 'getSingleOpportunitySource']);

Route::middleware('permission:update-opportunitySource')->put("/{id}", [OpportunitySourceController::class, 'updateOpportunitySource']);

Route::middleware('permission:delete-opportunitySource')->delete("/{id}", [OpportunitySourceController::class, 'deleteOpportunitySource']);

