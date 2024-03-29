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
use Illuminate\Http\Request;

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
     * @var ListenerMessageMail $listener_message_mail ListenerMessageMailインスタンス
     */
    private $listener_message_mail;

    /**
     * コンストラクタ
     *
     * @param RadioProgramRepository $radio_program RadioProgramRepositoryインスタンス
     * @param ProgramCornerRepository $program_corner ProgramCornerRepositoryインスタンス
     * @param ListenerMyProgramRepository $listener_my_program ListenerMyProgramRepositoryインスタンス
     * @param MyProgramCornerRepository $my_program_corner MyProgramCornerRepositoryインスタンス
     * @param ListenerRepository $listener ListenerRepositoryインスタンス
     * @param ListenerMessageMail $listener_message_mail ListenerMessageMailインスタンス
     */
    public function __construct(
        RadioProgramRepository $radio_program,
        ProgramCornerRepository $program_corner,
        ListenerMyProgramRepository $listener_my_program,
        MyProgramCornerRepository $my_program_corner,
        ListenerRepository $listener,
        ListenerMessageMail $listener_message_mail,
    ) {
        $this->radio_program = $radio_program;
        $this->program_corner = $program_corner;
        $this->listener_my_program = $listener_my_program;
        $this->my_program_corner = $my_program_corner;
        $this->listener = $listener;
        $this->listener_message_mail = $listener_message_mail;
    }

    /**
     * リスナー登録
     * 
     * @param ListenerRequest $request リスナー登録用のリクエストデータ
     * @return Listener 登録されたリスナー情報
     */
    public function CreateListener(ListenerRequest $request): Listener
    {
        return $this->listener->CreateListener($request);
    }

    /**
     * リスナー更新
     * 
     * @param \Illuminate\Http\Request $request リスナー更新用のリクエストデータ
     * @param int $listener_id リスナーID
     * @return void
     */
    public function UpdateListener(Request $request, $listener_id): void
    {
        $request->offsetUnset('email');
        $request->offsetUnset('password');

        $this->listener->UpdateListener($request, $listener_id);
    }

    /**
     * リスナー一覧取得
     *
     * @return object リスナー一覧 データ
     */
    public function getAllListeners(): object
    {
        return $this->listener->getAllListeners();
    }

    /**
     * リスナー情報取得
     *
     * @param int $listener_id リスナーID
     * @return array リスナーデータ
     */
    public function getSingleListener(int $listener_id): array
    {
        $listener = $this->listener->getSingleListener($listener_id);
        return [
            'id' => $listener->id,
            'email' => $listener->email,
            'full_name' => $listener->full_name,
            'first_name' => $listener->first_name,
            'last_name' => $listener->last_name,
            'full_name_kana' => $listener->full_name_kana,
            'first_name_kana' => $listener->first_name_kana,
            'last_name_kana' => $listener->last_name_kana,
            'radio_name' => $listener->radio_name,
            'post_code' => $listener->post_code,
            'address' => $listener->full_address,
            'prefecture' => $listener->prefecture,
            'city' => $listener->city,
            'house_number' => $listener->house_number,
            'building' => $listener->building,
            'room_number' => $listener->room_number,
            'tel' => $listener->tel,
        ];
    }

    /**
     * 投稿メッセージをDBに保存
     * 
     * @param ListenerMessageRequest $request メッセージ投稿用のリクエストデータ
     * @param int $listener_id リスナーID
     * @return void
     */
    public function storeListenerMessage(ListenerMessageRequest $request, int $listener_id)
    {
        $this->listener->storeListenerMessage($request, $listener_id);
        $this->listener->createOrUpdatePostCounts($request, $listener_id);
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
        $radio_email = $this->setRadioProgramEmail($request->integer('radio_program_id'), $request->integer('listener_my_program_id'), $listener_id);
        $corner = $this->setCorner($request->integer('program_corner_id'), $request->integer('my_program_corner_id'), $request->string('subject'));
        $listener = $this->listener->getSingleListener($listener_id);

        $full_name = $listener->last_name ? "{$listener->last_name}　{$listener->first_name}" : null;
        $full_name_kana = $listener->last_name_kana ? "{$listener->last_name_kana}　{$listener->first_name_kana}" : null;
        $post_code = $listener->post_code ? $listener->post_code : null;
        $prefecture = $listener->prefecture ? $listener->prefecture : null;
        $city = $listener->city ? $listener->city : null;
        $house_number = $listener->house_number ? $listener->house_number : null;
        $building = $listener->building ? $listener->building : null;
        $room_number = $listener->room_number ? $listener->room_number : null;
        $tel = $listener->tel ? $listener->tel : null;
        $email = $listener->email;
        $content = $request->string('content');
        $listener_info_flag = $request->bool('listener_info_flag');
        $tel_flag = $request->bool('tel_flag');
        if ($request->radio_name) {
            $radio_name = $request->string('radio_name');
        } else if ($listener->radio_name) {
            $radio_name = strval($listener->radio_name);
        } else {
            $radio_name = null;
        }

        // TODO: Mailファザードはどこかで怒られるかも
        Mail::to($radio_email)->send($this->listener_message_mail->getSelf(
            $corner,
            $full_name,
            $full_name_kana,
            $radio_name,
            $post_code,
            $prefecture,
            $city,
            $house_number,
            $building,
            $room_number,
            $tel,
            $email,
            $content,
            $listener_info_flag,
            $tel_flag
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
        return $this->listener->getAllListenerMessages($listener_id);
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
        return $this->listener->getSingleListenerMessage($listener_id, $listener_message_id);
    }

    /**
     * リスナーに紐づいた一保存してある投稿一覧の取得
     * 
     * @param int $listener_id リスナーID
     * @return object 投稿一覧
     */
    public function getAllListenerSavedMessages(int $listener_id): object
    {
        return $this->listener->getAllListenerSavedMessages($listener_id);
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

    /**
     * 番組メールアドレスを取得
     * 
     * @param int|null $radio_program_id ラジオ番組ID
     * @param int|null $listener_my_program_id マイラジオ番組ID
     * @param int $listener_id リスナーID
     * @return string|null ラジオ番組メールアドレス
     */
    private function setRadioProgramEmail(int|null $radio_program_id, int|null $listener_my_program_id, int $listener_id): string|null
    {
        if ($radio_program_id) {
            $radio_email = $this->radio_program->getSingleRadioProgram($radio_program_id)->email;
        } else if ($listener_my_program_id) {
            $listener_my_program = $this->listener_my_program->getSingleListenerMyProgram($listener_id, $listener_my_program_id);
            if ($listener_my_program) {
                $radio_email = $listener_my_program->email;
            } else {
                $radio_email = null;
            }
        } else {
            $radio_email = null;
        }
        return $radio_email;
    }

    /**
     * 番組コーナーを取得
     * 
     * @param int|null $program_corner_id 番組コーナーID
     * @param int|null $my_program_corner_id マイ番組コーナーID
     * @param string|null $subject 件名
     * @return string|null 番組コーナー
     */
    private function setCorner(int|null $program_corner_id, int|null $my_program_corner_id, string|null $subject): string|null
    {
        if ($program_corner_id) {
            $corner = $this->program_corner->getSingleProgramCorner($program_corner_id)->name;
        } else if ($my_program_corner_id) {
            $corner = $this->my_program_corner->getSingleMyProgramCorner($my_program_corner_id)->name;
        } else {
            $corner = $subject;
        }
        return $corner;
    }

    /**
     * リスナー削除削除
     *
     * @param int $listener_id リスナーID
     * @return bool|null 削除できたかどうか
     */
    public function deleteListener(int $listener_id)
    {
        return $this->listener->deleteListener($listener_id);
    }

    /**
     * 最近投稿した番組（最新3件）
     *
     * @param int $listener_id リスナーID
     * @return array 最近投稿した番組
     */
    public function fetchRecentPostRadioPrograms(int $listener_id)
    {
        $recent_post_radio_promgrams = $this->listener->fetchRecentPostRadioPrograms($listener_id);

        $recent_post_radio_promgrams_array = [];
        foreach ($recent_post_radio_promgrams as $recent_post_radio_promgram) {
            array_push($recent_post_radio_promgrams_array, [
                'radio_program_id' => $recent_post_radio_promgram->radio_program_id ? $recent_post_radio_promgram->radio_program_id : $recent_post_radio_promgram->listener_my_program_id,
                'radio_program_name' => $recent_post_radio_promgram->radio_program_id ? $recent_post_radio_promgram->RadioProgram->name : $recent_post_radio_promgram->ListenerMyProgram->name,
            ]);
        }
        return $recent_post_radio_promgrams_array;
    }

    /**
     * 投稿の多い番組（最新3件）
     *
     * @param int $listener_id リスナーID
     * @return array 投稿の多い番組
     */
    public function fetchMostPostRadioPrograms(int $listener_id)
    {
        $most_post_radio_promgrams = $this->listener->fetchMostPostRadioPrograms($listener_id);

        $most_post_radio_promgrams_array = [];
        foreach ($most_post_radio_promgrams as $most_post_radio_promgram) {
            array_push($most_post_radio_promgrams_array, [
                'radio_program_id' => $most_post_radio_promgram->radio_program_id ? $most_post_radio_promgram->radio_program_id : $most_post_radio_promgram->listener_my_program_id,
                'radio_program_name' => $most_post_radio_promgram->radio_program_id ? $most_post_radio_promgram->RadioProgram->name : $most_post_radio_promgram->ListenerMyProgram->name,
            ]);
        }
        return $most_post_radio_promgrams_array;
    }

    /**
     * 同じメールアドレスが保存されていないかどうか
     *
     * @param string $email メールアドレス
     * @return bool|null 同じメールアドレスが保存されていないかどうか
     */
    public function isUniqueEmail(string $email)
    {
        return $this->listener->isUniqueEmail($email);
    }
}
