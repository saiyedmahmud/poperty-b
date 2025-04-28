<?php

use App\Http\Controllers\Crm\Quote\QuoteStageController;
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

Route::middleware('permission:create-quoteStage')->post("/", [QuoteStageController::class, 'createQuoteStage']);

Route::middleware('permission:readAll-quoteStage')->get("/", [QuoteStageController::class, 'getAllQuoteStage']);

Route::middleware('permission:readSingle-quoteStage')->get("/{id}", [QuoteStageController::class, 'getSingleQuoteStage']);

Route::middleware('permission:update-quoteStage')->put("/{id}", [QuoteStageController::class, 'updateQuoteStage']);

Route::middleware('permission:delete-quoteStage')->delete("/{id}", [QuoteStageController::class, 'deleteQuoteStage']);
