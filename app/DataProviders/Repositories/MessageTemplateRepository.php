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
     * @var MessageTemplate $messageTemplate MessageTemplateインスタンス
     */
    private $message_template;

    /**
     * コンストラクタ
     *
     * @param MessageTemplate $messageTemplate MessageTemplateModel
     * @return void
     */
    public function __construct(MessageTemplate $messageTemplate)
    {
        $this->message_template = $messageTemplate;
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
