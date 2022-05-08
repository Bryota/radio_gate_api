<?php

namespace App\Http\Controllers;

use App\Services\Listener\ListenerService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Database\Connection;

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

    public function __construct(ListenerService $listener, Connection $db_connection)
    {
        $this->listener = $listener;
        $this->db_connection = $db_connection;
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
            return response()->json([
                'listeners' => $listeners
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
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
        // TODO: どっかで共通化するかmiddlewareで対応したい
        if (!auth()->user()) {
            return response()->json([
                'message' => 'ログインしてください。'
            ], 401, [], JSON_UNESCAPED_UNICODE);
        }

        try {
            $listener = $this->listener->getSingleListener(auth()->user()->id);
            return response()->json([
                'listener' => $listener
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'リスナーデータがありませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
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
        // TODO: どっかで共通化するかmiddlewareで対応したい
        if (!auth()->user()) {
            return response()->json([
                'message' => 'ログインしてください。'
            ], 401, [], JSON_UNESCAPED_UNICODE);
        }

        try {
            $this->db_connection->beginTransaction();
            $this->listener->UpdateListener($request, auth()->user()->id);
            $this->db_connection->commit();
            return response()->json([
                'message' => 'リスナーデータの更新に成功しました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'リスナーデータがありませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            $this->db_connection->rollBack();
            return response()->json([
                'message' => 'リスナーデータの更新に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
