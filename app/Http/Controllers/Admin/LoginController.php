<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json([
                'admin' => Auth::guard('admin')->user()
            ], 200, [], JSON_UNESCAPED_UNICODE);
        }
        Log::error('【管理者】ログインエラー', ['request' => $request]);
        return response()->json([
            'message' => 'ログインに失敗しました。メールアドレスまたはパスワードが間違えていないかご確認ください。'
        ], 500, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * リスナーログアウト
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'ログアウトに成功しました。'
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * ログインチェック
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authorized()
    {
        if (Auth::guard('admin')->check()) {
            return response()->json([
                'status' => 'success'
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json([
                'status' => 'failed'
            ], 200, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
