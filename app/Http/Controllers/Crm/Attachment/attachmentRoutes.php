<?php

use App\Http\Controllers\Crm\Attachment\AttachmentController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['permission:create-attachment', 'fileUploader:1'])->post("/", [AttachmentController::class, 'createAttachment']);

Route::middleware('permission:readAll-attachment')->get("/", [AttachmentController::class, 'getAllAttachments']);

Route::middleware('permission:readSingle-attachment')->get("/{id}", [AttachmentController::class, 'getSingleAttachment']);

Route::middleware('permission:delete-attachment')->delete("/{id}", [AttachmentController::class, 'deleteAttachment']);
