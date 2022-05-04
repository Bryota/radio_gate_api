<?php

namespace App\Http\Controllers;

use App\Services\Listener\MyProgramCornerService;
use App\Http\Requests\MyProgramCornerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * 個別のマイ番組コーナー取得
     * 
     * @param int $my_program_corner_id マイ番組コーナーID
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $my_program_corner_id)
    {
        try {
            $my_program_corner = $this->my_program_corner->getSingleMyProgramCorner($my_program_corner_id);
            return response()->json([
                'my_program_corner' => $my_program_corner
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'マイ番組コーナーの取得に失敗しました。'
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
            // TODO: facade使うと誰かに怒られるかも
            DB::beginTransaction();
            $this->my_program_corner->storeMyProgramCorner($request);
            DB::commit();
            return response()->json([
                'message' => 'マイ番組コーナーが作成されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'マイ番組コーナーの作成に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * マイ番組コーナ更新
     *
     * @param MyProgramCornerRequest $request マイ番組コーナー作成リクエストデータ
     * @param int $my_program_corner_id マイ番組コーナーID
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(MyProgramCornerRequest $request, $my_program_corner_id)
    {
        if ($request->listener_id !== auth()->user()->id) {
            return response()->json([
                'message' => 'ログインし直してください。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }

        try {
            // TODO: facade使うと誰かに怒られるかも
            DB::beginTransaction();
            $this->my_program_corner->updateMyProgramCorner($request, $my_program_corner_id);
            DB::commit();
            return response()->json([
                'message' => 'マイ番組コーナーが更新されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'マイ番組コーナーの更新に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * マイ番組コーナー削除（1コーナーのみ）
     *
     * @param Request $request getパラメーター
     * @param int $my_program_corner_id マイ番組コーナーID
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, int $my_program_corner_id)
    {
        $listener_id = (int) $request->input('listener');
        if ($listener_id !== auth()->user()->id) {
            return response()->json([
                'message' => 'ログインし直してください。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }

        try {
            $this->my_program_corner->deleteMyProgramCorner($my_program_corner_id);
            return response()->json([
                'message' => 'マイ番組コーナーが削除されました。'
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'マイ番組コーナーの削除に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
