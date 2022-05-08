<?php

namespace App\Http\Controllers;

use App\Services\Listener\MessageTemplateService;
use App\Http\Requests\MessageTemplateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Connection;

class MessageTemplateController extends Controller
{
    /**
     * @var MessageTemplateService $message_template MessageTemplateServiceインスタンス
     */
    private $message_template;

    /**
     * @var Connection $db_connection Connectionインスタンス
     */
    private $db_connection;

    public function __construct(MessageTemplateService $message_template, Connection $db_connection)
    {
        $this->message_template = $message_template;
        $this->db_connection = $db_connection;
    }

    /**
     * リスナーに紐づいた投稿テンプレート一覧の取得
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // TODO: どっかで共通化するかmiddlewareで対応したい
        if (!auth()->user()) {
            return response()->json([
                'message' => 'ログインしてください。'
            ], 401, [], JSON_UNESCAPED_UNICODE);
        }

        try {
            $message_templates = $this->message_template->getAllMessageTemplates(auth()->user()->id);
            return response()->json([
                'message_templates' => $message_templates
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
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
        // TODO: どっかで共通化するかmiddlewareで対応したい
        if (!auth()->user()) {
            return response()->json([
                'message' => 'ログインしてください。'
            ], 401, [], JSON_UNESCAPED_UNICODE);
        }

        try {
            $message_template = $this->message_template->getSingleMessageTemplate(auth()->user()->id, $message_template_id);
            return response()->json([
                'message_template' => $message_template
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
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
        // TODO: どっかで共通化するかmiddlewareで対応したい
        if (!auth()->user()) {
            return response()->json([
                'message' => 'ログインしてください。'
            ], 401, [], JSON_UNESCAPED_UNICODE);
        }

        try {
            $this->db_connection->beginTransaction();
            $this->message_template->storeMessageTemplate($request, auth()->user()->id);
            $this->db_connection->commit();
            return response()->json([
                'message' => '投稿テンプレートが作成されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            $this->db_connection->rollBack();
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
        // TODO: どっかで共通化するかmiddlewareで対応したい
        if (!auth()->user()) {
            return response()->json([
                'message' => 'ログインしてください。'
            ], 401, [], JSON_UNESCAPED_UNICODE);
        }

        try {
            $this->db_connection->beginTransaction();
            $this->message_template->updateMessageTemplate($request, auth()->user()->id, $message_template_id);
            $this->db_connection->commit();
            return response()->json([
                'message' => '投稿テンプレートが更新されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            $this->db_connection->rollBack();
            return response()->json([
                'message' => '投稿テンプレートの更新に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 投稿テンプレート削除（1つのみ）
     *
     * @param int $message_template_id 投稿テンプレートID
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $message_template_id)
    {
        // TODO: どっかで共通化するかmiddlewareで対応したい
        if (!auth()->user()) {
            return response()->json([
                'message' => 'ログインしてください。'
            ], 401, [], JSON_UNESCAPED_UNICODE);
        }

        try {
            $this->message_template->deleteMessageTemplate(auth()->user()->id, $message_template_id);
            return response()->json([
                'message' => '投稿テンプレートが削除されました。'
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => '投稿テンプレートの削除に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
