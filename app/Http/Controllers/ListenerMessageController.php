<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListenerMessageRequest;
use App\Services\Listener\ListenerService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Connection;

class ListenerMessageController extends Controller
{
    /**
     * @var ListenerService $listener ListenerServiceインスタンス
     */
    private $listener;

    /**
     * @var Connection $db_connection Connectionインスタンス
     */
    private $db_connection;

    public function __construct(ListenerService $listener, Connection $db_connection)
    {
        $this->listener = $listener;
        $this->db_connection = $db_connection;
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
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
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
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => '投稿の取得に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * リスナーに紐づいた一時保存してある投稿一覧の取得
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function savedMessages()
    {
        try {
            $listener_messages = $this->listener->getAllListenerSavedMessages(auth()->user()->id);
            return response()->json([
                'listener_messages' => $listener_messages
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => '投稿一覧の取得に失敗しました。'
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
            $this->db_connection->beginTransaction();
            $this->listener->storeListenerMyProgram($request, auth()->user()->id);
            $this->listener->sendEmailToRadioProgram($request, auth()->user()->id);
            $this->db_connection->commit();
            return response()->json([
                'message' => 'メッセージが投稿されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            $this->db_connection->rollBack();
            return response()->json([
                'message' => 'メッセージの投稿に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * メッセージ一時保存
     * 
     * @param ListenerMessageRequest $request メッセージ保存用のリクエストデータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(ListenerMessageRequest $request)
    {
        try {
            $this->db_connection->beginTransaction();
            $this->listener->saveListenerMyProgram($request, auth()->user()->id);
            $this->db_connection->commit();
            return response()->json([
                'message' => 'メッセージが保存されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            $this->db_connection->rollBack();
            return response()->json([
                'message' => 'メッセージの保存に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
