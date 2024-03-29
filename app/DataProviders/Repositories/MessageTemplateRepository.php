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
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
     * @return LengthAwarePaginator 投稿テンプレート一覧
     */
    public function getAllMessageTemplates(int $listener_id): LengthAwarePaginator
    {
        return $this->message_template::ListenerIdEqual($listener_id)->orderBy('id', 'desc')->paginate(8, ['id', 'name']);
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
        return $this->message_template::where('id', $message_template_id)
            ->ListenerIdEqual($listener_id)
            ->firstOrFail(['id', 'name', 'content']);
    }

    /**
     * 投稿テンプレート作成
     *
     * @param MessageTemplateRequest $request 投稿テンプレート作成リクエストデータ
     * @param int $listener_id リスナーID
     * @return MessageTemplate 投稿テンプレート生成データ
     */
    public function storeMessageTemplate(MessageTemplateRequest $request, int $listener_id): MessageTemplate
    {
        return $this->message_template::create(
            [
                'name' => $request->name,
                'content' => $request->content,
                'listener_id' => $listener_id
            ]
        );
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
        $message_template = $this->message_template::where('id', $message_template_id)
            ->ListenerIdEqual($listener_id)
            ->firstOrFail();
        $message_template->name = $request->string('name');
        $message_template->content = $request->string('content');
        return $message_template->save();
    }

    /**
     * 投稿テンプレート削除
     *
     * @param int $listener_id リスナーID
     * @param int $message_template_id 投稿テンプレートID
     * @return bool|null 削除できたかどうか
     */
    public function deleteMessageTemplate(int $listener_id, int $message_template_id): bool|null
    {
        $message_template = $this->message_template::where('id', $message_template_id)
            ->ListenerIdEqual($listener_id)
            ->firstOrFail();
        return $message_template->delete();
    }
}
