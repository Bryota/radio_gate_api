<?php

namespace Tests\Feature;

use App\Mail\InqueryMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InqueryTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setUp();

        $this->listener = $this->loginAsListener();
    }

    /**
     * @test
     * App\Http\Controllers\Listener\InqueryController@send
     */
    public function お問い合わせを送信できる()
    {
        Mail::fake();

        $response = $this->postJson('api/inquery/send', [
            'email' => 'test@example.com',
            'type' => '機能に関する質問',
            'content' => str_repeat('test', 10),
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'お問い合わせが送信されました。'
            ]);

        Mail::assertSent(function (InqueryMail $mail) {
            $mail->build();
            return $mail->subject == 'お問い合わせが届きました';
        });
    }

    /**
     * @test
     * App\Http\Controllers\Listener\InqueryController@send
     */
    public function お問い合わせメールの本文チェック()
    {
        $mailable = new InqueryMail(
            'test@example.com',
            '機能に関する質問',
            str_repeat('test', 10),
        );

        $mailable->assertSeeInOrderInHtml([
            'test@example.com',
            '機能に関する質問',
            str_repeat('test', 10),
        ]);
    }
}
