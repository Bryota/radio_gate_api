<?php

namespace App\Http\Controllers\Admin;

use App\Services\Listener\RequestFunctionService;
use App\Http\Requests\RequestFunctionRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Connection;
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

    public function __construct(RequestFunctionService $request_function, Connection $db_connection)
    {
        $this->request_function = $request_function;
        $this->db_connection = $db_connection;
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
            Log::error('【管理者】リクエスト機能一覧がありませんでした。', ['error' => $e]);
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('【管理者】リクエスト機能一覧取得エラー', ['error' => $th]);
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
            Log::error('【管理者】リクエスト機能がありませんでした。', ['error' => $e]);
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('【管理者】リクエスト機能取得エラー', ['error' => $th]);
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
        try {
            $this->db_connection->beginTransaction();
            $this->request_function->storeRequestFunction($request);
            $this->db_connection->commit();
            return response()->json([
                'message' => 'リクエスト機能が作成されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            $this->db_connection->rollBack();
            Log::error('【管理者】リクエスト機能作成エラー', ['error' => $th]);
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
        try {
            $this->db_connection->beginTransaction();
            $this->request_function->updateRequestFunction($request, $request_function_id);
            $this->db_connection->commit();
            return response()->json([
                'message' => 'リクエスト機能が更新されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            Log::error('【管理者】リクエスト機能がありませんでした。（更新）', ['error' => $e]);
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            $this->db_connection->rollBack();
            Log::error('【管理者】リクエスト機能更新エラー', ['error' => $th]);
            return response()->json([
                'message' => 'リクエスト機能の更新に失敗しました。'
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
        try {
            $this->request_function->deleteRequestFunction($request_function_id);
            return response()->json([
                'message' => 'リクエスト機能が削除されました。'
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('【管理者】リクエスト機能削除エラー', ['error' => $th]);
            return response()->json([
                'message' => 'リクエスト機能の削除に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
