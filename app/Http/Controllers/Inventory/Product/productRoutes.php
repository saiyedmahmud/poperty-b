<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Inventory\Product\ProductController;


Route::middleware('permission:create-product')->post("/", [ProductController::class, 'createSingleProduct']);

Route::middleware('permission:readAll-product')->get("/", [ProductController::class, 'getAllProduct']);

Route::middleware('permission:readSingle-product')->get("/{id}", [ProductController::class, 'getSingleProduct']);

Route::middleware('permission:update-product')->put("/{id}", [ProductController::class, 'updateSingleProduct']);

Route::middleware('permission:delete-product')->patch("/{id}", [ProductController::class, 'deleteSingleProduct']);
