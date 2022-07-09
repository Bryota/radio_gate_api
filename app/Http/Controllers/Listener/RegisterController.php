<?php

namespace App\Http\Controllers\Listener;

use App\Http\Requests\ListenerRequest;
use App\Services\Listener\ListenerService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /**
     * @var ListenerService $listener ListenerServiceインスタンス
     */
    private $listener;

    public function __construct(ListenerService $listener)
    {
        $this->listener = $listener;
    }

    /**
     * リスナー登録
     *
     * @param ListenerRequest $request リスナー登録用のリクエストデータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(ListenerRequest $request)
    {

        try {
            $this->listener->CreateListener($request);

            $credentials = [
                'email' => $request->email,
                'password' => $request->password
            ];
            // TODO: 失敗時の処理リファクタ
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return response()->json([
                    'message' => 'アカウントが作成されました。',
                ], 200, [], JSON_UNESCAPED_UNICODE);
            } else {
                return response()->json([
                    'message' => 'アカウントの作成に失敗しました。',
                ], 400, [], JSON_UNESCAPED_UNICODE);
            }
        } catch (\Throwable $th) {
            Log::error('アカウント作成エラー', ['error', $th]);
            return response()->json([
                'message' => 'アカウントの作成に失敗しました。',
            ], 400, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
