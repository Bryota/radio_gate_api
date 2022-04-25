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
use App\Http\Requests\RequestFunctionRequest;

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
     * コンストラクタ
     *
     * @param RequestFunction $request_function RequestFunctionModel
     * @return void
     */
    public function __construct(RequestFunction $request_function)
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
        return $this->request_function::get(['id', 'name']);
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
}
