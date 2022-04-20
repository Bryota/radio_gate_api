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
     * リスナーに紐づいたマイ番組一覧の取得
     * 
     * @param int $listener_id リスナーID
     * @return object マイ番組一覧
     */
    public function getAllListenerMyPrograms(int $listener_id): object
    {
        return $this->listener_my_program::where('listener_id', $listener_id)->get();
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
        return $this->listener_my_program::where('id', $listener_my_program_id)
            ->where('listener_id', $listener_id)
            ->first();
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
        $listener_my_program = $this->listener_my_program::where('id', $listener_my_program_id)
            ->where('listener_id', $listener_id)
            ->first();
        $listener_my_program->program_name = $request->program_name;
        $listener_my_program->email = $request->email;
        return $listener_my_program->save();
    }
}
