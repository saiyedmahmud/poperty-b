<?php

use App\Http\Controllers\Crm\Contact\ContactStageController;
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

    Route::middleware('permission:create-contactStage')->post("/", [ContactStageController::class, 'createContactStage']);

    Route::middleware('permission:readAll-contactStage')->get("/", [ContactStageController::class, 'getAllContactStage']);

    Route::middleware('permission:readSingle-contactStage')->get("/{id}", [ContactStageController::class, 'getSingleContactStage']);

    Route::middleware('permission:update-contactStage')->put("/{id}", [ContactStageController::class, 'updateContactStage']);

    Route::middleware('permission:delete-contactStage')->delete("/{id}", [ContactStageController::class, 'deleteContactStage']);

