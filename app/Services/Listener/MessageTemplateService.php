<?php

/**
 * 投稿テンプレート用の機能関連のビジネスロジック
 *
 * 投稿テンプレートのアクションに関連する
 *
 * @author s_ryota sryotapersian@gmail.com
 * @version 1.0
 * @copyright 2022 Ryota Segawa
 */

namespace App\Services\Listener;

use App\DataProviders\Models\MessageTemplate;
use App\DataProviders\Repositories\MessageTemplateRepository;
use App\Http\Requests\MessageTemplateRequest;

/**
 * 投稿テンプレート用のサービスクラス
 *
 * @package App\Services
 * @version 1.0
 */
class MessageTemplateService
{
    /**
     * @var MessageTemplateRepository $message_template MessageTemplateRepositoryインスタンス
     */
    private $message_template;

    /**
     * コンストラクタ
     *
     * @param MessageTemplateRepository $message_template MessageTemplateRepositoryインスタンス
     */
    public function __construct(MessageTemplateRepository $message_template)
    {
        $this->message_template = $message_template;
    }

    /**
     * リスナーに紐づいた投稿テンプレート一覧の取得
     * 
     * @param int $listener_id リスナーID
     * @return object 投稿テンプレート一覧
     */
    public function getAllMessageTemplates(int $listener_id): object
    {
        return $this->message_template->getAllMessageTemplates($listener_id);
    }

    /**
     * リスナーに紐づいた投稿テンプレート個別の取得
     *
     * @param int $listener_id リスナーID
     * @param int $message_template_id 投稿テンプレートID
     * @return MessageTemplate|null 投稿テンプレートデータ
     */
    public function getSingleMessageTemplate(int $listener_id, int $message_template_id): MessageTemplate|null
    {
        return $this->message_template->getSingleMessageTemplate($listener_id, $message_template_id);
    }

    /**
     * 投稿テンプレート作成
     * 
     * @param MessageTemplateRequest $request 投稿テンプレート登録用のリクエストデータ
     * @param int $listener_id リスナーID
     * @return MessageTemplate 作成された投稿テンプレート情報
     */
    public function storeMessageTemplate(MessageTemplateRequest $request, int $listener_id): MessageTemplate
    {
        return $this->message_template->storeMessageTemplate($request, $listener_id);
    }

    /**
     * 投稿テンプレート更新
     *
     * @param MessageTemplateRequest $request 投稿テンプレート更新リクエストデータ
     * @param int $listener_id リスナーID
     * @param int $message_template_id 投稿テンプレートID
     * @return bool 更新できたかどうか
     */
    public function updateMessageTemplate(MessageTemplateRequest $request, int $listener_id, int $message_template_id): bool
    {
        return $this->message_template->updateMessageTemplate($request, $listener_id, $message_template_id);
    }

    /**
     * 投稿テンプレート削除
     *
     * @param int $listener_id リスナーID
     * @param int $message_template_id  投稿テンプレートID
     * @return bool|null 削除できたかどうか
     */
    public function deleteMessageTemplate(int $listener_id, int $message_template_id): bool|null
    {
        return $this->message_template->deleteMessageTemplate($listener_id, $message_template_id);
    }
}
