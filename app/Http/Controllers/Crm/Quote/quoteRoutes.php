<?php

use App\Http\Controllers\Crm\Quote\QuoteController;
use Illuminate\Support\Facades\Route;


Route::middleware('permission:create-quote')->post("/", [QuoteController::class, 'createQuote']);

Route::middleware('permission:readAll-quote')->get("/", [QuoteController::class, 'getAllQuote']);

Route::middleware('permission:readSingle-quote')->get("/{id}", [QuoteController::class, 'getSingleQuote']);

Route::middleware('permission:update-quote')->put("/{id}", [QuoteController::class, 'updateQuote']);

Route::middleware('permission:delete-quote')->patch("/{id}", [QuoteController::class, 'deleteQuote']);

Route::middleware('permission:delete-quote')->patch('/convert/{id}', [QuoteController::class, 'convertQuoteToInvoice']);

