<?php

namespace App\Http\Controllers;

use App\Services\Listener\ListenerMyProgramService;
use App\Http\Requests\ListenerMyProgramRequest;

class ListenerMyProgramController extends Controller
{
    /**
     * @var ListenerMyProgramService $listener_my_program ListenerMyProgramServiceインスタンス
     */
    private $listener_my_program;

    public function __construct(ListenerMyProgramService $listener_my_program)
    {
        $this->listener_my_program = $listener_my_program;
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
            $this->listener_my_program->storeListenerMyProgram($request, auth()->user()->id);
            return response()->json([
                'message' => 'マイ番組が作成されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'マイ番組の作成に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
