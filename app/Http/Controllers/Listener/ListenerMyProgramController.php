<?php

namespace App\Http\Controllers\Listener;

use App\Services\Listener\ListenerMyProgramService;
use App\Http\Requests\ListenerMyProgramRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ListenerMyProgramController extends Controller
{
    /**
     * @var ListenerMyProgramService $listener_my_program ListenerMyProgramServiceインスタンス
     */
    private $listener_my_program;

    /**
     * @var Connection $db_connection Connectionインスタンス
     */
    private $db_connection;

    /**
     * @var Request $request Requestインスタンス
     */
    private $request;

    public function __construct(ListenerMyProgramService $listener_my_program, Connection $db_connection, Request $request)
    {
        $this->listener_my_program = $listener_my_program;
        $this->db_connection = $db_connection;
        $this->request = $request;
    }

    /**
     * リスナーに紐づいたマイ番組一覧の取得
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $listener_id = $this->checkUserId();

        try {
            $listener_my_programs = $this->listener_my_program->getAllListenerMyPrograms(intval($listener_id));
            return response()->json(
                $listener_my_programs,
                200,
                [],
                JSON_UNESCAPED_UNICODE
            );
        } catch (\Throwable $th) {
            Log::error('マイ番組取得エラー', ['error' => $th, 'listener_id' => $listener_id]);
            return response()->json([
                'message' => 'マイ番組一覧の取得に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * リスナーに紐づいたマイ番組個別の取得
     * 
     * @param int $listener_my_program_id マイ番組ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $listener_my_program_id)
    {
        $listener_id = $this->checkUserId();

        try {
            $listener_my_program = $this->listener_my_program->getSingleListenerMyProgram(intval($listener_id), $listener_my_program_id);
            return response()->json(
                $listener_my_program,
                200,
                [],
                JSON_UNESCAPED_UNICODE
            );
        } catch (ModelNotFoundException $e) {
            Log::error('マイ番組データがありませんでした。', ['error' => $e, 'listener_id' => $listener_id]);
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 404, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('マイ番組データ取得エラー', ['error' => $th, 'listener_id' => $listener_id]);
            return response()->json([
                'message' => 'マイ番組個別の取得に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * マイ番組作成
     *
     * @param ListenerMyProgramRequest $request マイ番組作成リクエストデータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ListenerMyProgramRequest $request)
    {
        $listener_id = $this->checkUserId();

        try {
            $this->db_connection->beginTransaction();
            $listener_my_program = $this->listener_my_program->storeListenerMyProgram($request, intval($listener_id));
            $this->db_connection->commit();
            if ($listener_my_program) {
                return response()->json([
                    'message' => 'マイ番組が作成されました。',
                    'listener_my_program_id' => $listener_my_program->id
                ], 201, [], JSON_UNESCAPED_UNICODE);
            } else {
                throw new Exception();
            }
        } catch (\Throwable $th) {
            $this->db_connection->rollBack();
            Log::error('マイ番組作成エラー', ['error' => $th, 'listener_id' => $listener_id, 'request' => $request]);
            return response()->json([
                'message' => 'マイ番組の作成に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * マイ番組更新
     *
     * @param ListenerMyProgramRequest $request マイ番組更新リクエストデータ
     * @param int $listener_my_program_id マイ番組ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ListenerMyProgramRequest $request, int $listener_my_program_id)
    {
        $listener_id = $this->checkUserId();

        try {
            $this->db_connection->beginTransaction();
            $this->listener_my_program->updateListenerMyProgram($request, intval($listener_id), $listener_my_program_id);
            $this->db_connection->commit();
            return response()->json([
                'message' => 'マイ番組が更新されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            Log::error('マイ番組データがありませんでした。（更新）', ['error' => $e, 'listener_id' => $listener_id, 'request' => $request]);
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 404, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('マイ番組更新エラー', ['error' => $th, 'listener_id' => $listener_id, 'request' => $request]);
            $this->db_connection->rollBack();
            return response()->json([
                'message' => 'マイ番組の更新に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * マイ番組削除（1つのみ）
     *
     * @param int $listener_my_program_id マイ番組ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $listener_my_program_id)
    {
        $listener_id = $this->checkUserId();

        try {
            $this->listener_my_program->deleteListenerMyProgram(intval($listener_id), $listener_my_program_id);
            return response()->json([
                'message' => 'マイ番組が削除されました。'
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('マイ番組削除エラー', ['error' => $th, 'listener_id' => $listener_id]);
            return response()->json([
                'message' => 'マイ番組の削除に失敗しました。'
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
