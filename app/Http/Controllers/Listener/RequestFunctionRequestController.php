<?php

namespace App\Http\Controllers\Listener;

use App\Services\Listener\RequestFunctionRequestService;
use App\Http\Requests\RequestFunctionRequestRequest;
use Illuminate\Database\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RequestFunctionRequestController extends Controller
{
    /**
     * @var RequestFunctionRequestService $request_function_request RequestFunctionRequestServiceインスタンス
     */
    private $request_function_request;

    /**
     * @var Connection $db_connection Connectionインスタンス
     */
    private $db_connection;

    /**
     * @var Request $request Requestインスタンス
     */
    private $request;

    public function __construct(RequestFunctionRequestService $request_function_request, Connection $db_connection, Request $request)
    {
        $this->request_function_request = $request_function_request;
        $this->db_connection = $db_connection;
        $this->request = $request;
    }

    /**
     * リクエスト機能申請作成
     *
     * @param RequestFunctionRequestRequest $request リクエスト機能申請用のリクエストデータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RequestFunctionRequestRequest $request)
    {
        $listener_id = $this->checkUserId();

        try {
            $this->db_connection->beginTransaction();
            $this->request_function_request->storeRequestFunctionRequest($request, intval($listener_id));
            $this->db_connection->commit();
            return response()->json([
                'message' => 'リクエスト機能申請が作成されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            $this->db_connection->rollBack();
            Log::error('機能リクエスト申請作成エラー', ['error' => $th, 'request' => $request]);
            return response()->json([
                'message' => 'リクエスト機能申請の作成に失敗しました。'
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
