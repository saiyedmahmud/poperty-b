<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Files\FilesController;



Route::get("/{id}", [FilesController::class, 'show']);
