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
     * ラジオ番組作成
     *
     * @param RadioProgramRequest $request ラジオ番組作成リクエストデータ
     * @return RadioProgram ラジオ番組生成データ
     */
    public function storeRadioProgram(RadioProgramRequest $request): RadioProgram
    {
        $radio_program = $this->radio_program->storeRadioProgram($request);
        return $radio_program;
    }
}
