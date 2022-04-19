<?php

/**
 * ラジオ番組用のデータリポジトリ
 *
 * DBからラジオ番組の情報取得・更新、削除の責務を担う
 *
 * @author s_ryota sryotapersian@gmail.com
 * @version 1.0
 * @copyright 2022 Ryota Segawa
 */

namespace App\DataProviders\Repositories;

use App\DataProviders\Models\RadioProgram;
use App\Http\Requests\RadioProgramRequest;

/**
 * ラジオ番組リポジトリクラス
 *
 * @package App\DataProviders\Repositories
 * @version 1.0
 */
class RadioProgramRepository
{
    /**
     * @var RadioProgram $radio_program RadioProgramインスタンス
     */
    private $radio_program;

    /**
     * コンストラクタ
     *
     * @param RadioProgram $radio_program RadioProgramModel
     * @return void
     */
    public function __construct(RadioProgram $radio_program)
    {
        $this->radio_program = $radio_program;
    }

    /**
     * ラジオ局に紐づいたラジオ番組一覧取得
     * 
     * @param int $radio_station_id ラジオ局ID
     * @return object ラジオ番組一覧
     */
    public function getAllRadioPrograms(int $radio_station_id): object
    {
        return $this->radio_program::where('radio_station_id', $radio_station_id)->get();
    }

    /**
     * 個別のラジオ番組取得
     * 
     * @param int $radio_program_id ラジオ番組ID
     * @return RadioProgram ラジオ番組データ
     */
    public function getSingleRadioProgram(int $radio_program_id): RadioProgram
    {
        return $this->radio_program::find($radio_program_id);
    }

    /**
     * ラジオ番組作成
     *
     * @param RadioProgramRequest $request ラジオ番組作成リクエストデータ
     * @return RadioProgram ラジオ番組生成データ
     */
    public function storeRadioProgram(RadioProgramRequest $request): RadioProgram
    {
        return $this->radio_program::create($request->all());
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
        $radio_program = $this->radio_program::find($radio_program_id);
        $radio_program->name = $request->name;
        $radio_program->email = $request->email;
        return $radio_program->save();
    }

    /**
     * ラジオ番組削除
     *
     * @param int $radio_program_id ラジオ番組ID
     * @return bool 削除したかどうか
     */
    public function deleteRadioProgram(int $radio_program_id): bool
    {
        $radio_program = $this->radio_program::find($radio_program_id);
        return $radio_program->delete();
    }
}
