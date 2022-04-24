<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListenerRequest;
use App\Services\Listener\ListenerService;
use Illuminate\Http\Request;

class ListenerController extends Controller
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
     * リスナー情報取得
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        try {
            $listener = $this->listener->getSingleListener(auth()->user()->id);
            return response()->json([
                'listener' => $listener
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'リスナーデータの取得に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * リスナー情報更新
     *
     * @param \Illuminate\Http\Request $request リスナー登録用のリクエストデータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        try {
            $this->listener->UpdateListener($request, auth()->user()->id);
            return response()->json([
                'message' => 'リスナーデータの更新に成功しました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'リスナーデータの更新に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
