<?php

use App\Http\Controllers\Listener\RegisterController;
use App\Http\Controllers\Listener\LoginController;
use App\Http\Controllers\Listener\ForgotPasswordController;
use App\Http\Controllers\Listener\PasswordResetController;
use App\Http\Controllers\Listener\ListenerController;
use App\Http\Controllers\Listener\RadioStationController;
use App\Http\Controllers\Listener\RadioProgramController;
use App\Http\Controllers\Listener\ProgramCornerController;
use App\Http\Controllers\Listener\MessageTemplateController;
use App\Http\Controllers\Listener\ListenerMyProgramController;
use App\Http\Controllers\Listener\MyProgramCornerController;
use App\Http\Controllers\Listener\ListenerMessageController;
use App\Http\Controllers\Listener\RequestFunctionController;
use App\Http\Controllers\Listener\RequestFunctionRequestController;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\ForgotPasswordController as AdminForgotPasswordController;
use App\Http\Controllers\Admin\PasswordResetController as AdminPasswordResetController;
use App\Http\Controllers\Admin\RadioStationController as AdminRadioStationController;
use App\Http\Controllers\Admin\RadioProgramController as AdminRadioProgramController;
use App\Http\Controllers\Admin\ProgramCornerController as AdminProgramCornerController;
use App\Http\Controllers\Admin\RequestFunctionController as AdminRequestFunctionController;
use App\Http\Controllers\Admin\RequestFunctionRequestController as AdminRequestFunctionRequestController;
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
// リスナー
Route::post('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
// TODO: パスワード再設定用の画面は別で設定する
Route::get('/test', function () {
    return 'test';
})->name('password.reset');
Route::post('/forgot_password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('password/reset/{token}', [PasswordResetController::class, 'resetPassword']);
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/authorized', [LoginController::class, 'authorized'])->name('authorized');
    Route::get('/listeners', [ListenerController::class, 'index']);
    Route::get('/listener', [ListenerController::class, 'show']);
    Route::put('/listener', [ListenerController::class, 'update']);
    Route::post('/listener/is_unique_email', [ListenerController::class, 'isUniqueEmail']);
    Route::delete('/listener', [ListenerController::class, 'destroy']);
    Route::apiResource('radio_stations', RadioStationController::class);
    Route::get('/radio_station_name/{id}', [RadioStationController::class, 'getRadioStationName']);
    Route::apiResource('radio_programs', RadioProgramController::class);
    Route::apiResource('program_corners', ProgramCornerController::class);
    Route::apiResource('message_templates', MessageTemplateController::class);
    Route::apiResource('listener_my_programs', ListenerMyProgramController::class);
    Route::apiResource('my_program_corners', MyProgramCornerController::class);
    Route::apiResource('listener_messages', ListenerMessageController::class);
    Route::post('/listener_messages/save', [ListenerMessageController::class, 'save']);
    Route::get('/saved_messages', [ListenerMessageController::class, 'savedMessages']);
    Route::apiResource('request_functions', RequestFunctionController::class, ['only' => ['index', 'show']]);
    Route::apiResource('request_function_requests', RequestFunctionRequestController::class, ['only' => ['store']]);
    Route::post('/request_functions/submit_point', [RequestFunctionController::class, 'submitListenerPoint']);
});

// 管理者
Route::post('admin/login', [AdminLoginController::class, 'login'])->name('admin.login');
Route::post('admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
Route::post('admin/forgot_password', [AdminForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('admin/password/reset/{token}', [AdminPasswordResetController::class, 'resetPassword']);
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::apiResource('radio_stations', AdminRadioStationController::class);
    Route::apiResource('radio_programs', AdminRadioProgramController::class);
    Route::apiResource('program_corners', AdminProgramCornerController::class);
    Route::get('/radio_station_name/{id}', [AdminRadioStationController::class, 'getRadioStationName']);
    Route::apiResource('request_functions', AdminRequestFunctionController::class);
    Route::apiResource('request_function_requests', AdminRequestFunctionRequestController::class, ['only' => ['index', 'show']]);
    Route::post('/request_function_requests/close/{id}', [AdminRequestFunctionRequestController::class, 'close']);
});
