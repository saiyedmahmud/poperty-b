<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\CustomerController;


Route::post("/login", [CustomerController::class, 'customerLogin']);
Route::post("/logout", [CustomerController::class, 'Logout']);
Route::post("/", [CustomerController::class, 'createSingleCustomer']);
Route::post("/register", [CustomerController::class, 'registerCustomer']);

Route::middleware('permission:update-customer')->patch("/reset-password/{id}", [CustomerController::class, 'resetPassword']);
Route::middleware('permission:readSingle-customer')->get('/profile', [CustomerController::class, 'getProfile']);
Route::middleware('permission:update-customer')->put("/profile/update", [CustomerController::class, 'profileUpdate']);


Route::post("/request-forgot-password", [CustomerController::class, 'requestForgetPassword']);
Route::patch("/forgot-password", [CustomerController::class, 'forgotPassword']);


Route::middleware('permission:readAll-customer')->get("/", [CustomerController::class, 'getAllCustomer']);

Route::get("/email", [CustomerController::class, 'getCustomerByEmail']);
Route::middleware('permission:readSingle-customer')->get("/{id}", [CustomerController::class, 'getSingleCustomer']);

Route::middleware('permission:update-customer', 'fileUploader:1')->put("/{id}", [CustomerController::class, 'updateSingleCustomer']);

Route::middleware('permission:delete-customer')->patch("/{id}", [CustomerController::class, 'deleteSingleCustomer']);

