<?php

namespace App\Http\Controllers\Listener;

use App\Services\Radio\RadioProgramService;
use App\Http\Requests\RadioProgramRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Log;

class RadioProgramController extends Controller
{
    /**
     * @var RadioProgramService $radio_program RadioProgramServiceインスタンス
     */
    private $radio_program;

    /**
     * @var Connection $db_connection Connectionインスタンス
     */
    private $db_connection;

    public function __construct(RadioProgramService $radio_program, Connection $db_connection)
    {
        $this->radio_program = $radio_program;
        $this->db_connection = $db_connection;
    }

    /**
     * ラジオ局に紐づいたラジオ番組一覧取得
     *
     * @param Request $request getパラメーター
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $radio_programs = $this->radio_program->getAllRadioPrograms($request);
            return response()->json([
                'radio_programs' => $radio_programs
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            Log::error('ラジオ番組一覧がありませんでした。', ['error' => $e]);
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('ラジオ番組一覧取得エラー', ['error' => $th]);
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
        } catch (ModelNotFoundException $e) {
            Log::error('ラジオ番組がありませんでした。', ['error' => $e]);
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('ラジオ番組取得エラー', ['error' => $th]);
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
            $this->db_connection->beginTransaction();
            $this->radio_program->storeRadioProgram($request);
            $this->db_connection->commit();
            return response()->json([
                'message' => 'ラジオ番組が作成されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('ラジオ番組作成エラー', ['error' => $th, 'request' => $request]);
            $this->db_connection->rollBack();
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
            $this->db_connection->beginTransaction();
            $this->radio_program->updateRadioProgram($request, $radio_program_id);
            $this->db_connection->commit();
            return response()->json([
                'message' => 'ラジオ番組が更新されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            Log::error('ラジオ番組がありませんでした。（更新）', ['error' => $e, 'request' => $request]);
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            $this->db_connection->rollBack();
            Log::error('ラジオ番組更新エラー', ['error' => $th, 'request' => $request]);
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
        try {
            $this->radio_program->deleteRadioProgram($radio_program_id);
            return response()->json([
                'message' => 'ラジオ番組が削除されました。'
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('ラジオ番組削除エラー', ['error' => $th]);
            return response()->json([
                'message' => 'ラジオ番組の削除に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
