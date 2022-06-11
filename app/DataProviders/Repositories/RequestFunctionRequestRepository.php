<?php

/**
 * リクエスト機能用のデータリポジトリ
 *
 * DBからリクエスト機能の情報取得・更新、削除の責務を担う
 *
 * @author s_ryota sryotapersian@gmail.com
 * @version 1.0
 * @copyright 2022 Ryota Segawa
 */

namespace App\DataProviders\Repositories;

use App\DataProviders\Models\RequestFunctionRequest;
use App\Http\Requests\RequestFunctionRequestRequest;

/**
 * リクエスト機能リポジトリクラス
 *
 * @package App\DataProviders\Repositories
 * @version 1.0
 */
class RequestFunctionRequestRepository
{
    /**
     * @var RequestFunctionRequest $request_function_request RequestFunctionRequestインスタンス
     */
    private $request_function_request;

    /**
     * コンストラクタ
     *
     * @param RequestFunctionRequest $request_function_request RequestFunctionRequestModel
     * @return void
     */
    public function __construct(RequestFunctionRequest $request_function_request)
    {
        $this->request_function_request = $request_function_request;
    }

    /**
     * リクエスト機能作成
     *
     * @param RequestFunctionRequestRequest $request リクエスト機能作成リクエストデータ
     * @param int $listener_id リスナーID
     * @return RequestFunctionRequest リクエスト機能生成データ
     */
    public function storeRequestFunctionRequest(RequestFunctionRequestRequest $request, int $listener_id): RequestFunctionRequest
    {
        return $this->request_function_request::create([
            'listener_id' => $listener_id,
            'name' => $request->name,
            'detail' => $request->detail
        ]);
    }
}
