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

use Illuminate\Support\Facades\Mail;
use App\DataProviders\Models\Listener;
use App\DataProviders\Models\ListenerMessage;
use App\DataProviders\Repositories\RadioProgramRepository;
use App\DataProviders\Repositories\ProgramCornerRepository;
use App\DataProviders\Repositories\ListenerMyProgramRepository;
use App\DataProviders\Repositories\MyProgramCornerRepository;
use App\DataProviders\Repositories\ListenerRepository;
use App\Http\Requests\ListenerRequest;
use App\Http\Requests\ListenerMessageRequest;
use App\Mail\ListenerMessageMail;

/**
 * リスナー用のサービスクラス
 *
 * @package App\Services
 * @version 1.0
 */
class ListenerService
{
    /**
     * @var RadioProgramRepository $radio_program RadioProgramRepositoryインスタンス
     */
    private $radio_program;

    /**
     * @var ProgramCornerRepository $program_corner ProgramCornerRepositoryインスタンス
     */
    private $program_corner;

    /**
     * @var ListenerMyProgramRepository $listener_my_program ListenerMyProgramRepositoryインスタンス
     */
    private $listener_my_program;

    /**
     * @var MyProgramCornerRepository $my_program_corner MyProgramCornerRepositoryインスタンス
     */
    private $my_program_corner;

    /**
     * @var ListenerRepository $listener ListenerRepositoryインスタンス
     */
    private $listener;

    /**
     * コンストラクタ
     *
     * @param RadioProgramRepository $radio_program RadioProgramRepositoryインスタンス
     * @param ProgramCornerRepository $program_corner ProgramCornerRepositoryインスタンス
     * @param ListenerMyProgramRepository $listener_my_program ListenerMyProgramRepositoryインスタンス
     * @param MyProgramCornerRepository $my_program_corner MyProgramCornerRepositoryインスタンス
     * @param ListenerRepository $listener ListenerRepositoryインスタンス
     */
    public function __construct(
        RadioProgramRepository $radio_program,
        ProgramCornerRepository $program_corner,
        ListenerMyProgramRepository $listener_my_program,
        MyProgramCornerRepository $my_program_corner,
        ListenerRepository $listener,
    ) {
        $this->radio_program = $radio_program;
        $this->program_corner = $program_corner;
        $this->listener_my_program = $listener_my_program;
        $this->my_program_corner = $my_program_corner;
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
     * リスナー更新
     * 
     * @param ListenerRequest $request リスナー更新用のリクエストデータ
     * @param int $listener_id リスナーID
     * @return void
     */
    public function UpdateListener(ListenerRequest $request, $listener_id): void
    {
        $request->offsetUnset('password');
        $this->listener->UpdateListener($request, $listener_id);
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
        // TODO: 別メソッドに分けてもいいかも
        if ($request->radio_program_id) {
            $radio_email = $this->radio_program->getSingleRadioProgram($request->radio_program_id)->email;
        } else {
            $radio_email = $this->listener_my_program->getSingleListenerMyProgram($listener_id, $request->listener_my_program_id)->email;
        }
        if ($request->program_corner_id) {
            $corner = $this->program_corner->getSingleProgramCorner($request->program_corner_id)->name;
        } else if ($request->my_program_corner_id) {
            $corner = $this->my_program_corner->getSingleMyProgramCorner($request->my_program_corner_id)->name;
        } else {
            $corner = $request->subject;
        }

        $listener = $this->listener->getSingleListener($listener_id);
        $full_name = $listener->last_name ? "%{$request->last_name}　%{$request->first_name}" : null;
        $full_name_kana = $listener->last_name_kana ? "%{$request->last_name_kana}　%{$request->first_name_kana}" : null;
        $post_code = $listener->post_code ? $listener->post_code : null;
        $prefecture = $listener->prefecture ? $listener->prefecture : null;
        $city = $listener->city ? $listener->city : null;
        $house_number = $listener->house_number ? $listener->house_number : null;
        $tel = $listener->tel ? $listener->tel : null;
        $email = $listener->email;
        $content = $request->content;
        if ($request->radio_name) {
            $radio_name = $request->radio_name;
        } else if ($listener->radio_name) {
            $radio_name = $listener->radio_name;
        } else {
            $radio_name = null;
        }

        // TODO: ListenerMessageMailはコンストラクタでDIしたい
        Mail::to($radio_email)->send(new ListenerMessageMail(
            $corner,
            $full_name,
            $full_name_kana,
            $radio_name,
            $post_code,
            $prefecture,
            $city,
            $house_number,
            $tel,
            $email,
            $content
        ));
    }

    /**
     * リスナーに紐づいた投稿一覧の取得
     * 
     * @param int $listener_id リスナーID
     * @return object 投稿一覧
     */
    public function getAllListenerMessages(int $listener_id): object
    {
        $listener_messages = $this->listener->getAllListenerMessages($listener_id);
        return $listener_messages;
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
        $listener_message = $this->listener->getSingleListenerMessage($listener_id, $listener_message_id);
        return $listener_message;
    }

    /**
     * リスナーに紐づいた一保存してある投稿一覧の取得
     * 
     * @param int $listener_id リスナーID
     * @return object 投稿一覧
     */
    public function getAllListenerSavedMessages(int $listener_id): object
    {
        $listener_messages = $this->listener->getAllListenerSavedMessages($listener_id);
        return $listener_messages;
    }

    /**
     * 投稿メッセージをDBに一時保存
     * 
     * @param ListenerMessageRequest $request メッセージ保存用のリクエストデータ
     * @param int $listener_id リスナーID
     * @return void
     */
    public function saveListenerMyProgram(ListenerMessageRequest $request, int $listener_id)
    {
        $this->listener->saveListenerMyProgram($request, $listener_id);
    }
}
