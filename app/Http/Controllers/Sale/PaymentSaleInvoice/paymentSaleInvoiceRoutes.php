<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sale\PaymentSaleInvoice\PaymentSaleInvoiceController;



Route::middleware('permission:create-paymentSaleInvoice')->post("/", [PaymentSaleInvoiceController::class, 'createSinglePaymentSaleInvoice']);

Route::middleware('permission:readAll-paymentSaleInvoice')->get("/", [PaymentSaleInvoiceController::class, 'getAllPaymentSaleInvoice']);
