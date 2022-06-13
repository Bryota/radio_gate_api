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

use App\DataProviders\Models\RequestFunctionRequest;
use App\DataProviders\Repositories\RequestFunctionRequestRepository;
use App\Http\Requests\RequestFunctionRequestRequest;

/**
 * リクエスト機能用のサービスクラス
 *
 * @package App\Services
 * @version 1.0
 */
class RequestFunctionRequestService
{
    /**
     * @var RequestFunctionRequestRepository $request_function_request RequestFunctionRequestRepositoryインスタンス
     */
    private $request_function_request;

    /**
     * コンストラクタ
     *
     * @param RequestFunctionRequestRepository $request_function_request RequestFunctionRequestRepositoryインスタンス
     */
    public function __construct(RequestFunctionRequestRepository $request_function_request)
    {
        $this->request_function_request = $request_function_request;
    }

    /**
     * 機能リクエスト申請一覧
     * 
     * @return object 機能リクエスト一覧
     */
    public function getAllRequestFunctionRequests(): object
    {
        return $this->request_function_request->getAllRequestFunctionRequests();
    }

    /**
     * 機能リクエスト申請個別の取得
     *
     * @param int $request_function_request_id 機能リクエスト申請ID
     * @return RequestFunctionRequest|null 機能リクエスト申請データ
     */
    public function getSingleRequestFunctionRequest(int $request_function_request_id): RequestFunctionRequest|null
    {
        return $this->request_function_request->getSingleRequestFunctionRequest($request_function_request_id);
    }

    /**
     * リクエスト機能申請作成
     * 
     * @param RequestFunctionRequestRequest $request リクエスト機能申請用のリクエストデータ
     * @param int $listener_id リスナーID
     * @return RequestFunctionRequest 作成されたリクエスト機能申請情報
     */
    public function storeRequestFunctionRequest(RequestFunctionRequestRequest $request, int $listener_id): RequestFunctionRequest
    {
        return $this->request_function_request->storeRequestFunctionRequest($request, $listener_id);
    }
}
