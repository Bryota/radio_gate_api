<?php

/**
 * リスナー用の機能関連のビジネスロジック
 *
 * リスナーのアクションに関連する
 *
 * @author s_ryota sryotapersian@gmail.com
 * @version 1.0
 * @copyright 2022 Ryota Segawa
 */

namespace App\Services\Listener;

use App\DataProviders\Models\Listener;
use App\DataProviders\Repositories\ListenerRepository;
use App\Http\Requests\ListenerRequest;
use App\Http\Requests\ListenerMessageRequest;

/**
 * リスナー用のサービスクラス
 *
 * @package App\Services
 * @version 1.0
 */
class ListenerService
{
    /**
     * @var ListenerRepository $listener ListenerRepositoryインスタンス
     */
    private $listener;

    /**
     * コンストラクタ
     *
     * @param ListenerRepository $listener ListenerRepositoryインスタンス
     */
    public function __construct(ListenerRepository $listener)
    {
        $this->listener = $listener;
    }

    /**
     * リスナー登録
     * 
     * @param ListenerRequest $request リスナー登録用のリクエストデータ
     * @return Listener 登録されたリスナー情報
     */
    public function CreateListener(ListenerRequest $request): Listener
    {
        $listener = $this->listener->CreateListener($request);
        return $listener;
    }

    /**
     * リスナー情報取得
     *
     * @param int $listener_id リスナーID
     * @return Listener リスナーデータ
     */
    public function getSingleListener(int $listener_id): Listener
    {
        $listener = $this->listener->getSingleListener($listener_id);
        return $listener;
    }

    /**
     * 投稿メッセージをDBに保存
     * 
     * @param ListenerMessageRequest $request メッセージ投稿用のリクエストデータ
     * @param int $listener_id リスナーID
     * @return void
     */
    public function storeListenerMyProgram(ListenerMessageRequest $request, int $listener_id)
    {
        $this->listener->storeListenerMyProgram($request, $listener_id);
    }

    /**
     * 投稿メッセージを投稿
     * 
     * @param ListenerMessageRequest $request メッセージ投稿用のリクエストデータ
     * @param int $listener_id リスナーID
     * @return void
     */
    public function sendEmailToRadioProgram(ListenerMessageRequest $request, int $listener_id)
    {
    }
}
