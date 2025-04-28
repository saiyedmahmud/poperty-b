<?php

use App\Http\Controllers\Payment\PaymentMethod\PaymentMethodController;
use Illuminate\Support\Facades\Route;





Route::middleware('permission:create-paymentMethod', 'fileUploader:1')->post('/',[PaymentMethodController::class,'createPaymentMethod']);
Route::middleware('permission:readAll-paymentMethod')->get('/',[PaymentMethodController::class,'getAllPaymentMethods']);
Route::middleware('permission:update-paymentMethod', 'fileUploader:1')->put('/{id}',[PaymentMethodController::class,'updatePaymentMethod']);
Route::middleware('permission:delete-paymentMethod')->patch('/{id}',[PaymentMethodController::class,'deletePaymentMethod']);
