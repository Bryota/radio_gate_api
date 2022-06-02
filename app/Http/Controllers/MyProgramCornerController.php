<?php

namespace App\Http\Controllers;

use App\Services\Listener\MyProgramCornerService;
use App\Http\Requests\MyProgramCornerRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Database\Connection;

class MyProgramCornerController extends Controller
{
    /**
     * @var MyProgramCornerService $my_program_corner MyProgramCornerServiceインスタンス
     */
    private $my_program_corner;

    /**
     * @var Connection $db_connection Connectionインスタンス
     */
    private $db_connection;

    /**
     * @var Request $request Requestインスタンス
     */
    private $request;

    public function __construct(MyProgramCornerService $my_program_corner, Connection $db_connection, Request $request)
    {
        $this->my_program_corner = $my_program_corner;
        $this->db_connection = $db_connection;
        $this->request = $request;
    }

    /**
     * マイ番組に紐づいたコーナーの取得
     *
     * @param Request $request getパラメーター
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $listener_id = $this->checkUserId();

        $listener_my_program_id = intval($request->input('listener_my_program'));
        if (!$this->my_program_corner->checkLoginListener(intval($listener_id), $listener_my_program_id)) {
            return response()->json([
                'message' => 'ログインし直してください。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }

        try {
            $my_program_corners = $this->my_program_corner->getAllMyProgramCorners($listener_my_program_id);
            return response()->json([
                'my_program_corners' => $my_program_corners
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
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
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
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
        $listener_id = $this->checkUserId();

        if (!$this->my_program_corner->checkLoginListener(intval($listener_id), $request->integer('listener_my_program_id'))) {
            return response()->json([
                'message' => 'ログインし直してください。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }

        try {
            $this->db_connection->beginTransaction();
            $this->my_program_corner->storeMyProgramCorner($request);
            $this->db_connection->commit();
            return response()->json([
                'message' => 'マイ番組コーナーが作成されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            $this->db_connection->rollBack();
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
        $listener_id = $this->checkUserId();

        if ($request->listener_id !== $listener_id) {
            return response()->json([
                'message' => 'ログインし直してください。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }

        try {
            $this->db_connection->beginTransaction();
            $this->my_program_corner->updateMyProgramCorner($request, $my_program_corner_id);
            $this->db_connection->commit();
            return response()->json([
                'message' => 'マイ番組コーナーが更新されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            $this->db_connection->rollBack();
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
        $listener_id = $this->checkUserId();

        $request_listener_id = intval($request->input('listener'));
        if ($request_listener_id !== $listener_id) {
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

    // TODO: どっかで共通化したい
    /**
     * リスナーIDが取得できるかどうかの確認
     *
     * @return \Illuminate\Http\JsonResponse|int
     */
    private function checkUserId()
    {
        if (!$this->request->user()) {
            return response()->json([
                'message' => 'ログインしてください。'
            ], 401, [], JSON_UNESCAPED_UNICODE);
        }

        return $this->request->user()->id;
    }
}
