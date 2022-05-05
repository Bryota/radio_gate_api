<?php

namespace App\Http\Controllers;

use App\Services\Listener\ListenerMyProgramService;
use App\Http\Requests\ListenerMyProgramRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Connection;

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

    public function __construct(ListenerMyProgramService $listener_my_program, Connection $db_connection)
    {
        $this->listener_my_program = $listener_my_program;
        $this->db_connection = $db_connection;
    }

    /**
     * リスナーに紐づいたマイ番組一覧の取得
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $listener_my_programs = $this->listener_my_program->getAllListenerMyPrograms(auth()->user()->id);
            return response()->json([
                'listener_my_programs' => $listener_my_programs
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
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
        try {
            $listener_my_program = $this->listener_my_program->getSingleListenerMyProgram(auth()->user()->id, $listener_my_program_id);
            return response()->json([
                'listener_my_program' => $listener_my_program
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
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
        try {
            $this->db_connection->beginTransaction();
            $this->listener_my_program->storeListenerMyProgram($request, auth()->user()->id);
            $this->db_connection->commit();
            return response()->json([
                'message' => 'マイ番組が作成されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            $this->db_connection->rollBack();
            return response()->json([
                'message' => 'マイ番組の作成に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
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
        try {
            $this->db_connection->beginTransaction();
            $this->listener_my_program->updateListenerMyProgram($request, auth()->user()->id, $listener_my_program_id);
            $this->db_connection->commit();
            return response()->json([
                'message' => 'マイ番組が更新されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            $this->db_connection->rollBack();
            return response()->json([
                'message' => 'マイ番組の更新に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
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
        try {
            $this->listener_my_program->deleteListenerMyProgram(auth()->user()->id, $listener_my_program_id);
            return response()->json([
                'message' => 'マイ番組が削除されました。'
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'マイ番組の削除に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
