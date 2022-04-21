<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ListenerController;
use App\Http\Controllers\RadioStationController;
use App\Http\Controllers\RadioProgramController;
use App\Http\Controllers\ProgramCornerController;
use App\Http\Controllers\MessageTemplateController;
use App\Http\Controllers\ListenerMyProgramController;
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

Route::post('/register', [RegisterController::class, 'create'])->name('register');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource('listeners', ListenerController::class);
    Route::apiResource('radio_stations', RadioStationController::class);
    Route::apiResource('radio_programs', RadioProgramController::class);
    Route::apiResource('program_corners', ProgramCornerController::class);
    Route::apiResource('message_templates', MessageTemplateController::class);
    Route::apiResource('listener_my_programs', ListenerMyProgramController::class);
});
