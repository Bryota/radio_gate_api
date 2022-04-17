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
     * 投稿テンプレート作成
     * 
     * @param MessageTemplateRequest $request 投稿テンプレート登録用のリクエストデータ
     * @return MessageTemplate 作成された投稿テンプレート情報
     */
    public function storeMessageTemplate(MessageTemplateRequest $request): MessageTemplate
    {
        $message_template = $this->message_template->storeMessageTemplate($request);
        return $message_template;
    }
}