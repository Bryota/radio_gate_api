<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ListenerController extends Controller
{
    /**
     * ユーザー情報取得
     *
     * @param int $listener_id リスナーID
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $listener_my_program = $this->listener_my_program->getSingleListenerMyProgram(auth()->user()->id, $listener_my_program_id);
            return response()->json([
                'listener_my_program' => $listener_my_program
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'マイ番組個別の取得に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
