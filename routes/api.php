<?php

use App\Http\Controllers\RadioStationController;
use App\Http\Controllers\RadioProgramController;
use App\Http\Controllers\ProgramCornerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource('radio_stations', RadioStationController::class);
    Route::apiResource('radio_programs', RadioProgramController::class);
    Route::apiResource('program_corners', ProgramCornerController::class);
});
