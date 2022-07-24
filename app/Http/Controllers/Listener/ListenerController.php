<?php

namespace App\Http\Controllers\Listener;

use App\Services\Listener\ListenerService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Log;

class ListenerController extends Controller
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
     * リスナー一覧取得
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $listeners = $this->listener->getAllListeners();
            return response()->json(
                $listeners,
                200,
                [],
                JSON_UNESCAPED_UNICODE
            );
        } catch (\Throwable $th) {
            Log::error('リスナー一覧の取得エラー', ['error' => $th]);
            return response()->json([
                'message' => 'リスナー一覧の取得に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * リスナー情報取得
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        $listener_id = $this->checkUserId();

        try {
            $listener = $this->listener->getSingleListener(intval($listener_id));
            return response()->json(
                $listener,
                200,
                [],
                JSON_UNESCAPED_UNICODE
            );
        } catch (ModelNotFoundException $e) {
            Log::error('リスナーデータが見つかりませんでした。（リスナーデータ取得）', ['error' => $e, 'listener_id' => $listener_id]);
            return response()->json([
                'message' => 'リスナーデータがありませんでした。'
            ], 404, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('リスナーデータ取得エラー', ['error' => $th, 'listener_id' => $listener_id]);
            return response()->json([
                'message' => 'リスナーデータの取得に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * リスナー情報更新
     *
     * @param \Illuminate\Http\Request $request リスナー更新用のリクエストデータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $listener_id = $this->checkUserId();

        try {
            $this->db_connection->beginTransaction();
            $this->listener->UpdateListener($request, intval($listener_id));
            $this->db_connection->commit();
            return response()->json([
                'message' => 'リスナーデータの更新に成功しました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            Log::error('リスナーデータが見つかりませんでした。（リスナーデータ更新）', ['error' => $e, 'listener_id' => $listener_id]);
            return response()->json([
                'message' => 'リスナーデータがありませんでした。'
            ], 404, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('リスナーデータ更新エラー', ['error' => $th, 'listener_id' => $listener_id]);
            $this->db_connection->rollBack();
            return response()->json([
                'message' => 'リスナーデータの更新に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * リスナー削除
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {
        $listener_id = $this->checkUserId();

        try {
            $this->listener->deleteListener(intval($listener_id));
            return response()->json([
                'message' => 'アカウントが削除されました。'
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('アカウント削除エラー', ['error' => $th, 'listener_id' => $listener_id]);
            return response()->json([
                'message' => 'アカウントの削除に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 同じメールアドレスが保存されていないかどうか
     * 
     * @param \Illuminate\Http\Request $request リクエストデータ
     * @return \Illuminate\Http\JsonResponse|int
     */
    public function isUniqueEmail(Request $request)
    {

        try {
            $is_unique_email = $this->listener->isUniqueEmail(strval($request->email));
            return response()->json([
                'is_unique_email' => $is_unique_email
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('メールアドレス確認エラー', ['error' => $th, 'request' => $request]);
            return response()->json([
                'message' => 'メールアドレスの確認に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
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
