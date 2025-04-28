<?php

use App\Http\Controllers\Crm\Company\CompanyTypeController;
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

Route::middleware("permission:create-companyType")->post('/', [CompanyTypeController::class, 'createCompanyType']);
Route::middleware("permission:readAll-companyType")->get('/', [CompanyTypeController::class, 'getAllCompanyType']);
Route::middleware("permission:readSingle-companyType")->get('/{id}', [CompanyTypeController::class, 'getSingleCompanyType']);
Route::middleware("permission:update-companyType")->put('/{id}', [CompanyTypeController::class, 'updateCompanyType']);
Route::middleware("permission:delete-companyType")->delete('/{id}', [CompanyTypeController::class, 'deleteCompanyType']);
