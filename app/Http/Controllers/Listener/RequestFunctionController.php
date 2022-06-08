<?php

namespace App\Http\Controllers\Listener;

use App\Services\Listener\RequestFunctionService;
use App\Http\Requests\RequestFunctionRequest;
use App\Http\Requests\RequestFunctionListenerSubmitRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Connection;
use Illuminate\Http\Request;

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
        try {
            $request_functions = $this->request_function->getAllRequestFunctions();
            return response()->json([
                'request_functions' => $request_functions
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
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
            return response()->json([
                'request_function' => $request_function
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'リクエスト機能の取得に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * リクエスト機能作成
     *
     * @param RequestFunctionRequest $request リクエスト機能登録用のリクエストデータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RequestFunctionRequest $request)
    {
        $listener_id = $this->checkUserId();

        try {
            $this->db_connection->beginTransaction();
            $this->request_function->storeRequestFunction($request, intval($listener_id));
            $this->db_connection->commit();
            return response()->json([
                'message' => 'リクエスト機能が作成されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            $this->db_connection->rollBack();
            return response()->json([
                'message' => 'リクエスト機能の作成に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * リクエスト機能更新
     *
     * @param RequestFunctionRequest $request リクエスト機能更新リクエストデータ
     * @param int $request_function_id リクエスト機能ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(RequestFunctionRequest $request, int $request_function_id)
    {
        $listener_id = $this->checkUserId();

        try {
            $this->db_connection->beginTransaction();
            $this->request_function->updateRequestFunction($request, intval($listener_id), $request_function_id);
            $this->db_connection->commit();
            return response()->json([
                'message' => 'リクエスト機能が更新されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            $this->db_connection->rollBack();
            return response()->json([
                'message' => 'リクエスト機能の更新に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * リクエスト機能リスナー投票
     * 
     * @param RequestFunctionListenerSubmitRequest $request リクエスト機能リスナー投票用のリクエストデータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitListenerPoint(RequestFunctionListenerSubmitRequest $request)
    {
        $listener_id = $this->checkUserId();

        if ($this->request_function->isSubmittedListener($request->integer('request_function_id'), intval($listener_id))) {
            return response()->json([
                'message' => 'この機能には既に投票してあります。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        };
        try {
            $this->db_connection->beginTransaction();
            $this->request_function->submitListenerPoint($request, intval($listener_id));
            $this->db_connection->commit();
            return response()->json([
                'message' => '投票が完了しました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            $this->db_connection->rollBack();
            return response()->json([
                'message' => '投票に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * リクエスト機能削除（1つのみ）
     *
     * @param int $request_function_id リクエスト機能ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $request_function_id)
    {
        $listener_id = $this->checkUserId();

        try {
            $this->request_function->deleteRequestFunction(intval($listener_id), $request_function_id);
            return response()->json([
                'message' => 'リクエスト機能が削除されました。'
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'リクエスト機能の削除に失敗しました。'
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
