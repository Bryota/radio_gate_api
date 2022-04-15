<?php

namespace App\Http\Controllers;

use App\Services\Radio\ProgramCornerService;
use App\Http\Requests\ProgramCornerRequest;
use Illuminate\Http\Request;

class ProgramCornerController extends Controller
{
    /**
     * @var ProgramCornerService $program_corner ProgramCornerServiceインスタンス
     */
    private $program_corner;

    public function __construct(ProgramCornerService $program_corner)
    {
        $this->program_corner = $program_corner;
    }

    /**
     * ラジオ番組に紐づいたコーナーの取得
     *
     * @param Request $request ラジオ番組ID用のgetパラメーター
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $radio_program_id = $request->input('radio_program');
        $program_corners = $this->program_corner->getAllProgramCorners($radio_program_id);
        if ($program_corners) {
            return response()->json([
                'program_corners' => $program_corners
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json([
                'message' => '番組コーナー一覧の取得に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 番組コーナー作成
     *
     * @param ProgramCornerRequest $request 番組コーナー作成リクエストデータ
     * @return \Illuminate\Http\Response
     */
    public function store(ProgramCornerRequest $request)
    {
        $program_corner = $this->program_corner->storeProgramCorner($request);
        if ($program_corner) {
            return response()->json([
                'message' => '番組コーナーが作成されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json([
                'message' => '番組コーナーの作成に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 番組コーナ更新
     *
     * @param ProgramCornerRequest $request 番組コーナー作成リクエストデータ
     * @param int $program_corner_id 番組コーナーID
     * @return \Illuminate\Http\Response
     */
    public function update(ProgramCornerRequest $request, $program_corner_id)
    {
        $program_corner = $this->program_corner->updateProgramCorner($request, $program_corner_id);
        if ($program_corner) {
            return response()->json([
                'message' => '番組コーナーが更新されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json([
                'message' => '番組コーナーの更新に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
