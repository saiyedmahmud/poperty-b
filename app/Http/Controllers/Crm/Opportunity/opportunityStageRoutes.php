<?php

use App\Http\Controllers\Crm\Opportunity\OpportunityStageController;
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

Route::middleware('permission:create-opportunityStage')->post("/", [OpportunityStageController::class, 'createOpportunityStage']);

Route::middleware('permission:readAll-opportunityStage')->get("/", [OpportunityStageController::class, 'getAllOpportunityStage']);

Route::middleware('permission:readSingle-opportunityStage')->get("/{id}", [OpportunityStageController::class, 'getSingleOpportunityStage']);

Route::middleware('permission:update-opportunityStage')->put("/{id}", [OpportunityStageController::class, 'updateOpportunityStage']);

Route::middleware('permission:delete-opportunityStage')->delete("/{id}", [OpportunityStageController::class, 'deleteOpportunityStage']);

