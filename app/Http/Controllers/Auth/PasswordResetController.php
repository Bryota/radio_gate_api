<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);
                $user->save();
                event(new PasswordReset($user));
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
