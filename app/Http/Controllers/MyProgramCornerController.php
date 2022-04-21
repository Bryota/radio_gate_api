<?php

namespace App\Http\Controllers;

use App\Services\Listener\MyProgramCornerService;
use App\Http\Requests\MyProgramCornerRequest;
use Illuminate\Http\Request;

class MyProgramCornerController extends Controller
{
    /**
     * @var MyProgramCornerService $my_program_corner MyProgramCornerServiceインスタンス
     */
    private $my_program_corner;

    public function __construct(MyProgramCornerService $my_program_corner)
    {
        $this->my_program_corner = $my_program_corner;
    }

    /**
     * マイ番組に紐づいたコーナーの取得
     *
     * @param Request $request getパラメーター
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $listener_my_program_id = $request->input('listener_my_program');
        $listener_id = (int) $request->input('listener');
        if ($listener_id !== auth()->user()->id) {
            return response()->json([
                'message' => 'ログインし直してください。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }

        try {
            $my_program_corners = $this->my_program_corner->getAllMyProgramCorners($listener_my_program_id);
            return response()->json([
                'my_program_corners' => $my_program_corners
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'マイ番組コーナー一覧の取得に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * マイ番組コーナー作成
     *
     * @param MyProgramCornerRequest $request マイ番組コーナー作成リクエストデータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(MyProgramCornerRequest $request)
    {
        if ($request->listener_id !== auth()->user()->id) {
            return response()->json([
                'message' => 'ログインし直してください。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }

        try {
            $this->my_program_corner->storeMyProgramCorner($request);
            return response()->json([
                'message' => 'マイ番組コーナーが作成されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'マイ番組コーナーの作成に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
