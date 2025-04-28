<?php

use App\Http\Controllers\Crm\CrmEmail\CrmEmailController;
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

Route::middleware('permission:create-crmEmail')->post('/',[CrmEmailController::class,'createCrmEmail']);
Route::middleware('permission:readAll-crmEmail')->get('/',[CrmEmailController::class,'getAllCrmEmails']);
Route::middleware('permission:readSingle-crmEmail')->get('/{id}',[CrmEmailController::class,'getSingleCrmEmail']);
Route::middleware('permission:delete-crmEmail')->delete('/{id}',[CrmEmailController::class,'deleteCrmEmail']);

