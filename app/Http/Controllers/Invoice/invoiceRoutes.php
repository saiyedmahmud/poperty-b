<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Invoice\InvoiceController;

Route::middleware('permission:create-invoice')->post('/', [InvoiceController::class, 'createSingleInvoice']);

Route::middleware('permission:readAll-invoice')->get('/', [InvoiceController::class, 'getAllInvoice']);

Route::middleware('permission:readSingle-invoice')->get('/{id}', [InvoiceController::class, 'getSingleInvoice']);

Route::middleware('permission:update-invoice')->put('/{id}', [InvoiceController::class, 'updateSingleInvoice']);

Route::middleware('permission:delete-invoice')->delete('/{id}', [InvoiceController::class, 'deleteSingleInvoice']);
