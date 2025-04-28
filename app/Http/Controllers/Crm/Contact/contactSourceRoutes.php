<?php

use App\Http\Controllers\Crm\Contact\ContactSourceController;
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

Route::middleware('permission:create-contactSource')->post("/", [ContactSourceController::class, 'createContactSource']);

Route::middleware('permission:readAll-contactSource')->get("/", [ContactSourceController::class, 'getAllContactSource']);

Route::middleware('permission:readSingle-contactSource')->get("/{id}", [ContactSourceController::class, 'getSingleContactSource']);

Route::middleware('permission:update-contactSource')->put("/{id}", [ContactSourceController::class, 'updateContactSource']);

Route::middleware('permission:delete-contactSource')->delete("/{id}", [ContactSourceController::class, 'deleteContactSource']);

