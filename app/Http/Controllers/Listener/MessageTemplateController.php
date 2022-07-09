<?php

namespace App\Http\Controllers\Listener;

use App\Services\Listener\MessageTemplateService;
use App\Http\Requests\MessageTemplateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    /**
     * @var Request $request Requestインスタンス
     */
    private $request;

    public function __construct(MessageTemplateService $message_template, Connection $db_connection, Request $request)
    {
        $this->message_template = $message_template;
        $this->db_connection = $db_connection;
        $this->request = $request;
    }

    /**
     * リスナーに紐づいた投稿テンプレート一覧の取得
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $listener_id = $this->checkUserId();

        try {
            $message_templates = $this->message_template->getAllMessageTemplates(intval($listener_id));
            return response()->json([
                'message_templates' => $message_templates
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            Log::error('投稿テンプレート一覧データがありませんでした。', ['error' => $e, 'listener_id' => $listener_id]);
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('投稿テンプレート一覧取得エラー。', ['error' => $th, 'listener_id' => $listener_id]);
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
        $listener_id = $this->checkUserId();

        try {
            $message_template = $this->message_template->getSingleMessageTemplate(intval($listener_id), $message_template_id);
            return response()->json([
                'message_template' => $message_template
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            Log::error('投稿テンプレートデータがありませんでした。', ['error' => $e, 'listener_id' => $listener_id]);
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('投稿テンプレート取得エラー。', ['error' => $th, 'listener_id' => $listener_id]);
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
        $listener_id = $this->checkUserId();

        try {
            $this->db_connection->beginTransaction();
            $this->message_template->storeMessageTemplate($request, intval($listener_id));
            $this->db_connection->commit();
            return response()->json([
                'message' => '投稿テンプレートが作成されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            $this->db_connection->rollBack();
            Log::error('投稿テンプレート作成エラー。', ['error' => $th, 'listener_id' => $listener_id, 'request' => $request]);
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
        $listener_id = $this->checkUserId();

        try {
            $this->db_connection->beginTransaction();
            $this->message_template->updateMessageTemplate($request, intval($listener_id), $message_template_id);
            $this->db_connection->commit();
            return response()->json([
                'message' => '投稿テンプレートが更新されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            Log::error('投稿テンプレートデータがありませんでした。（更新）', ['error' => $e, 'listener_id' => $listener_id]);
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            $this->db_connection->rollBack();
            Log::error('投稿テンプレート更新エラー。', ['error' => $th, 'listener_id' => $listener_id, 'request' => $request]);
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
        $listener_id = $this->checkUserId();

        try {
            $this->message_template->deleteMessageTemplate(intval($listener_id), $message_template_id);
            return response()->json([
                'message' => '投稿テンプレートが削除されました。'
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('投稿テンプレート削除エラー。', ['error' => $th, 'listener_id' => $listener_id]);
            return response()->json([
                'message' => '投稿テンプレートの削除に失敗しました。'
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
