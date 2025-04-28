<?php

use App\Http\Controllers\Crm\Company\CompanyController;
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

Route::middleware("permission:create-company")->post('/', [CompanyController::class, 'createCompany']);
Route::middleware("permission:readAll-company")->get('/', [CompanyController::class, 'getAllCompanies']);
Route::middleware("permission:readSingle-company")->get('/{id}', [CompanyController::class, 'getSingleCompany']);
Route::middleware("permission:update-company")->put('/{id}', [CompanyController::class, 'updateCompany']);
Route::middleware("permission:delete-company")->patch('/{id}', [CompanyController::class, 'deleteCompany']);

