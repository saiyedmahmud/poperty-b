<?php

use App\Http\Controllers\Crm\Contact\ContactController;
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

Route::middleware("permission:create-contact")->post('/', [ContactController::class, 'createContact']);
Route::middleware("permission:readAll-contact")->get('/', [ContactController::class, 'getAllContact']);
Route::middleware("permission:readSingle-contact")->get('/{id}', [ContactController::class, 'getSingleContact']);
Route::middleware("permission:update-contact")->put('/{id}', [ContactController::class, 'updateContact']);
Route::middleware("permission:delete-contact")->patch('/{id}', [ContactController::class, 'deleteContact']);

