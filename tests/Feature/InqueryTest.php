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

        $response = $this->postJson('api/inquery', [
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
    public function お問い合わせ送信に失敗する（メールアドレス関連）()
    {
        Mail::fake();

        $response1 = $this->postJson('api/inquery', [
            'email' => '',
            'type' => '機能に関する質問',
            'content' => str_repeat('test', 10),
        ]);
        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => [
                    'メールアドレスを入力してください。'
                ]
            ]);

        $response2 = $this->postJson('api/inquery', [
            'email' => 'testtest',
            'type' => '機能に関する質問',
            'content' => str_repeat('test', 10),
        ]);
        $response2->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => [
                    'メールアドレスは正しい形式で入力してください。'
                ]
            ]);

        Mail::assertNotSent(InqueryMail::class);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\InqueryController@send
     */
    public function お問い合わせ送信に失敗する（問い合わせ種別関連）()
    {
        Mail::fake();

        $response1 = $this->postJson('api/inquery', [
            'email' => 'test@example.com',
            'type' => '',
            'content' => str_repeat('test', 10),
        ]);
        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'type' => [
                    '問い合わせ種別を入力してください。'
                ]
            ]);

        $response2 = $this->postJson('api/inquery', [
            'email' => 'test@example.com',
            'type' => str_repeat('test', 151),
            'content' => str_repeat('test', 10),
        ]);
        $response2->assertStatus(422)
            ->assertJsonValidationErrors([
                'type' => [
                    '問い合わせ種別は150文字以内で入力してください。'
                ]
            ]);

        Mail::assertNotSent(InqueryMail::class);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\InqueryController@send
     */
    public function お問い合わせ送信に失敗する（詳細関連）()
    {
        Mail::fake();

        $response1 = $this->postJson('api/inquery', [
            'email' => 'test@example.com',
            'type' => '機能に関する質問',
            'content' => '',
        ]);
        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'content' => [
                    '詳細を入力してください。'
                ]
            ]);

        $response2 = $this->postJson('api/inquery', [
            'email' => 'test@example.com',
            'type' => '機能に関する質問',
            'content' => str_repeat('test', 1501),
        ]);
        $response2->assertStatus(422)
            ->assertJsonValidationErrors([
                'content' => [
                    '詳細は1500文字以内で入力してください。'
                ]
            ]);

        Mail::assertNotSent(InqueryMail::class);
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
