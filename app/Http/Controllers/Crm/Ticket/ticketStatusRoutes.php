<?php

use App\Http\Controllers\Crm\Ticket\TicketStatusController;
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

Route::middleware('permission:create-ticketStatus')->post("/", [TicketStatusController::class, 'createTicketStatus']);

Route::middleware('permission:readAll-ticketStatus')->get("/", [TicketStatusController::class, 'getAllTicketStatus']);

Route::middleware('permission:readSingle-ticketStatus')->get("/{id}", [TicketStatusController::class, 'getSingleTicketStatus']);

Route::middleware('permission:update-ticketStatus')->put("/{id}", [TicketStatusController::class, 'updateTicketStatus']);

Route::middleware('permission:delete-ticketStatus')->delete("/{id}", [TicketStatusController::class, 'deleteTicketStatus']);
