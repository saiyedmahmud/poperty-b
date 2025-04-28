<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HR\Award\AwardController;





Route::middleware("permission:create-award")->post("/", [AwardController::class, 'createSingleAward']);

Route::middleware("permission:readAll-award")->get("/", [AwardController::class, 'getAllAward']);

Route::middleware("permission:readSingle-award")->get("/{id}", [AwardController::class, 'getSingleAward']);

Route::middleware("permission:update-award")->put("/{id}", [AwardController::class, 'updateSingleAward']);

Route::middleware("permission:delete-award")->patch("/{id}", [AwardController::class, 'deleteSingleAward']);

