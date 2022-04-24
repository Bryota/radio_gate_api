<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ListenerController;
use App\Http\Controllers\RadioStationController;
use App\Http\Controllers\RadioProgramController;
use App\Http\Controllers\ProgramCornerController;
use App\Http\Controllers\MessageTemplateController;
use App\Http\Controllers\ListenerMyProgramController;
use App\Http\Controllers\MyProgramCornerController;
use App\Http\Controllers\ListenerMessageController;
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
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
// TODO: パスワード再設定用の画面は別で設定する
Route::get('/test', function () {
    return 'test';
})->name('password.reset');
Route::post('/forgot_password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/listeners', [ListenerController::class, 'index']);
    Route::get('/listener', [ListenerController::class, 'show']);
    Route::put('/listener', [ListenerController::class, 'update']);
    Route::apiResource('radio_stations', RadioStationController::class);
    Route::apiResource('radio_programs', RadioProgramController::class);
    Route::apiResource('program_corners', ProgramCornerController::class);
    Route::apiResource('message_templates', MessageTemplateController::class);
    Route::apiResource('listener_my_programs', ListenerMyProgramController::class);
    Route::apiResource('my_program_corners', MyProgramCornerController::class);
    Route::apiResource('listener_messages', ListenerMessageController::class);
    Route::post('/listener_messages/save', [ListenerMessageController::class, 'save']);
    Route::get('/saved_messages', [ListenerMessageController::class, 'savedMessages']);
});
