<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HR\Award\AwardHistoryController;




Route::middleware("permission:create-awardHistory")->post("/", [AwardHistoryController::class, 'createSingleAwardHistory']);

Route::middleware("permission:readAll-awardHistory")->get("/", [AwardHistoryController::class, 'getAllAwardHistory']);

Route::middleware("permission:readSingle-awardHistory")->get("/{id}", [AwardHistoryController::class, 'getSingleAwardHistory']);

Route::middleware("permission:update-awardHistory")->put("/{id}", [AwardHistoryController::class, 'updateSingleAwardHistory']);

Route::middleware("permission:delete-awardHistory")->delete("/{id}", [AwardHistoryController::class, 'deleteSingleAwardHistory']);
