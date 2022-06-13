<?php

namespace App\Http\Controllers\Admin;

use App\Services\Listener\RequestFunctionRequestService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RequestFunctionRequestController extends Controller
{
    /**
     * @var RequestFunctionRequestService $request_function_request RequestFunctionRequestServiceインスタンス
     */
    private $request_function_request;

    public function __construct(RequestFunctionRequestService $request_function_request)
    {
        $this->request_function_request = $request_function_request;
    }

    /**
     * リクエスト機能申請一覧
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $request_function_requests = $this->request_function_request->getAllRequestFunctionRequests();
            return response()->json([
                'request_function_requests' => $request_function_requests
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'リクエスト機能申請一覧の取得に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * リクエスト機能申請個別の取得
     * 
     * @param int $request_function_request_id リクエスト機能申請ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $request_function_request_id)
    {
        try {
            $request_function_request = $this->request_function_request->getSingleRequestFunctionRequest($request_function_request_id);
            return response()->json([
                'request_function_request' => $request_function_request
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
}
