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
use App\Http\Requests\RequestFunctionListenerSubmitRequest;

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
     * 機能リクエスト一覧の取得
     *
     * @return object 機能リクエスト一覧
     */
    public function getAllRequestFunctions(): object
    {
        return $this->request_function->getAllRequestFunctions();
    }

    /**
     * 公開状態の機能リクエスト一覧の取得
     *
     * @param int $listener_id リスナーID
     * @return array リクエスト機能一覧
     */
    public function getAllOpenRequestFunctions(int $listener_id = 0): array
    {
        $request_functions_array = [];
        $data = [];

        $request_functions = $this->request_function->getAllOpenRequestFunctions();
        $request_functions->each(function ($request_function) use (&$data, $listener_id) {
            array_push($data, [
                'id' => $request_function->id,
                'name' => $request_function->name,
                'point' => $request_function->point,
                'is_voted' => $this->request_function->isSubmittedListener($request_function->id, $listener_id)
            ]);
        });

        $request_functions_array = [
            'data' => $data,
            'last_page' => $request_functions->lastPage()
        ];

        return $request_functions_array;
    }

    /**
     * リクエスト機能個別の取得
     *
     * @param int $request_function_id リクエスト機能ID
     * @return RequestFunction|null リクエスト機能データ
     */
    public function getSingleRequestFunction(int $request_function_id): RequestFunction|null
    {
        return $this->request_function->getSingleRequestFunction($request_function_id);
    }

    /**
     * リクエスト機能作成
     * 
     * @param RequestFunctionRequest $request リクエスト機能登録用のリクエストデータ
     * @return RequestFunction 作成されたリクエスト機能情報
     */
    public function storeRequestFunction(RequestFunctionRequest $request): RequestFunction
    {
        return $this->request_function->storeRequestFunction($request);
    }

    /**
     * リクエスト機能更新
     *
     * @param RequestFunctionRequest $request リクエスト機能更新リクエストデータ
     * @param int $request_function_id リクエスト機能ID
     * @return bool 更新できたかどうか
     */
    public function updateRequestFunction(RequestFunctionRequest $request, int $request_function_id): bool
    {
        return $this->request_function->updateRequestFunction($request, $request_function_id);
    }

    /**
     * リクエスト機能リスナー投票
     * 
     * @param int $id 機能リクエストID
     * @param RequestFunctionListenerSubmitRequest $request リクエスト機能リスナー投票用のリクエストデータ
     * @param int $listener_id リスナーID
     * @return void
     */
    public function submitListenerPoint(int $id, RequestFunctionListenerSubmitRequest $request, int $listener_id)
    {
        $this->request_function->submitListenerPoint($id, $request, $listener_id);
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
        return $this->request_function->isSubmittedListener($request_function_id, $listener_id);
    }

    /**
     * リクエスト機能削除
     *
     * @param int $request_function_id  リクエスト機能ID
     * @return bool|null 削除できたかどうか
     */
    public function deleteRequestFunction(int $request_function_id): bool|null
    {
        return $this->request_function->deleteRequestFunction($request_function_id);
    }
}
