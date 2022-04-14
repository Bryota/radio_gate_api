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
        $program_corners = $this->program_corner->getAllProgramCorners($radio_program_id);
        return $program_corners;
    }

    /**
     * 番組コーナー作成
     *
     * @param ProgramCornerRequest $request 番組コーナー作成リクエストデータ
     * @return ProgramCorner 番組コーナー生成データ
     */
    public function storeProgramCorner(ProgramCornerRequest $request): ProgramCorner
    {
        $program_corner = $this->program_corner->storeProgramCorner($request);
        return $program_corner;
    }
}
