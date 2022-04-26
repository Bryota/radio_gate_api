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

use App\DataProviders\Models\RequestFunction;
use App\DataProviders\Models\RequestFunctionListenerSubmit;
use App\Http\Requests\RequestFunctionRequest;
use App\Http\Requests\RequestFunctionListenerSubmitRequest;

/**
 * リクエスト機能リポジトリクラス
 *
 * @package App\DataProviders\Repositories
 * @version 1.0
 */
class RequestFunctionRepository
{
    /**
     * @var RequestFunction $request_function RequestFunctionインスタンス
     */
    private $request_function;

    /**
     * @var RequestFunctionListenerSubmit $request_function_listener_submit RequestFunctionListenerSubmitインスタンス
     */
    private $request_function_listener_submit;

    /**
     * コンストラクタ
     *
     * @param RequestFunction $request_function RequestFunctionModel
     * @return void
     */
    public function __construct(RequestFunction $request_function, RequestFunctionListenerSubmit $request_function_listener_submit)
    {
        $this->request_function = $request_function;
        $this->request_function_listener_submit = $request_function_listener_submit;
    }

    /**
     * リクエスト機能一覧の取得
     * 
     * @return object リクエスト機能一覧
     */
    public function getAllRequestFunctions(): object
    {
        return $this->request_function::get(['id', 'name', 'point']);
    }

    /**
     * リクエスト機能個別の取得
     * 
     * @param int $request_function_id リクエスト機能ID
     * @return RequestFunction リクエスト機能データ
     */
    public function getSingleRequestFunction(int $request_function_id): RequestFunction
    {
        return $this->request_function::where('id', $request_function_id)
            ->first();
    }

    /**
     * リクエスト機能作成
     *
     * @param RequestFunctionRequest $request リクエスト機能作成リクエストデータ
     * @return RequestFunction リクエスト機能生成データ
     */
    public function storeRequestFunction(RequestFunctionRequest $request): RequestFunction
    {
        return $this->request_function::create($request->all());
    }

    /**
     * リクエスト機能更新
     *
     * @param RequestFunctionRequest $request リクエスト機能更新リクエストデータ
     * @param int $listener_id リスナーID
     * @param int $request_function_id リクエスト機能ID
     * @return bool 更新できたかどうか
     */
    public function updateRequestFunction(RequestFunctionRequest $request, int $listener_id, int $request_function_id): bool
    {
        $request_function = $this->request_function::where('id', $request_function_id)
            ->where('listener_id', $listener_id)
            ->first();
        $request_function->name = $request->name;
        $request_function->detail = $request->detail;
        return $request_function->save();
    }


    /**
     * リクエスト機能リスナー投票
     * 
     * @param RequestFunctionListenerSubmitRequest $request リクエスト機能リスナー投票用のリクエストデータ
     * @param int $listener_id リスナーID
     * @return void
     */
    public function submitListenerPoint(RequestFunctionListenerSubmitRequest $request, int $listener_id)
    {
        $this->request_function_listener_submit::create([
            'listener_id' => $listener_id,
            'request_function_id' => $request->request_function_id,
            'point' => (int)$request->point
        ]);
        $request_function = $this->getSingleRequestFunction($request->request_function_id);
        $request_function->point = $request_function->point + (int)$request->point;
        $request_function->save();
    }

    /**
     * 投票した機能かどうか
     * 
     * @param int $request_function_id リクエスト機能ID
     * @param int $listener_id リスナーID
     * @return bool
     */
    public function isSubmittedListener(int $request_function_id, int $listener_id): bool
    {
        return $this->request_function_listener_submit::where('request_function_id', $request_function_id)
            ->where('listener_id', $listener_id)
            ->exists();
    }
}
