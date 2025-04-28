<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Inventory\ProductCategory\ProductCategoryController;




Route::middleware('permission:create-productCategory')->post("/", [ProductCategoryController::class, 'createSingleProductCategory']);

Route::middleware('permission:readAll-productCategory')->get("/", [ProductCategoryController::class, 'getAllProductCategory']);

Route::middleware('permission:readAll-productCategory')->get("/{id}", [ProductCategoryController::class, 'getSingleProductCategory']);

Route::middleware('permission:update-productCategory')->put("/{id}", [ProductCategoryController::class, 'updateSingleProductCategory']);

Route::middleware('permission:delete-productCategory')->patch("/{id}", [ProductCategoryController::class, 'deleteSingleProductCategory']);


