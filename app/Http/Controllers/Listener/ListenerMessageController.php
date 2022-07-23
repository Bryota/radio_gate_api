<?php

namespace App\Http\Controllers\Listener;

use App\Http\Requests\ListenerMessageRequest;
use App\Services\Listener\ListenerService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    /**
     * @var Request $request Requestインスタンス
     */
    private $request;

    public function __construct(ListenerService $listener, Connection $db_connection, Request $request)
    {
        $this->listener = $listener;
        $this->db_connection = $db_connection;
        $this->request = $request;
    }

    /**
     * リスナーに紐づいた投稿一覧の取得
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $listener_id = $this->checkUserId();

        try {
            $listener_messages = $this->listener->getAllListenerMessages(intval($listener_id));
            return response()->json(
                $listener_messages,
                200,
                [],
                JSON_UNESCAPED_UNICODE
            );
        } catch (ModelNotFoundException $e) {
            Log::error('投稿データ一覧がありませんでした。', ['error' => $e, 'listener_id' => $listener_id]);
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('投稿データ一覧取得エラー', ['error' => $th, 'listener_id' => $listener_id]);
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
        $listener_id = $this->checkUserId();

        try {
            $listener_message = $this->listener->getSingleListenerMessage(intval($listener_id), $listener_message_id);
            return response()->json(
                $listener_message,
                200,
                [],
                JSON_UNESCAPED_UNICODE
            );
        } catch (ModelNotFoundException $e) {
            Log::error('投稿データがありませんでした。', ['error' => $e, 'listener_id' => $listener_id]);
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('投稿データ取得エラー', ['error' => $th, 'listener_id' => $listener_id]);
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
        $listener_id = $this->checkUserId();

        try {
            $listener_messages = $this->listener->getAllListenerSavedMessages(intval($listener_id));
            return response()->json(
                $listener_messages,
                200,
                [],
                JSON_UNESCAPED_UNICODE
            );
        } catch (ModelNotFoundException $e) {
            Log::error('一時保存データ一覧がありませんでした。', ['error' => $e, 'listener_id' => $listener_id]);
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('一時保存一覧データ取得エラー', ['error' => $th, 'listener_id' => $listener_id]);
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
        $listener_id = $this->checkUserId();

        try {
            $this->db_connection->beginTransaction();
            $this->listener->storeListenerMyProgram($request, intval($listener_id));
            $this->listener->sendEmailToRadioProgram($request, intval($listener_id));
            $this->db_connection->commit();
            return response()->json([
                'message' => 'メッセージが投稿されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('メッセージ投稿エラー', ['error' => $th, 'listener_id' => $listener_id, 'request' => $request]);
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
        $listener_id = $this->checkUserId();

        try {
            $this->db_connection->beginTransaction();
            $this->listener->saveListenerMyProgram($request, intval($listener_id));
            $this->db_connection->commit();
            return response()->json([
                'message' => 'メッセージが保存されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('メッセージ一時保存エラー', ['error' => $th, 'listener_id' => $listener_id, 'request' => $request]);
            $this->db_connection->rollBack();
            return response()->json([
                'message' => 'メッセージの保存に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
    }

    // TODO: どっかで共通化したい
    /**
     * リスナーIDが取得できるかどうかの確認
     *
     * @return \Illuminate\Http\JsonResponse|int
     */
    private function checkUserId()
    {
        if (!$this->request->user()) {
            return response()->json([
                'message' => 'ログインしてください。'
            ], 401, [], JSON_UNESCAPED_UNICODE);
        }

        return $this->request->user()->id;
    }
}
