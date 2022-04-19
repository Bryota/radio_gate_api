<?php

/**
 * マイ番組用のデータリポジトリ
 *
 * DBからマイ番組の情報取得・更新、削除の責務を担う
 *
 * @author s_ryota sryotapersian@gmail.com
 * @version 1.0
 * @copyright 2022 Ryota Segawa
 */

namespace App\DataProviders\Repositories;

use App\DataProviders\Models\ListenerMyProgram;
use App\Http\Requests\ListenerMyProgramRequest;

/**
 * マイ番組リポジトリクラス
 *
 * @package App\DataProviders\Repositories
 * @version 1.0
 */
class ListenerMyProgramRepository
{
    /**
     * @var ListenerMyProgram $listener_my_program ListenerMyProgramインスタンス
     */
    private $listener_my_program;

    /**
     * コンストラクタ
     *
     * @param ListenerMyProgram $listener_my_program ListenerMyProgramModel
     * @return void
     */
    public function __construct(ListenerMyProgram $listener_my_program)
    {
        $this->listener_my_program = $listener_my_program;
    }

    /**
     * マイ番組作成
     *
     * @param ListenerMyProgramRequest $request マイ番組作成リクエストデータ
     * @return ListenerMyProgram マイ番組生成データ
     */
    public function storeListenerMyProgram(ListenerMyProgramRequest $request, int $listener_id): ListenerMyProgram
    {
        return $this->listener_my_program::create([
            'program_name' => $request->program_name,
            'email' => $request->email,
            'listener_id' => $listener_id
        ]);
    }
}
