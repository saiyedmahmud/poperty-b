<?php

use Faker\Core\File;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Files\FilesController;
use App\Http\Controllers\Crm\Ticket\TicketController;

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

Route::middleware(['permission:create-ticket', 'fileUploader:4'])->post("/", [TicketController::class, 'createTicket']);

Route::middleware('permission:readAll-ticket')->get("/", [TicketController::class, 'getAllTicket']);

Route::middleware('permission:readAll-ticket')->get("/customer", [TicketController::class, 'getAllTicketByCustomerId']);

Route::middleware('permission:readSingle-ticket')->get("/{id}", [TicketController::class, 'getSingleTicket']);

Route::middleware('permission:update-ticket')->put("/{id}", [TicketController::class, 'updateTicket']);

Route::middleware('permission:delete-ticket')->delete("/{id}", [TicketController::class, 'deleteTicket']);


