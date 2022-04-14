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
        $program_corners = $this->radio_program->getAllProgramCorners($radio_program_id);
        if ($program_corners) {
            return response()->json([
                'progr$program_corners' => $program_corners
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json([
                'message' => '番組コーナー一覧の取得に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
