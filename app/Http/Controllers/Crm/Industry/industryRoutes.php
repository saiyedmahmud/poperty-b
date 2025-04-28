<?php

use App\Http\Controllers\Crm\Industry\IndustryController;
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

Route::middleware("permission:create-industry")->post('/', [IndustryController::class, 'crateIndustry']);
Route::middleware("permission:readAll-industry")->get('/', [IndustryController::class, 'getAllIndustry']);
Route::middleware("permission:readSingle-industry")->get('/{id}', [IndustryController::class, 'getSingleIndustry']);
Route::middleware("permission:update-industry")->put('/{id}', [IndustryController::class, 'updateIndustry']);
Route::middleware("permission:delete-industry")->delete('/{id}', [IndustryController::class, 'deleteIndustry']);

