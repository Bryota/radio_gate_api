<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListenerMessageRequest;
use App\Services\Listener\ListenerService;

class ListenerMessageController extends Controller
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
     * メッセージ投稿
     * 
     * @param ListenerMessageRequest $request メッセージ投稿用のリクエストデータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ListenerMessageRequest $request)
    {
        try {
            $this->listener->storeListenerMyProgram($request, auth()->user()->id);
            $this->listener->sendEmailToRadioProgram($request, auth()->user()->id);
            return response()->json([
                'message' => 'メッセージが投稿されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'メッセージの投稿に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
