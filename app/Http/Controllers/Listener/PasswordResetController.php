<?php

namespace App\Http\Controllers\Listener;

use App\DataProviders\Models\Listener;
use App\Http\Requests\PasswordResetRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;

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

        $status = Password::reset(
            $credentials,
            function (Listener $listener, $password) {
                $listener->forceFill([
                    'password' => Hash::make($password)
                ]);
                $listener->save();
                event(new PasswordReset($listener));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'messege' => 'パスワード変更に成功しました。'
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json([
                'messege' => 'パスワード変更に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
