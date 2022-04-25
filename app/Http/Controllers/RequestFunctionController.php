<?php

namespace App\Http\Controllers;

use App\Services\Listener\RequestFunctionService;
use App\Http\Requests\RequestFunctionRequest;

class RequestFunctionController extends Controller
{
    /**
     * @var RequestFunctionService $request_function RequestFunctionServiceインスタンス
     */
    private $request_function;

    public function __construct(RequestFunctionService $request_function)
    {
        $this->request_function = $request_function;
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
            $this->request_function->storeRequestFunction($request);
            return response()->json([
                'message' => 'リクエスト機能が作成されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'リクエスト機能の作成に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
