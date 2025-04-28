<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Accounting\Account\AccountController;


Route::middleware('permission:create-account')->post('/', [AccountController::class, 'createSubAccount']);
Route::middleware('permission:readAll-account')->get('/', [AccountController::class, 'getAllAccount']);
Route::middleware('permission:readSingle-account')->get('/{id}', [AccountController::class, 'getSingleAccount']);
Route::middleware('permission:update-account')->put('/{id}', [AccountController::class, 'updateSubAccount']);
Route::middleware('permission:delete-account')->patch('/{id}', [AccountController::class, 'deleteSubAccount']);

