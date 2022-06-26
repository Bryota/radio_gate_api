<?php

/**
 * お問い合わせのビジネスロジック
 *
 * お問い合わせに関連するビジネスロジック
 *
 * @author s_ryota sryotapersian@gmail.com
 * @version 1.0
 * @copyright 2022 Ryota Segawa
 */

namespace App\Services\Listener;

use Illuminate\Support\Facades\Mail;
use App\Mail\InqueryMail;
use App\Http\Requests\InqueryRequest;

/**
 * お問い合わせ用のサービスクラス
 *
 * @package App\Services
 * @version 1.0
 */
class InqueryService
{

    /**
     * @var InqueryMail $inquery InqueryMailインスタンス
     */
    private $inquery;

    /**
     * コンストラクタ
     *
     * @param InqueryMail $inquery InqueryMailインスタンス
     */
    public function __construct(
        InqueryMail $inquery,
    ) {
        $this->inquery = $inquery;
    }
    /**
     * お問い合わせを送信
     * 
     * @param InqueryRequest $request お問い合わせ用のリクエストデータ
     * @return void
     */
    public function sendInquery(InqueryRequest $request)
    {
        // TODO: Mailファザードはどこかで怒られるかも
        Mail::to(config('mail.mailers.smtp.admin_email'))->send($this->inquery->getSelf(
            $request->email,
            $request->type,
            $request->content
        ));
    }
}
