<?php

/**
 * マイ番組用の機能関連のビジネスロジック
 *
 * マイ番組のアクションに関連する
 *
 * @author s_ryota sryotapersian@gmail.com
 * @version 1.0
 * @copyright 2022 Ryota Segawa
 */

namespace App\Services\Listener;

use App\DataProviders\Models\ListenerMyProgram;
use App\DataProviders\Repositories\ListenerMyProgramRepository;
use App\Http\Requests\ListenerMyProgramRequest;

/**
 * マイ番組用のサービスクラス
 *
 * @package App\Services
 * @version 1.0
 */
class ListenerMyProgramService
{
    /**
     * @var ListenerMyProgramRepository $listener_my_program ListenerMyProgramRepositoryインスタンス
     */
    private $listener_my_program;

    /**
     * コンストラクタ
     *
     * @param ListenerMyProgramRepository $listener_my_program ListenerMyProgramRepositoryインスタンス
     */
    public function __construct(ListenerMyProgramRepository $listener_my_program)
    {
        $this->listener_my_program = $listener_my_program;
    }

    /**
     * リスナーに紐づいたマイ番組一覧の取得
     * 
     * @param int $listener_id リスナーID
     * @return object マイ番組一覧
     */
    public function getAllListenerMyPrograms(int $listener_id): object
    {
        $listener_my_programs = $this->listener_my_program->getAllListenerMyPrograms($listener_id);
        return $listener_my_programs;
    }

    /**
     * リスナーに紐づいたマイ番組個別の取得
     *
     * @param int $listener_id リスナーID
     * @param int $listener_my_program_id マイ番組ID
     * @return ListenerMyProgram マイ番組データ
     */
    public function getSingleListenerMyProgram(int $listener_id, int $listener_my_program_id): ListenerMyProgram
    {
        $listener_my_program = $this->listener_my_program->getSingleListenerMyProgram($listener_id, $listener_my_program_id);
        return $listener_my_program;
    }

    /**
     * マイ番組作成
     * 
     * @param int $listener_id リスナーID
     * @param ListenerMyProgramRequest $request マイ番組登録用のリクエストデータ
     * @return ListenerMyProgram 作成されたマイ番組情報
     */
    public function storeListenerMyProgram(ListenerMyProgramRequest $request, int $listener_id): ListenerMyProgram
    {
        $listener_my_program = $this->listener_my_program->storeListenerMyProgram($request, $listener_id);
        return $listener_my_program;
    }

    /**
     * マイ番組更新
     *
     * @param ListenerMyProgramRequest $request マイ番組更新リクエストデータ
     * @param int $listener_id リスナーID
     * @param int $listener_my_program_id マイ番組ID
     * @return bool 更新できたかどうか
     */
    public function updateListenerMyProgram(ListenerMyProgramRequest $request, int $listener_id, int $listener_my_program_id): bool
    {
        $listener_my_program = $this->listener_my_program->updateListenerMyProgram($request, $listener_id, $listener_my_program_id);
        return $listener_my_program;
    }
}
