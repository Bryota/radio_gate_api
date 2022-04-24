<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * リスナーログイン
     *
     * @param LoginRequest $request リスナーログインリクエストデータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json([
                'listener_info' => Auth::user()
            ], 200, [], JSON_UNESCAPED_UNICODE);
        }

        return response()->json([
            'message' => 'ログインに失敗しました。メールアドレスまたはパスワードが間違えていないかご確認ください。'
        ], 500, [], JSON_UNESCAPED_UNICODE);
    }
}
