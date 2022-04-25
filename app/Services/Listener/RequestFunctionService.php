<?php

/**
 * リクエスト機能用の機能関連のビジネスロジック
 *
 * リクエスト機能のアクションに関連する
 *
 * @author s_ryota sryotapersian@gmail.com
 * @version 1.0
 * @copyright 2022 Ryota Segawa
 */

namespace App\Services\Listener;

use App\DataProviders\Models\RequestFunction;
use App\DataProviders\Repositories\RequestFunctionRepository;
use App\Http\Requests\RequestFunctionRequest;

/**
 * リクエスト機能用のサービスクラス
 *
 * @package App\Services
 * @version 1.0
 */
class RequestFunctionService
{
    /**
     * @var RequestFunctionRepository $request_function RequestFunctionRepositoryインスタンス
     */
    private $request_function;

    /**
     * コンストラクタ
     *
     * @param RequestFunctionRepository $request_function RequestFunctionRepositoryインスタンス
     */
    public function __construct(RequestFunctionRepository $request_function)
    {
        $this->request_function = $request_function;
    }

    /**
     * リクエスト機能一覧の取得
     * 
     * @return object リクエスト機能一覧
     */
    public function getAllRequestFunctions(): object
    {
        $request_functions = $this->request_function->getAllRequestFunctions();
        return $request_functions;
    }

    /**
     * リクエスト機能個別の取得
     *
     * @param int $request_function_id リクエスト機能ID
     * @return RequestFunction リクエスト機能データ
     */
    public function getSingleRequestFunction(int $request_function_id): RequestFunction
    {
        $request_function = $this->request_function->getSingleRequestFunction($request_function_id);
        return $request_function;
    }

    /**
     * リクエスト機能作成
     * 
     * @param RequestFunctionRequest $request リクエスト機能登録用のリクエストデータ
     * @return RequestFunction 作成されたリクエスト機能情報
     */
    public function storeRequestFunction(RequestFunctionRequest $request): RequestFunction
    {
        $request_function = $this->request_function->storeRequestFunction($request);
        return $request_function;
    }
}