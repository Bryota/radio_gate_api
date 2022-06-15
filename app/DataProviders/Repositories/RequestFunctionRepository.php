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
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
     * 機能リクエスト一覧の取得
     *
     * @return LengthAwarePaginator リクエスト機能一覧
     */
    public function getAllRequestFunctions(): LengthAwarePaginator
    {
        return $this->request_function::paginate(8);
    }

    /**
     * 公開状態の機能リクエスト一覧の取得
     *
     * @return LengthAwarePaginator リクエスト機能一覧
     */
    public function getAllOpenRequestFunctions(): LengthAwarePaginator
    {
        return $this->request_function::where('is_open', true)->paginate(8);
    }

    /**
     * リクエスト機能個別の取得
     * 
     * @param int $request_function_id リクエスト機能ID
     * @return RequestFunction|null リクエスト機能データ
     */
    public function getSingleRequestFunction(int $request_function_id): RequestFunction|null
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
        return $this->request_function::create([
            'name' => $request->name,
            'detail' => $request->detail,
            'is_open' => $request->is_open
        ]);
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
        $request_function = $this->request_function::where('id', $request_function_id)
            ->first();
        if ($request_function) {
            $request_function->name = $request->string('name');
            $request_function->detail = $request->string('detail');
            $request_function->is_open = $request->boolean('is_open');
            return $request_function->save();
        } else {
            return false;
        }
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
            'point' => $request->integer('point')
        ]);
        $request_function = $this->getSingleRequestFunction($request->integer('request_function_id'));
        if ($request_function) {
            $request_function->point = intval($request_function->point) + $request->integer('point');
            $request_function->save();
        }
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
            ->ListenerIdEqual($listener_id)
            ->exists();
    }

    /**
     * リクエスト機能削除
     *
     * @param int $request_function_id リクエスト機能ID
     * @return bool|null 削除できたかどうか
     */
    public function deleteRequestFunction(int $request_function_id): bool|null
    {
        $request_function = $this->request_function::where('id', $request_function_id)
            ->first();
        if ($request_function) {
            return $request_function->delete();
        } else {
            return false;
        }
    }
}
