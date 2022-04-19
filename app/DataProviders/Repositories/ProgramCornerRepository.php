<?php

/**
 * 番組コーナー用のデータリポジトリ
 *
 * DBから番組コーナーの情報取得・更新、削除の責務を担う
 *
 * @author s_ryota sryotapersian@gmail.com
 * @version 1.0
 * @copyright 2022 Ryota Segawa
 */

namespace App\DataProviders\Repositories;

use App\DataProviders\Models\ProgramCorner;
use App\Http\Requests\ProgramCornerRequest;

/**
 * 番組コーナーリポジトリクラス
 *
 * @package App\DataProviders\Repositories
 * @version 1.0
 */
class ProgramCornerRepository
{
    /**
     * @var ProgramCorner $program_corner ProgramCornerインスタンス
     */
    private $program_corner;

    /**
     * コンストラクタ
     *
     * @param ProgramCorner $program_corner ProgramCornerModel
     * @return void
     */
    public function __construct(ProgramCorner $program_corner)
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
        return $this->program_corner::where('radio_program_id', $radio_program_id)->get();
    }

    /**
     * 番組コーナー作成
     *
     * @param ProgramCornerRequest $request 番組コーナー作成リクエストデータ
     * @return ProgramCorner 番組コーナー生成データ
     */
    public function storeProgramCorner(ProgramCornerRequest $request): ProgramCorner
    {
        return $this->program_corner::create($request->all());
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
        $program_corner = $this->program_corner::find($program_corner_id);
        $program_corner->name = $request->name;
        return $program_corner->save();
    }

    /**
     * 番組コーナー削除
     *
     * @param int $program_corner_id 番組コーナーID
     * @return bool 削除できたかどうか
     */
    public function deleteProgramCorner(int $program_corner_id): bool
    {
        $program_corner = $this->program_corner::find($program_corner_id);
        return $program_corner->delete();
    }
}
