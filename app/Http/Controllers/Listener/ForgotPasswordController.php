<?php

namespace App\Http\Controllers\Listener;

use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * パスワード再設定メール送信
     * 
     * @param ForgotPasswordRequest $request パスワード再設定メール送信用リクエスト
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'messege' => 'パスワード再設定用のメールを送信しました。'
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json([
                'messege' => 'パスワード再設定用のメールの送信に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
