<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoModule\DemoModuleController;

Route::middleware('permission:create-demoModule')->post('/', [DemoModuleController::class, 'createSingleDemoModule']);

Route::middleware('permission:readAll-demoModule')->get('/', [DemoModuleController::class, 'getAllDemoModule']);

Route::middleware('permission:readSingle-demoModule')->get('/{id}', [DemoModuleController::class, 'getSingleDemoModule']);

Route::middleware('permission:update-demoModule')->put('/{id}', [DemoModuleController::class, 'updateSingleDemoModule']);

Route::middleware('permission:delete-demoModule')->patch('/{id}', [DemoModuleController::class, 'deleteSingleDemoModule']);
