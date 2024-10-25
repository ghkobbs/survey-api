<?php
use App\Http\Controllers\Api\SurveyController;
use App\Http\Controllers\Api\ResponseController;
use App\Http\Controllers\Api\AnalyticsController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    // Survey routes
    Route::apiResource('/surveys', SurveyController::class);
    
    // Response routes
    Route::post('/surveys/{survey}/responses', [ResponseController::class, 'store']);
    
    // Analytics routes
    Route::get('/surveys/{survey}/analytics', [AnalyticsController::class, 'show']);
});