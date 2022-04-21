<?php

/**
 * リスナー用のデータリポジトリ
 *
 * DBからリスナーの情報取得・更新、削除の責務を担う
 *
 * @author s_ryota sryotapersian@gmail.com
 * @version 1.0
 * @copyright 2022 Ryota Segawa
 */

namespace App\DataProviders\Repositories;

use App\DataProviders\Models\Listener;
use App\Http\Requests\ListenerRequest;
use Illuminate\Support\Facades\Hash;

/**
 * リスナーリポジトリクラス
 *
 * @package App\DataProviders\Repositories
 * @version 1.0
 */
class ListenerRepository
{
    /**
     * @var Listener $listener Listenerインスタンス
     */
    private $listener;

    /**
     * コンストラクタ
     *
     * @param Listener $listener ListenerModel
     * @return void
     */
    public function __construct(Listener $listener)
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
        $listener = $this->listener->create([
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'last_name_kana' => $request->last_name_kana,
            'first_name_kana' => $request->first_name_kana,
            'radio_name' => $request->radio_name,
            'post_code' => $request->post_code,
            'prefecture' => $request->prefecture,
            'city' => $request->city,
            'house_number' => $request->house_number,
            'tel' => $request->tel,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
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
        return $this->listener::where('id', $listener_id)
            ->first();
    }
}
