<?php

namespace App\Http\Controllers\Listener;

use App\Services\Radio\ProgramCornerService;
use App\Http\Requests\ProgramCornerRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Log;

class ProgramCornerController extends Controller
{
    /**
     * @var ProgramCornerService $program_corner ProgramCornerServiceインスタンス
     */
    private $program_corner;

    /**
     * @var Connection $db_connection Connectionインスタンス
     */
    private $db_connection;

    public function __construct(ProgramCornerService $program_corner, Connection $db_connection)
    {
        $this->program_corner = $program_corner;
        $this->db_connection = $db_connection;
    }

    /**
     * ラジオ番組に紐づいたコーナーの取得
     *
     * @param Request $request ラジオ番組ID用のgetパラメーター
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $radio_program_id = intval($request->input('radio_program'));
        try {
            $program_corners = $this->program_corner->getAllProgramCorners($radio_program_id);
            return response()->json(
                $program_corners,
                200,
                [],
                JSON_UNESCAPED_UNICODE
            );
        } catch (ModelNotFoundException $e) {
            Log::error('番組コーナー一覧がありませんでした。', ['error' => $e]);
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 404, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('番組コーナー一覧取得エラー', ['error' => $th]);
            return response()->json([
                'message' => '番組コーナー一覧の取得に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 個別の番組コーナー取得
     * 
     * @param int $program_corner_id 番組コーナーID
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $program_corner_id)
    {
        try {
            $program_corner = $this->program_corner->getSingleProgramCorner($program_corner_id);
            return response()->json(
                $program_corner,
                200,
                [],
                JSON_UNESCAPED_UNICODE
            );
        } catch (ModelNotFoundException $e) {
            Log::error('番組コーナーがありませんでした。', ['error' => $e]);
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 404, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('番組コーナー取得エラー。', ['error' => $th]);
            return response()->json([
                'message' => '番組コーナーの取得に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 番組コーナー作成
     *
     * @param ProgramCornerRequest $request 番組コーナー作成リクエストデータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProgramCornerRequest $request)
    {
        try {
            $this->db_connection->beginTransaction();
            $this->program_corner->storeProgramCorner($request);
            $this->db_connection->commit();
            return response()->json([
                'message' => '番組コーナーが作成されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            $this->db_connection->rollBack();
            Log::error('番組コーナー作成エラー。', ['error' => $th, 'request' => $request]);
            return response()->json([
                'message' => '番組コーナーの作成に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 番組コーナ更新
     *
     * @param ProgramCornerRequest $request 番組コーナー作成リクエストデータ
     * @param int $program_corner_id 番組コーナーID
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProgramCornerRequest $request, $program_corner_id)
    {
        try {
            $this->db_connection->beginTransaction();
            $this->program_corner->updateProgramCorner($request, $program_corner_id);
            $this->db_connection->commit();
            return response()->json([
                'message' => '番組コーナーが更新されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            Log::error('番組コーナーがありませんでした。（更新）', ['error' => $e, 'request' => $request]);
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 404, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            $this->db_connection->rollBack();
            Log::error('番組コーナー更新エラー。', ['error' => $th, 'request' => $request]);
            return response()->json([
                'message' => '番組コーナーの更新に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 番組コーナー削除（1番組のみ）
     *
     * @param int $program_corner_id 番組コーナーID
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $program_corner_id)
    {
        try {
            $this->program_corner->deleteProgramCorner($program_corner_id);
            return response()->json([
                'message' => '番組コーナーが削除されました。'
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('番組コーナー削除エラー。', ['error' => $th]);
            return response()->json([
                'message' => '番組コーナーの削除に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
