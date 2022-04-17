<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListenerRequest;
use App\Services\Radio\ListenerService;

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
        $listener = $this->listener->CreateListener($request);

        if ($listener) {
            $request->session()->regenerate();

            return response()->json([
                'message' => 'アカウントが作成されました。',
                'listener' => $listener
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json([
                'message' => 'アカウントの作成に失敗しました。',
            ], 400, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
