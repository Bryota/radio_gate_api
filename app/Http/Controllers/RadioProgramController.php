<?php

namespace App\Http\Controllers;

use App\Services\Radio\RadioProgramService;
use App\Http\Requests\RadioProgramRequest;
use Illuminate\Http\Request;

class RadioProgramController extends Controller
{
    /**
     * @var RadioProgramService $radio_program RadioProgramServiceインスタンス
     */
    private $radio_program;

    public function __construct(RadioProgramService $radio_program)
    {
        $this->radio_program = $radio_program;
    }

    /**
     * ラジオ番組作成
     *
     * @param  RadioProgramRequest $request ラジオ番組作成リクエストデータ
     * @return \Illuminate\Http\Response
     */
    public function store(RadioProgramRequest $request)
    {
        $radio_program = $this->radio_program->storeRadioProgram($request);
        if ($radio_program) {
            return response()->json([
                'message' => 'ラジオ番組が作成されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json([
                'message' => 'ラジオ番組の作成に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
