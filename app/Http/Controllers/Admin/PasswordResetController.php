<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PasswordResetRequest;
use App\DataProviders\Models\Admin;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Log;

class PasswordResetController extends Controller
{
    /**
     * パスワードリセット
     * 
     * @param PasswordResetRequest $request パスワードリセット用パラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(PasswordResetRequest $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'token' => $request->token
        ];

        $status = Password::broker('admins')->reset(
            $credentials,
            function (Admin $admin, $password) {
                $admin->forceFill([
                    'password' => Hash::make($password)
                ]);
                $admin->save();
                event(new PasswordReset($admin));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'messege' => 'パスワード変更に成功しました。'
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } else {
            Log::error('【管理者】パスワード更新エラー', ['status' => $status, 'request' => $request]);
            return response()->json([
                'messege' => 'パスワード変更に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
