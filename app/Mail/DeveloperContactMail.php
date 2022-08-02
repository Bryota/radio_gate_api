<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeveloperContactMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string|null $email メールアドレス
     */
    private ?string $email;

    /**
     * @var string|null $type GitHubアカウント
     */
    private ?string $github;

    /**
     * @var string|null $type 得意な言語
     */
    private ?string $languages;

    /**
     * @var string|null $content ご質問
     */
    private ?string $content;

    /**
     * Create a new message instance.
     *
     * @param string|null $email メールアドレス
     * @param string|null $github GitHubアカウント
     * @param string|null $languages 得意な言語
     * @param string|null $content ご質問
     * 
     * @return void
     */
    public function __construct(
        string $email = null,
        string $github = null,
        string $languages = null,
        string $content = null,
    ) {
        $this->email = $email;
        $this->github = $github;
        $this->languages = $languages;
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
            ->subject('開発者コンタクトが届きました')
            ->view('email.developer_contact')
            ->text('email.developer_contact')
            ->with([
                'email' => $this->email,
                'github' => $this->github,
                'languages' => $this->languages,
                'content' => $this->content,
            ]);
    }

    /**
     * DeveloperContactMailインスタンスを取得
     *
     * @param string|null $email メールアドレス
     * @param string|null $github GitHubアカウント
     * @param string|null $languages 得意な言語
     * @param string|null $content ご質問
     * 
     * @return DeveloperContactMail DeveloperContactMailインスタンス
     */
    public function getSelf(
        string|null $email,
        string|null $github,
        string|null $languages,
        string|null $content,
    ) {
        return new DeveloperContactMail(
            $email,
            $github,
            $languages,
            $content,
        );
    }
}
