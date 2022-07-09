<?php

namespace App\Http\Controllers\Listener;

use App\Services\Listener\RequestFunctionService;
use App\Http\Requests\RequestFunctionListenerSubmitRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RequestFunctionController extends Controller
{
    /**
     * @var RequestFunctionService $request_function RequestFunctionServiceインスタンス
     */
    private $request_function;

    /**
     * @var Connection $db_connection Connectionインスタンス
     */
    private $db_connection;

    /**
     * @var Request $request Requestインスタンス
     */
    private $request;

    public function __construct(RequestFunctionService $request_function, Connection $db_connection, Request $request)
    {
        $this->request_function = $request_function;
        $this->db_connection = $db_connection;
        $this->request = $request;
    }

    /**
     * リクエスト機能一覧の取得
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $listener_id = $this->checkUserId();

        try {
            $request_functions = $this->request_function->getAllOpenRequestFunctions(intval($listener_id));
            return response()->json([
                'request_functions' => $request_functions
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            Log::error('機能リクエスト一覧がありませんでした。', ['error' => $e]);
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('機能リクエスト一覧取得エラー', ['error' => $th]);
            return response()->json([
                'message' => 'リクエスト機能一覧の取得に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * リクエスト機能個別の取得
     * 
     * @param int $request_function_id リクエスト機能ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $request_function_id)
    {
        try {
            $request_function = $this->request_function->getSingleRequestFunction($request_function_id);
            if ($request_function && $request_function->is_open) {
                return response()->json([
                    'status' => 'success',
                    'request_function' => $request_function
                ], 200, [], JSON_UNESCAPED_UNICODE);
            } else {
                return response()->json([
                    'status' => 'failed'
                ], 200, [], JSON_UNESCAPED_UNICODE);
            }
        } catch (ModelNotFoundException $e) {
            Log::error('機能リクエストがありませんでした。', ['error' => $e]);
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('機能リクエスト取得エラー', ['error' => $th]);
            return response()->json([
                'message' => 'リクエスト機能の取得に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * リクエスト機能リスナー投票
     * 
     * @param RequestFunctionListenerSubmitRequest $request リクエスト機能リスナー投票用のリクエストデータ
     * @param int $id 機能リクエストID
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitListenerPoint(RequestFunctionListenerSubmitRequest $request, int $id)
    {
        $listener_id = $this->checkUserId();

        if ($this->request_function->isSubmittedListener($id, intval($listener_id))) {
            return response()->json([
                'message' => 'この機能には既に投票してあります。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        };
        try {
            $this->db_connection->beginTransaction();
            $this->request_function->submitListenerPoint($id, $request, intval($listener_id));
            $this->db_connection->commit();
            return response()->json([
                'message' => '投票が完了しました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            $this->db_connection->rollBack();
            Log::error('機能リクエスト投稿エラー', ['error' => $th, 'request' => $request]);
            return response()->json([
                'message' => '投票に失敗しました。'
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
