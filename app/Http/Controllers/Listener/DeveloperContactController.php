<?php

namespace App\Http\Controllers\Listener;

use App\Services\Listener\DeveloperContactService;
use App\Http\Requests\DeveloperContactRequest;
use Illuminate\Support\Facades\Log;

class DeveloperContactController extends Controller
{
    /**
     * @var DeveloperContactService $developer_contact DeveloperContactServiceインスタンス
     */
    private $developer_contact;

    public function __construct(DeveloperContactService $developer_contact)
    {
        $this->developer_contact = $developer_contact;
    }

    /**
     * 開発者コンタクト送信
     *
     * @param DeveloperContactRequest $request 開発者コンタクト用のリクエストデータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(DeveloperContactRequest $request)
    {
        try {
            $this->developer_contact->sendDeveloperContact($request);
            return response()->json([
                'message' => '開発者コンタクトが送信されました。'
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('開発者コンタクト送信エラー', ['error' => $th, 'request' => $request]);
            return response()->json([
                'message' => '開発者コンタクトの送信に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
