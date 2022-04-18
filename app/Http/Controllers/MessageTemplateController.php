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
     * リスナーに紐づいた投稿テンプレート個別の取得
     * 
     * @param int $message_template_id 投稿テンプレートID
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $message_template_id)
    {
        try {
            $message_template = $this->message_template->getSingleMessageTemplate(auth()->user()->id, $message_template_id);
            return response()->json([
                'message_template' => $message_template
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => '投稿テンプレート個別の取得に失敗しました。'
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

    /**
     * 投稿テンプレート更新
     *
     * @param MessageTemplateRequest $request 投稿テンプレート更新リクエストデータ
     * @param int $message_template_id 投稿テンプレートID
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(MessageTemplateRequest $request, int $message_template_id)
    {
        try {
            $this->message_template->updateMessageTemplate($request, auth()->user()->id, $message_template_id);
            return response()->json([
                'message' => '投稿テンプレートが更新されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => '投稿テンプレートの更新に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
