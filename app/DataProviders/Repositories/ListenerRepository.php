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
use App\DataProviders\Models\PostMessageCount;
use App\Http\Requests\ListenerRequest;
use App\Http\Requests\ListenerMessageRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
     * @var PostMessageCount $post_message_count PostMessageCountインスタンス
     */
    private $post_message_count;

    /**
     * コンストラクタ
     *
     * @param Listener $listener ListenerModel
     * @param ListenerMessage $listener_message ListenerMessageModel
     * @param PostMessageCount $post_message_count PostMessageCountModel
     * @return void
     */
    public function __construct(Listener $listener, ListenerMessage $listener_message, PostMessageCount $post_message_count)
    {
        $this->listener = $listener;
        $this->listener_message = $listener_message;
        $this->post_message_count = $post_message_count;
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
            'building' => $request->building,
            'room_number' => $request->room_number,
            'tel' => $request->tel,
            'email' => $request->email,
            'password' => Hash::make($request->string('password'))
        ]);
        return $listener;
    }

    /**
     * リスナー更新
     * 
     * @param \Illuminate\Http\Request $request リスナー更新用のリクエストデータ
     * @param int $listener_id リスナーID
     * @return void
     */
    public function UpdateListener(Request $request, int $listener_id): void
    {
        $this->listener::ListenerIdEqual($listener_id)
            ->update($request->all());
    }

    /**
     * リスナー一覧取得
     * 
     * @return LengthAwarePaginator リスナー一覧データ
     */
    public function getAllListeners(): LengthAwarePaginator
    {
        return $this->listener::paginate(8);;
    }

    /**
     * リスナー情報取得
     * 
     * @param int $listener_id リスナーID
     * @return Listener リスナーデータ
     */
    public function getSingleListener(int $listener_id): Listener
    {
        return $this->listener::ListenerIdEqual($listener_id)
            ->firstOrFail();
    }

    /**
     * 投稿メッセージ保存
     * 
     * @param ListenerMessageRequest $request メッセージ投稿用のリクエストデータ
     * @param int $listener_id リスナーID
     * @return void
     */
    public function storeListenerMessage(ListenerMessageRequest $request, int $listener_id)
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
            'listener_info_flag' => $request->listener_info_flag,
            'tel_flag' => $request->tel_flag,
            'posted_at' => Carbon::now()
        ]);
    }

    /**
     * 投稿数保存
     * 
     * @param ListenerMessageRequest $request メッセージ投稿用のリクエストデータ
     * @param int $listener_id リスナーID
     * @return void
     */
    public function createOrUpdatePostCounts(ListenerMessageRequest $request, int $listener_id)
    {
        $post_message_count = $this->post_message_count::where([
            ['radio_program_id', '=', $request->radio_program_id],
            ['listener_my_program_id', '=', $request->listener_my_program_id],
            ['listener_id', '=', $listener_id]
        ])->first();

        if ($post_message_count) {
            $post_message_count->post_counts = $post_message_count->post_counts + 1;
            $post_message_count->save();
        } else {
            $this->post_message_count::create([
                'radio_program_id' => $request->radio_program_id ? $request->radio_program_id : null,
                'listener_my_program_id' => $request->listener_my_program_id ? $request->listener_my_program_id : null,
                'listener_id' => $listener_id,
                'post_counts' => 1
            ]);
        }
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
            'listener_info_flag' => $request->listener_info_flag,
            'tel_flag' => $request->tel_flag
        ]);
    }

    /**
     * リスナーに紐づいた投稿一覧の取得
     * 
     * @param int $listener_id リスナーID
     * @return LengthAwarePaginator 投稿一覧
     */
    public function getAllListenerMessages(int $listener_id): LengthAwarePaginator
    {
        return $this->listener_message::ListenerIdEqual($listener_id)
            ->whereNotNull('posted_at')
            ->orderBy('posted_at', 'desc')
            ->with(['radioProgram', 'programCorner', 'listenerMyProgram', 'myProgramCorner'])
            ->paginate(8, ['id', 'radio_program_id', 'program_corner_id', 'listener_my_program_id', 'my_program_corner_id', 'subject', 'posted_at']);
    }

    /**
     * リスナーに紐づいた投稿個別の取得
     * 
     * @param int $listener_id リスナーID
     * @param int $listener_message_id 投稿ID
     * @return ListenerMessage|null 投稿データ
     */
    public function getSingleListenerMessage(int $listener_id, int $listener_message_id): ListenerMessage|null
    {
        return $this->listener_message::where('id', $listener_message_id)
            ->ListenerIdEqual($listener_id)
            ->with(['radioProgram', 'programCorner', 'listenerMyProgram', 'myProgramCorner'])
            ->firstOrFail(['id', 'radio_program_id', 'program_corner_id', 'listener_my_program_id', 'my_program_corner_id', 'subject', 'content', 'radio_name', 'listener_info_flag', 'tel_flag', 'posted_at']);
    }

    /**
     * リスナーに紐づいた一時保存してある投稿一覧の取得
     * 
     * @param int $listener_id リスナーID
     * @return object 投稿一覧
     */
    public function getAllListenerSavedMessages(int $listener_id): object
    {
        return $this->listener_message::ListenerIdEqual($listener_id)
            ->where('posted_at', null)
            ->orderBy('id', 'desc')
            ->with(['radioProgram', 'programCorner', 'listenerMyProgram', 'myProgramCorner'])
            ->paginate(8, ['id', 'radio_program_id', 'program_corner_id', 'listener_my_program_id', 'my_program_corner_id', 'subject', 'posted_at']);
    }

    /**
     * リスナー削除削除
     *
     * @param int $listener_id リスナーID
     * @return bool|null 削除できたかどうか
     */
    public function deleteListener(int $listener_id)
    {
        $listener = $this->listener::ListenerIdEqual($listener_id)->firstOrFail();
        return $listener->delete();
    }

    /**
     * 最近投稿した番組（最新3件）
     *
     * @param int $listener_id リスナーID
     * @return object 最近投稿した番組
     */
    public function fetchRecentPostRadioPrograms(int $listener_id)
    {
        $recent_post_radio_promgrams = $this->listener_message::ListenerIdEqual($listener_id)
            ->groupBy('radio_program_id')
            ->groupBy('listener_my_program_id')
            ->orderBy('posted_at')
            ->limit(3)
            ->with(['radioProgram', 'listenerMyProgram'])
            ->get();

        return $recent_post_radio_promgrams;
    }

    /**
     * 投稿の多い番組（最新3件）
     *
     * @param int $listener_id リスナーID
     * @return object 投稿の多い番組
     */
    public function fetchMostPostRadioPrograms(int $listener_id)
    {
        $most_post_radio_promgrams = $this->post_message_count::ListenerIdEqual($listener_id)
            ->orderBy('post_counts', 'desc')
            ->limit(3)
            ->with(['radioProgram', 'listenerMyProgram'])
            ->get();

        return $most_post_radio_promgrams;
    }

    /**
     * 同じメールアドレスが保存されていないかどうか
     *
     * @param string $email メールアドレス
     * @return bool|null 同じメールアドレスが保存されていないかどうか
     */
    public function isUniqueEmail(string $email)
    {
        return $this->listener::where('email', $email)->doesntExist();
    }
}
