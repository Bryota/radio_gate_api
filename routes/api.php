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
use App\Http\Controllers\Listener\InqueryController;
use App\Http\Controllers\Listener\DeveloperContactController;
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
Route::post('/register', [RegisterController::class, 'create']);
Route::get('/listener/unique-email', [ListenerController::class, 'isUniqueEmail']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::put('/listener/password', [PasswordResetController::class, 'resetPassword']);
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/authorized', [LoginController::class, 'authorized']);
    Route::get('/listeners', [ListenerController::class, 'index']);
    Route::get('/listener', [ListenerController::class, 'show']);
    Route::put('/listener', [ListenerController::class, 'update']);
    Route::delete('/listener', [ListenerController::class, 'destroy']);
    Route::apiResource('radio-stations', RadioStationController::class);
    Route::apiResource('radio-programs', RadioProgramController::class);
    Route::apiResource('program-corners', ProgramCornerController::class);
    Route::apiResource('message-templates', MessageTemplateController::class);
    Route::apiResource('listener-my-programs', ListenerMyProgramController::class);
    Route::apiResource('my-program-corners', MyProgramCornerController::class);
    Route::apiResource('listener-messages', ListenerMessageController::class);
    Route::get('/saved-messages', [ListenerMessageController::class, 'savedMessages']);
    Route::post('/saved-messages', [ListenerMessageController::class, 'save']);
    Route::apiResource('request-functions', RequestFunctionController::class, ['only' => ['index', 'show']]);
    Route::apiResource('request-function-requests', RequestFunctionRequestController::class, ['only' => ['store']]);
    Route::post('/request-functions/{id}/point', [RequestFunctionController::class, 'submitListenerPoint']);
    Route::post('/inquery', [InqueryController::class, 'send']);
    Route::post('/developer-contact', [DeveloperContactController::class, 'send']);
});

// 管理者
Route::post('admin/login', [AdminLoginController::class, 'login']);
Route::post('admin/logout', [AdminLoginController::class, 'logout']);
Route::post('admin/forgot-password', [AdminForgotPasswordController::class, 'sendResetLinkEmail']);
Route::put('admin/password', [AdminPasswordResetController::class, 'resetPassword']);
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
    Route::get('/authorized', [AdminLoginController::class, 'authorized']);
    Route::apiResource('radio-stations', AdminRadioStationController::class);
    Route::apiResource('radio-programs', AdminRadioProgramController::class);
    Route::apiResource('program-corners', AdminProgramCornerController::class);
    Route::get('/radio-station/{id}/name', [AdminRadioStationController::class, 'getRadioStationName']);
    Route::apiResource('request-functions', AdminRequestFunctionController::class);
    Route::apiResource('request-function-requests', AdminRequestFunctionRequestController::class, ['only' => ['index', 'show']]);
    Route::post('/request-function-requests/{id}/close/', [AdminRequestFunctionRequestController::class, 'close']);
});
