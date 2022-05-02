<?php

/**
 * 番組コーナー用の機能関連のビジネスロジック
 *
 * 番組コーナーの情報取得、更新、削除のビジネスロジック
 *
 * @author s_ryota sryotapersian@gmail.com
 * @version 1.0
 * @copyright 2022 Ryota Segawa
 */

namespace App\Services\Radio;

use App\DataProviders\Models\ProgramCorner;
use App\DataProviders\Repositories\ProgramCornerRepository;
use App\Http\Requests\ProgramCornerRequest;

/**
 * 番組コーナー用のサービスクラス
 *
 * @package App\Services
 * @version 1.0
 */
class ProgramCornerService
{
    /**
     * @var ProgramCornerRepository $program_corner ProgramCornerRepositoryインスタンス
     */
    private $program_corner;

    /**
     * コンストラクタ
     *
     * @param ProgramCornerRepository $program_corner ProgramCornerRepositoryインスタンス
     */
    public function __construct(ProgramCornerRepository $program_corner)
    {
        $this->program_corner = $program_corner;
    }

    /**
     * ラジオ番組に紐づいた番組コーナー一覧取得
     * 
     * @param int $radio_program_id ラジオ番組ID
     * @return object 番組コーナー一覧
     */
    public function getAllProgramCorners(int $radio_program_id): object
    {
        return $this->program_corner->getAllProgramCorners($radio_program_id);
    }

    /**
     * 個別の番組コーナー取得
     *
     * @param int $program_corner_id 番組コーナーID
     * @return ProgramCorner 番組コーナーデータ
     */
    public function getSingleProgramCorner(int $program_corner_id): ProgramCorner
    {
        return $this->program_corner->getSingleProgramCorner($program_corner_id);
    }

    /**
     * 番組コーナー作成
     *
     * @param ProgramCornerRequest $request 番組コーナー作成リクエストデータ
     * @return ProgramCorner 番組コーナー生成データ
     */
    public function storeProgramCorner(ProgramCornerRequest $request): ProgramCorner
    {
        return $this->program_corner->storeProgramCorner($request);
    }

    /**
     * 番組コーナー更新
     *
     * @param ProgramCornerRequest $request 番組コーナー更新リクエストデータ
     * @param int $program_corner_id 番組コーナーID
     * @return bool 更新できたかどうか
     */
    public function updateProgramCorner(ProgramCornerRequest $request, int $program_corner_id): bool
    {
        return $this->program_corner->updateProgramCorner($request, $program_corner_id);
    }

    /**
     * 番組コーナー削除
     *
     * @param int $program_corner_id 番組コーナーID
     * @return bool 削除できたかどうか
     */
    public function deleteProgramCorner(int $program_corner_id): bool
    {
        return $this->program_corner->deleteProgramCorner($program_corner_id);
    }
}
