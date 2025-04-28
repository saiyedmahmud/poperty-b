<?php

use App\Http\Controllers\Crm\Ticket\TicketCommentController;
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

Route::middleware(['permission:create-ticketComment', 'fileUploader:4'])->post("/", [TicketCommentController::class, 'createTicketComment']);

Route::middleware('permission:readAll-ticketComment')->get("/", [TicketCommentController::class, 'getAllTicketComment']);

Route::middleware('permission:readSingle-ticketComment')->get("/{ticketId}", [TicketCommentController::class, 'getAllTicketCommentByTicketId']);
