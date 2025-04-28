<?php

use App\Http\Controllers\Payment\ManualPayment\manualPaymentController;
use Illuminate\Support\Facades\Route;




Route::middleware('permission:create-manualPayment')->post('/', [manualPaymentController::class, 'createManualPayment']);
Route::middleware('permission:readAll-manualPayment')->get('/',[manualPaymentController::class,'getAllManualPayment']);
Route::middleware('permission:readSingle-manualPayment')->get('/payment-method/{id}',[manualPaymentController::class,'totalAmountByPaymentMethodId']);
Route::middleware('permission:readSingle-manualPayment')->get('/{id}',[manualPaymentController::class,'getSingleManualPayment']);
Route::middleware('permission:update-manualPayment')->put('/verify/{id}',[manualPaymentController::class,'verifiedManualPayment']);
