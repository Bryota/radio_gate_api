<?php

/**
 * マイ番組コーナー用の機能関連のビジネスロジック
 *
 * マイ番組コーナーのアクションに関連する
 *
 * @author s_ryota sryotapersian@gmail.com
 * @version 1.0
 * @copyright 2022 Ryota Segawa
 */

namespace App\Services\Listener;

use App\DataProviders\Models\MyProgramCorner;
use App\DataProviders\Repositories\ListenerMyProgramRepository;
use App\DataProviders\Repositories\MyProgramCornerRepository;
use App\Http\Requests\MyProgramCornerRequest;

/**
 * マイ番組コーナー用のサービスクラス
 *
 * @package App\Services
 * @version 1.0
 */
class MyProgramCornerService
{
    /**
     * @var ListenerMyProgramRepository $listener_my_program ListenerMyProgramRepositoryインスタンス
     */
    private $listener_my_program;

    /**
     * @var MyProgramCornerRepository $my_program_corner MyProgramCornerRepositoryインスタンス
     */
    private $my_program_corner;

    /**
     * コンストラクタ
     *
     * @param ListenerMyProgramRepository $listener_my_program ListenerMyProgramRepositoryインスタンス
     * @param MyProgramCornerRepository $my_program_corner MyProgramCornerRepositoryインスタンス
     */
    public function __construct(ListenerMyProgramRepository $listener_my_program, MyProgramCornerRepository $my_program_corner)
    {
        $this->listener_my_program = $listener_my_program;
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
        return $this->my_program_corner->getAllMyProgramCorners($listener_my_program_id);
    }

    /**
     * 個別のマイ番組コーナー取得
     *
     * @param int $my_program_corner_id マイ番組コーナーID
     * @return MyProgramCorner マイ番組コーナーデータ
     */
    public function getSingleMyProgramCorner(int $my_program_corner_id): MyProgramCorner
    {
        return $this->my_program_corner->getSingleMyProgramCorner($my_program_corner_id);
    }


    /**
     * マイ番組コーナー作成
     * 
     * @param MyProgramCornerRequest $request マイ番組コーナー登録用のリクエストデータ
     * @return MyProgramCorner 作成されたマイ番組コーナー情報
     */
    public function storeMyProgramCorner(MyProgramCornerRequest $request): MyProgramCorner
    {
        return $this->my_program_corner->storeMyProgramCorner($request);
    }

    /**
     * マイ番組コーナー更新
     *
     * @param MyProgramCornerRequest $request マイ番組コーナー更新リクエストデータ
     * @param int $my_program_corner_id マイ番組コーナーID
     * @return bool 更新できたかどうか
     */
    public function updateMyProgramCorner(MyProgramCornerRequest $request, int $my_program_corner_id): bool
    {
        return $this->my_program_corner->updateMyProgramCorner($request, $my_program_corner_id);
    }

    /**
     * マイ番組コーナー削除
     *
     * @param int $my_program_corner_id マイ番組コーナーID
     * @param int $listener_my_program_id マイ番組ID
     * @return bool 削除できたかどうか
     */
    public function deleteMyProgramCorner(int $my_program_corner_id, int $listener_my_program_id): bool
    {
        return $this->my_program_corner->deleteMyProgramCorner($my_program_corner_id, $listener_my_program_id);
    }

    /** 
     * マイ番組がログインしているリスナーが作成したかどうか
     * 
     * @param int $listener_id リスナーID
     * @param int $listener_my_program_id  マイ番組ID
     * @return bool 削除できたかどうか
     * 
     */
    public function checkLoginListener(int $listener_id, int $listener_my_program_id): bool
    {
        $my_program_listener_id = $this->listener_my_program->getListenerId($listener_my_program_id);
        return $listener_id == $my_program_listener_id;
    }
}
