<?php

/**
 * 投稿テンプレート用のデータリポジトリ
 *
 * DBから投稿テンプレートの情報取得・更新、削除の責務を担う
 *
 * @author s_ryota sryotapersian@gmail.com
 * @version 1.0
 * @copyright 2022 Ryota Segawa
 */

namespace App\DataProviders\Repositories;

use App\DataProviders\Models\MessageTemplate;
use App\Http\Requests\MessageTemplateRequest;

/**
 * 投稿テンプレートリポジトリクラス
 *
 * @package App\DataProviders\Repositories
 * @version 1.0
 */
class MessageTemplateRepository
{
    /**
     * @var MessageTemplate $message_template MessageTemplateインスタンス
     */
    private $message_template;

    /**
     * コンストラクタ
     *
     * @param MessageTemplate $message_template MessageTemplateModel
     * @return void
     */
    public function __construct(MessageTemplate $message_template)
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
        return $this->message_template::where('listener_id', $listener_id)->get();
    }

    /**
     * リスナーに紐づいた投稿テンプレート個別の取得
     * 
     * @param int $listener_id リスナーID
     * @param int $message_template_id 投稿テンプレートID
     * @return MessageTemplate 投稿テンプレートデータ
     */
    public function getSingleMessageTemplate(int $listener_id, int $message_template_id): MessageTemplate
    {
        return $this->message_template::where('id', $message_template_id)
            ->where('listener_id', $listener_id)
            ->first();
    }

    /**
     * 投稿テンプレート作成
     *
     * @param MessageTemplateRequest $request 投稿テンプレート作成リクエストデータ
     * @return MessageTemplate 投稿テンプレート生成データ
     */
    public function storeMessageTemplate(MessageTemplateRequest $request): MessageTemplate
    {
        return $this->message_template::create($request->all());
    }
}
