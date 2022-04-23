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
     * リスナーに紐づいた投稿一覧の取得
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $listener_messages = $this->listener->getAllListenerMessages(auth()->user()->id);
            return response()->json([
                'listener_messages' => $listener_messages
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => '投稿一覧の取得に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * リスナーに紐づいた投稿個別の取得
     * 
     * @param int $listener_message_id 投稿ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $listener_message_id)
    {
        try {
            $listener_message = $this->listener->getSingleListenerMessage(auth()->user()->id, $listener_message_id);
            return response()->json([
                'listener_message' => $listener_message
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => '投稿の取得に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
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
