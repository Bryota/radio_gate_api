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
     * ラジオ局に紐づいたラジオ番組一覧取得
     *
     * @param Request $request ラジオ局ID用のgetパラメーター
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $radio_station_id = $request->input('radio_station');
        try {
            $radio_programs = $this->radio_program->getAllRadioPrograms($radio_station_id);
            return response()->json([
                'radio_programs' => $radio_programs
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'ラジオ番組一覧の取得に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 個別のラジオ番組取得
     * 
     * @param int $radio_program_id ラジオ番組ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $radio_program_id)
    {
        try {
            $radio_program = $this->radio_program->getSingleRadioProgram($radio_program_id);
            return response()->json([
                'radio_program' => $radio_program
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'ラジオ番組の取得に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * ラジオ番組作成
     *
     * @param RadioProgramRequest $request ラジオ番組作成リクエストデータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RadioProgramRequest $request)
    {
        try {
            $radio_program = $this->radio_program->storeRadioProgram($request);
            return response()->json([
                'message' => 'ラジオ番組が作成されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'ラジオ番組の作成に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * ラジオ番組更新
     *
     * @param RadioProgramRequest $request ラジオ番組更新リクエストデータ
     * @param int $radio_program_id ラジオ番組ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(RadioProgramRequest $request, int $radio_program_id)
    {
        try {
            $this->radio_program->updateRadioProgram($request, $radio_program_id);
            return response()->json([
                'message' => 'ラジオ番組が更新されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'ラジオ番組の更新に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * ラジオ番組削除（1番組のみ）
     *
     * @param int $radio_program_id ラジオ番組ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $radio_program_id)
    {
        $radio_programs_destroy_count = $this->radio_program->deleteRadioProgram($radio_program_id);
        // TODO: 分岐の条件検討する
        if ($radio_programs_destroy_count == 1) {
            return response()->json([
                'message' => 'ラジオ番組が削除されました。'
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json([
                'message' => 'ラジオ番組の削除に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
