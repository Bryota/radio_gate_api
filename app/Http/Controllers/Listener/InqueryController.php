<?php

namespace App\Http\Controllers\Listener;

use App\Services\Listener\InqueryService;
use App\Http\Requests\InqueryRequest;
use Illuminate\Support\Facades\Log;

class InqueryController extends Controller
{
    /**
     * @var InqueryService $inquery InqueryServiceインスタンス
     */
    private $inquery;

    public function __construct(InqueryService $inquery)
    {
        $this->inquery = $inquery;
    }

    /**
     * お問い合わせ送信
     *
     * @param InqueryRequest $request お問い合わせ用のリクエストデータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(InqueryRequest $request)
    {
        try {
            $this->inquery->sendInquery($request);
            return response()->json([
                'message' => 'お問い合わせが送信されました。'
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('お問い合わせ送信エラー', ['error' => $th, 'request' => $request]);
            return response()->json([
                'message' => 'お問い合わせの送信に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
