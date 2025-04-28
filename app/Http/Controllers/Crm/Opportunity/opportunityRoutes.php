<?php


use App\Http\Controllers\Crm\Opportunity\OpportunityController;
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

Route::middleware("permission:create-opportunity")->post('/', [OpportunityController::class, 'createOpportunity']);
Route::middleware("permission:readAll-opportunity")->get('/', [OpportunityController::class, 'getAllOpportunity']);
Route::middleware("permission:readSingle-opportunity")->get('/{id}', [OpportunityController::class, 'getSingleOpportunity']);
Route::middleware("permission:update-opportunity")->put('/{id}', [OpportunityController::class, 'updateOpportunity']);
Route::middleware("permission:delete-opportunity")->patch('/{id}', [OpportunityController::class, 'deleteOpportunity']);

