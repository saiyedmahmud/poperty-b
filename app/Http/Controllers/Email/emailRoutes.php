<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Email\EmailController;




Route::middleware('permission:create-email', 'fileUploader:3')->post('/',[EmailController::class,'sendEmail']);
Route::middleware('permission:readAll-email')->get('/',[EmailController::class,'getEmails']);
Route::middleware('permission:readSingle-email')->get('/{id}',[EmailController::class,'getSingleEmail']);
Route::middleware('permission:update-email')->delete('/{id}',[EmailController::class,'deleteEmail']);

