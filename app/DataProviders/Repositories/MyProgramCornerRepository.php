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

use App\DataProviders\Models\MyProgramCorner;
use App\Http\Requests\MyProgramCornerRequest;

/**
 * マイ番組リポジトリクラス
 *
 * @package App\DataProviders\Repositories
 * @version 1.0
 */
class MyProgramCornerRepository
{
    /**
     * @var MyProgramCorner $my_program_corner MyProgramCornerインスタンス
     */
    private $my_program_corner;

    /**
     * コンストラクタ
     *
     * @param MyProgramCorner $my_program_corner MyProgramCornerModel
     * @return void
     */
    public function __construct(MyProgramCorner $my_program_corner)
    {
        $this->my_program_corner = $my_program_corner;
    }

    /**
     * マイ番組に紐づいたコーナー一覧取得
     * 
     * @param int $listener_my_program_id マイ番組ID
     * @return object コーナー一覧
     */
    public function getAllMyProgramCorners(int $listener_my_program_id): object
    {
        return $this->my_program_corner::where('listener_my_program_id', $listener_my_program_id)->get();
    }

    /**
     * マイ番組作成
     *
     * @param MyProgramCornerRequest $request マイ番組作成リクエストデータ
     * @return MyProgramCorner マイ番組生成データ
     */
    public function storeMyProgramCorner(MyProgramCornerRequest $request): MyProgramCorner
    {
        return $this->my_program_corner::create([
            'name' => $request->name,
            'listener_my_program_id' => $request->listener_my_program_id
        ]);
    }
}
