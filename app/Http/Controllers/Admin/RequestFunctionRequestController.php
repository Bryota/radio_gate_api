<?php

namespace App\Http\Controllers\Admin;

use App\Services\Listener\RequestFunctionRequestService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

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
            Log::error('【管理者】リクエスト機能申請一覧がありませんでした。', ['error' => $e]);
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('【管理者】リクエスト機能申請一覧取得エラー', ['error' => $th]);
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
            Log::error('【管理者】リクエスト機能申請がありませんでした。', ['error' => $e]);
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('【管理者】リクエスト機能申請取得エラー', ['error' => $th]);
            return response()->json([
                'message' => 'リクエスト機能申請の取得に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * リクエスト機能申請を非公開にする
     * 
     * @param int $request_function_request_id リクエスト機能申請ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function close(int $request_function_request_id)
    {
        try {
            $this->request_function_request->closeRequestFunctionRequest($request_function_request_id);
            return response()->json([
                'message' => '機能リクエスト申請を非公開にしました。'
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            Log::error('【管理者】リクエスト機能申請がありませんでした。（非公開）', ['error' => $e]);
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('【管理者】リクエスト機能申請非公開エラー', ['error' => $th]);
            return response()->json([
                'message' => '機能リクエスト申請を非公開にできませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
