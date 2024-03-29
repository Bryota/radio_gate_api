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
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
     * 機能リクエスト申請一覧の取得
     *
     * @return LengthAwarePaginator 機能リクエスト申請一覧
     */
    public function getAllRequestFunctionRequests(): LengthAwarePaginator
    {
        return $this->request_function_request::where('is_open', true)->orderBy('id', 'desc')->paginate(8);
    }

    /**
     * 機能リクエスト申請個別の取得
     * 
     * @param int $request_function_request_id 機能リクエスト申請ID
     * @return RequestFunctionRequest|null 機能リクエスト申請データ
     */
    public function getSingleRequestFunctionRequest(int $request_function_request_id): RequestFunctionRequest|null
    {
        return $this->request_function_request::where('id', $request_function_request_id)
            ->firstOrFail();
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

    /**
     * リクエスト機能申請を非公開にする
     * 
     * @param int $request_function_request_id リクエスト機能申請ID
     * @return bool 非公開にできたかどうか
     */
    public function closeRequestFunctionRequest(int $request_function_request_id): bool
    {
        $request_function_request = $this->request_function_request::where('id', $request_function_request_id)
            ->firstOrFail();
        $request_function_request->is_open = false;
        return $request_function_request->save();
    }
}
