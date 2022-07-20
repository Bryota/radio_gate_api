<?php

/**
 * ラジオ番組用の機能関連のビジネスロジック
 *
 * ラジオ番組の情報取得、更新、削除のビジネスロジック
 *
 * @author s_ryota sryotapersian@gmail.com
 * @version 1.0
 * @copyright 2022 Ryota Segawa
 */

namespace App\Services\Radio;

use App\DataProviders\Repositories\RadioProgramRepository;
use App\DataProviders\Models\RadioProgram;
use App\Http\Requests\RadioProgramRequest;
use Illuminate\Http\Request;

/**
 * ラジオ番組用のサービスクラス
 *
 * @package App\Services
 * @version 1.0
 */
class RadioProgramService
{
    /**
     * @var RadioProgramRepository $radio_program RadioProgramRepositoryインスタンス
     */
    private $radio_program;

    /**
     * コンストラクタ
     *
     * @param RadioProgramRepository $radio_program RadioProgramRepositoryインスタンス
     */
    public function __construct(RadioProgramRepository $radio_program)
    {
        $this->radio_program = $radio_program;
    }

    /**
     * ラジオ局に紐づいたラジオ番組一覧取得
     * 
     * @param Request $request getパラメーター
     * @return object ラジオ番組一覧
     */
    public function getAllRadioPrograms(Request $request): object
    {
        return $this->radio_program->getAllRadioPrograms($request);
    }

    /**
     * 個別のラジオ番組取得
     *
     * @param int $radio_program_id ラジオ番組ID
     * @return RadioProgram ラジオ番組データ
     */
    public function getSingleRadioProgram(int $radio_program_id): RadioProgram
    {
        return $this->radio_program->getSingleRadioProgram($radio_program_id);
    }

    /**
     * ラジオ番組作成
     *
     * @param RadioProgramRequest $request ラジオ番組作成リクエストデータ
     * @return RadioProgram ラジオ番組生成データ
     */
    public function storeRadioProgram(RadioProgramRequest $request): RadioProgram
    {
        return $this->radio_program->storeRadioProgram($request);
    }

    /**
     * ラジオ番組更新
     *
     * @param RadioProgramRequest $request ラジオ番組更新リクエストデータ
     * @param int $radio_program_id ラジオ番組ID
     * @return bool 更新できたかどうか
     */
    public function updateRadioProgram(RadioProgramRequest $request, int $radio_program_id): bool
    {
        return $this->radio_program->updateRadioProgram($request, $radio_program_id);
    }

    /**
     * ラジオ番組削除
     *
     * @param int $radio_program_id ラジオ番組ID
     * @return bool 削除できたかどうか
     */
    public function deleteRadioProgram(int $radio_program_id): bool
    {
        return $this->radio_program->deleteRadioProgram($radio_program_id);
    }
}
