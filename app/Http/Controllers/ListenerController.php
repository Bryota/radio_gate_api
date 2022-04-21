<?php

namespace App\Http\Controllers;

use App\Services\Listener\ListenerService;

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
     * ユーザー情報取得
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
}
