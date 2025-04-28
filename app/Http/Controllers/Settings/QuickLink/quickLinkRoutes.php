<?php

use App\Http\Controllers\Settings\QuickLink\QuickLinkController;
use Illuminate\Support\Facades\Route;


Route::middleware('permission:update-quickLink')->post('/update', [QuickLinkController::class, 'updateQuickLink']);
Route::middleware('permission:readAll-quickLink')->get('/', [QuickLinkController::class, 'getAllQuickLinks']);
Route::middleware('permission:readAll-quickLink')->get('/position', [QuickLinkController::class, 'getAllPosition']);
