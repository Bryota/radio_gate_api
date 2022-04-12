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
     * ラジオ番組一覧取得
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $radio_programs = $this->radio_program->getAllRadioPrograms();
        if ($radio_programs) {
            return response()->json([
                'radio_programs' => $radio_programs
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json([
                'message' => 'ラジオ局一覧の取得に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
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
