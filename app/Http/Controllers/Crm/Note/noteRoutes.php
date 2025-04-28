<?php

use App\Http\Controllers\Crm\Note\NoteController;
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

Route::middleware('permission:create-note')->post("/", [NoteController::class, 'createNote']);

Route::middleware('permission:readAll-note')->get("/", [NoteController::class, 'getAllNotes']);

Route::middleware('permission:readSingle-note')->get("/{id}", [NoteController::class, 'getSingleNote']);

Route::middleware('permission:update-note')->put("/{id}", [NoteController::class, 'updateNote']);

Route::middleware('permission:delete-note')->patch("/{id}", [NoteController::class, 'deleteNote']);
