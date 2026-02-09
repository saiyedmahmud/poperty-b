<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Payment\PaymentController;

Route::middleware('permission:create-payment')->post('/', [PaymentController::class, 'createSinglePayment']);

Route::middleware('permission:readAll-payment')->get('/', [PaymentController::class, 'getAllPayment']);

Route::middleware('permission:readSingle-payment')->get('/{id}', [PaymentController::class, 'getSinglePayment']);

Route::middleware('permission:update-payment')->put('/{id}', [PaymentController::class, 'updateSinglePayment']);

Route::middleware('permission:delete-payment')->delete('/{id}', [PaymentController::class, 'deleteSinglePayment']);
