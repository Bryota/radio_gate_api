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
use App\DataProviders\Models\ListenerMessage;
use App\Http\Requests\ListenerRequest;
use App\Http\Requests\ListenerMessageRequest;
use Carbon\Carbon;
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
     * @var ListenerMessage $listener_message ListenerMessageインスタンス
     */
    private $listener_message;

    /**
     * コンストラクタ
     *
     * @param Listener $listener ListenerModel
     * @param ListenerMessage $listener_message ListenerMessageModel
     * @return void
     */
    public function __construct(Listener $listener, ListenerMessage $listener_message)
    {
        $this->listener = $listener;
        $this->listener_message = $listener_message;
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

    /**
     * 投稿メッセージ保存
     * 
     * @param ListenerMessageRequest $request メッセージ投稿用のリクエストデータ
     * @param int $listener_id リスナーID
     * @return void
     */
    public function storeListenerMyProgram(ListenerMessageRequest $request, int $listener_id)
    {
        $this->listener_message::create([
            'radio_program_id' => $request->radio_program_id ? $request->radio_program_id : null,
            'program_corner_id' => $request->program_corner_id ? $request->program_corner_id : null,
            'listener_my_program_id' => $request->listener_my_program_id ? $request->listener_my_program_id : null,
            'my_program_corner_id' => $request->my_program_corner_id ? $request->my_program_corner_id : null,
            'listener_id' => $listener_id,
            'subject' => $request->subject ? $request->subject : null,
            'content' => $request->content,
            'radio_name' => $request->radio_name ? $request->radio_name : null,
            'posted_at' => Carbon::now()
        ]);
    }

    /**
     * 投稿メッセージ保存
     * 
     * @param ListenerMessageRequest $request メッセージ保存用のリクエストデータ
     * @param int $listener_id リスナーID
     * @return void
     */
    public function saveListenerMyProgram(ListenerMessageRequest $request, int $listener_id)
    {
        $this->listener_message::create([
            'radio_program_id' => $request->radio_program_id ? $request->radio_program_id : null,
            'program_corner_id' => $request->program_corner_id ? $request->program_corner_id : null,
            'listener_my_program_id' => $request->listener_my_program_id ? $request->listener_my_program_id : null,
            'my_program_corner_id' => $request->my_program_corner_id ? $request->my_program_corner_id : null,
            'listener_id' => $listener_id,
            'subject' => $request->subject ? $request->subject : null,
            'content' => $request->content,
            'radio_name' => $request->radio_name ? $request->radio_name : null,
        ]);
    }

    /**
     * リスナーに紐づいた投稿一覧の取得
     * 
     * @param int $listener_id リスナーID
     * @return object 投稿一覧
     */
    public function getAllListenerMessages(int $listener_id): object
    {
        return $this->listener_message::where('listener_id', $listener_id)->get();
    }

    /**
     * リスナーに紐づいた投稿個別の取得
     * 
     * @param int $listener_id リスナーID
     * @param int $listener_message_id 投稿ID
     * @return ListenerMessage 投稿データ
     */
    public function getSingleListenerMessage(int $listener_id, int $listener_message_id): ListenerMessage
    {
        return $this->listener_message::where('id', $listener_message_id)
            ->where('listener_id', $listener_id)
            ->first();
    }

    /**
     * リスナーに紐づいた一時保存してある投稿一覧の取得
     * 
     * @param int $listener_id リスナーID
     * @return object 投稿一覧
     */
    public function getAllListenerSavedMessages(int $listener_id): object
    {
        return $this->listener_message::where('listener_id', $listener_id)
            ->where('posted_at', null)
            ->get();
    }
}
