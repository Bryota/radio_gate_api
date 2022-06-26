<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InqueryMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string|null $email メールアドレス
     */
    private ?string $email;

    /**
     * @var string|null $type 問い合わせ種別
     */
    private ?string $type;

    /**
     * @var string|null $content 詳細
     */
    private ?string $content;

    /**
     * Create a new message instance.
     *
     * @param string|null $email メールアドレス
     * @param string|null $type 問い合わせ種別
     * @param string|null $content 詳細
     * 
     * @return void
     */
    public function __construct(
        string $email = null,
        string $type = null,
        string $content = null,
    ) {
        $this->email = $email;
        $this->type = $type;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(strval(config('mail.from.address')))
            ->subject('お問い合わせが届きました')
            ->view('email.inquery')
            ->text('email.inquery')
            ->with([
                'email' => $this->email,
                'type' => $this->type,
                'content' => $this->content,
            ]);
    }

    /**
     * InqueryMailインスタンスを取得
     *
     * @param string $email メールアドレス
     * @param string $type 問い合わせ種別
     * @param string $content 詳細
     * 
     * @return InqueryMail InqueryMailインスタンス
     */
    public function getSelf(
        string $email,
        string $type,
        string $content,
    ) {
        return new InqueryMail(
            $email,
            $type,
            $content,
        );
    }
}
