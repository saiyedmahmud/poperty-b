<?php

use App\Http\Controllers\Crm\Ticket\TicketCategoryController;
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

Route::middleware('permission:create-ticketCategory')->post("/", [TicketCategoryController::class, 'createTicketCategory']);

Route::middleware('permission:readAll-ticketCategory')->get("/", [TicketCategoryController::class, 'getAllTicketCategory']);

Route::middleware('permission:readSingle-ticketCategory')->get("/{id}", [TicketCategoryController::class, 'getSingleTicketCategory']);

Route::middleware('permission:update-ticketCategory')->put("/{id}", [TicketCategoryController::class, 'updateTicketCategory']);

Route::middleware('permission:delete-ticketCategory')->delete("/{id}", [TicketCategoryController::class, 'deleteTicketCategory']);
