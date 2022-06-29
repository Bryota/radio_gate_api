<?php

/**
 * 開発者コンタクトのビジネスロジック
 *
 * 開発者コンタクトに関連するビジネスロジック
 *
 * @author s_ryota sryotapersian@gmail.com
 * @version 1.0
 * @copyright 2022 Ryota Segawa
 */

namespace App\Services\Listener;

use Illuminate\Support\Facades\Mail;
use App\Mail\DeveloperContactMail;
use App\Http\Requests\DeveloperContactRequest;

/**
 * 開発者コンタクト用のサービスクラス
 *
 * @package App\Services
 * @version 1.0
 */
class DeveloperContactService
{

    /**
     * @var DeveloperContactMail $developer_contact DeveloperContactMailインスタンス
     */
    private $developer_contact;

    /**
     * コンストラクタ
     *
     * @param DeveloperContactMail $developer_contact DeveloperContactMailインスタンス
     */
    public function __construct(
        DeveloperContactMail $developer_contact,
    ) {
        $this->developer_contact = $developer_contact;
    }
    /**
     * 開発者コンタクトを送信
     * 
     * @param DeveloperContactRequest $request 開発者コンタクト用のリクエストデータ
     * @return void
     */
    public function sendDeveloperContact(DeveloperContactRequest $request)
    {
        // TODO: Mailファザードはどこかで怒られるかも
        Mail::to(config('mail.mailers.smtp.admin_email'))->send($this->developer_contact->getSelf(
            $request->email,
            $request->github,
            $request->languages,
            $request->content
        ));
    }
}
