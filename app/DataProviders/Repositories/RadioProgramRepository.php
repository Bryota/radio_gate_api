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
     * ラジオ番組作成
     *
     * @param RadioProgramRequest $request ラジオ番組作成リクエストデータ
     * @return RadioProgram ラジオ番組生成データ
     */
    public function storeRadioProgram(RadioProgramRequest $request): RadioProgram
    {
        return $this->radio_program::create($request->all());
    }
}
