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

    // /**
    //  * @var ProgramCornerRepository $program_corner ProgramCornerRepositoryインスタンス
    //  */
    // private $program_corner;

    /**
     * @var ListenerMyProgramRepository $listener_my_program ListenerMyProgramRepositoryインスタンス
     */
    private $listener_my_program;

    // /**
    //  * @var MyProgramCornerRepository $my_program_corner MyProgramCornerRepositoryインスタンス
    //  */
    // private $my_program_corner;

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
    //  * @param ProgramCornerRepository $program_corner ProgramCornerRepositoryインスタンス
     * @param ListenerMyProgramRepository $listener_my_program ListenerMyProgramRepositoryインスタンス
    //  * @param MyProgramCornerRepository $my_program_corner MyProgramCornerRepositoryインスタンス
     * @param ListenerRepository $listener ListenerRepositoryインスタンス
     * @param ListenerMessageMail $listener_message_mail ListenerMessageMailインスタンス
     */
    public function __construct(
        RadioProgramRepository $radio_program,
        // ProgramCornerRepository $program_corner,
        ListenerMyProgramRepository $listener_my_program,
        // MyProgramCornerRepository $my_program_corner,
        ListenerRepository $listener,
        ListenerMessageMail $listener_message_mail
    ) {
        $this->radio_program = $radio_program;
        // $this->program_corner = $program_corner;
        $this->listener_my_program = $listener_my_program;
        // $this->my_program_corner = $my_program_corner;
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
        $programData = [];
        if ($request->radio_program_id) {
            $programData['email'] = $this->radio_program->getSingleRadioProgram($request->radio_program_id)->email;
        } else {
            $programData['email'] = $this->listener_my_program->getSingleListenerMyProgram($listener_id, $request->listener_my_program_id)->email;
        }
        Mail::to('test@example.com')->send($this->listener_message_mail);
    }
}
