<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class ResetPasswordNotification extends ResetPassword
{
    /**
     * @param string $url
     * @return MailMessage
     */
    protected function buildMailMessage($url): MailMessage
    {
        return parent::buildMailMessage($url)
            ->greeting(strval(Lang::get('Greeting')))
            ->salutation(config('app.name'));
    }

    /**
     * @param mixed $notifiable
     * @return string
     */
    protected function resetUrl($notifiable): string
    {
        return  config('app.front_domain') . '/password_reset' . '?' . http_build_query([
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);
    }
}
