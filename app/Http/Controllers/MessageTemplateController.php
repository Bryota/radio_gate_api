<?php

namespace App\Http\Controllers;

use App\Services\Listener\MessageTemplateService;
use App\Http\Requests\MessageTemplateRequest;
use Illuminate\Http\Request;

class MessageTemplateController extends Controller
{
    /**
     * @var MessageTemplateService $message_template MessageTemplateServiceインスタンス
     */
    private $message_template;

    public function __construct(MessageTemplateService $message_template)
    {
        $this->message_template = $message_template;
    }


    /**
     * リスナーに紐づいた投稿テンプレート一覧の取得
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $message_templates = $this->message_template->getAllMessageTemplates(auth()->user()->id);
            return response()->json([
                'message_templates' => $message_templates
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => '投稿テンプレート一覧の取得に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 投稿テンプレート作成
     *
     * @param MessageTemplateRequest $request 投稿テンプレート作成リクエストデータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(MessageTemplateRequest $request)
    {
        try {
            // TODO: リスナーIDの渡し方検討
            $this->message_template->storeMessageTemplate($request);
            return response()->json([
                'message' => '投稿テンプレートが作成されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => '投稿テンプレートの作成に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
