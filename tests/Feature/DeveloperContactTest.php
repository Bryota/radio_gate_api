<?php

namespace Tests\Feature;

use App\Mail\DeveloperContactMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DeveloperContactTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setUp();

        $this->listener = $this->loginAsListener();
    }

    /**
     * @test
     * App\Http\Controllers\Listener\DeveloperContactController@send
     */
    public function 開発者コンタクトを送信できる()
    {
        Mail::fake();

        $response = $this->postJson('api/developer_contact/send', [
            'email' => 'test@example.com',
            'github' => 'https://example.com',
            'languages' => 'php',
            'content' => str_repeat('test', 10),
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => '開発者コンタクトが送信されました。'
            ]);

        Mail::assertSent(function (DeveloperContactMail $mail) {
            $mail->build();
            return $mail->subject == '開発者コンタクトが届きました';
        });
    }

    /**
     * @test
     * App\Http\Controllers\Listener\DeveloperContactController@send
     */
    public function 開発者コンタクト送信に失敗する（メールアドレス関連）()
    {
        Mail::fake();

        $response1 = $this->postJson('api/developer_contact/send', [
            'email' => '',
            'github' => 'https://example.com',
            'languages' => 'php',
            'content' => str_repeat('test', 10),
        ]);
        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => [
                    'メールアドレスを入力してください。'
                ]
            ]);

        $response2 = $this->postJson('api/developer_contact/send', [
            'email' => 'testtest',
            'github' => 'https://example.com',
            'content' => str_repeat('test', 10),
        ]);
        $response2->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => [
                    'メールアドレスは正しい形式で入力してください。'
                ]
            ]);

        Mail::assertNotSent(DeveloperContactMail::class);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\DeveloperContactController@send
     */
    public function 開発者コンタクト送信に失敗する（GitHubアカウント関連）()
    {
        Mail::fake();

        $response2 = $this->postJson('api/developer_contact/send', [
            'email' => 'test@example.com',
            'github' => str_repeat('test', 101),
            'languages' => 'php',
            'content' => str_repeat('test', 10),
        ]);
        $response2->assertStatus(422)
            ->assertJsonValidationErrors([
                'github' => [
                    'GitHubアカウントは100文字以内で入力してください。'
                ]
            ]);

        Mail::assertNotSent(DeveloperContactMail::class);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\DeveloperContactController@send
     */
    public function 開発者コンタクト送信に失敗する（得意な言語関連）()
    {
        Mail::fake();

        $response2 = $this->postJson('api/developer_contact/send', [
            'email' => 'test@example.com',
            'github' => 'https://example.com',
            'languages' => str_repeat('test', 101),
            'content' => str_repeat('test', 10),
        ]);
        $response2->assertStatus(422)
            ->assertJsonValidationErrors([
                'languages' => [
                    '得意な言語は100文字以内で入力してください。'
                ]
            ]);

        Mail::assertNotSent(DeveloperContactMail::class);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\DeveloperContactController@send
     */
    public function 開発者コンタクト送信に失敗する（ご質問関連）()
    {
        Mail::fake();

        $response2 = $this->postJson('api/developer_contact/send', [
            'email' => 'test@example.com',
            'github' => 'https://example.com',
            'languages' => 'php',
            'content' => str_repeat('test', 1501),
        ]);
        $response2->assertStatus(422)
            ->assertJsonValidationErrors([
                'content' => [
                    '詳細は1500文字以内で入力してください。'
                ]
            ]);

        Mail::assertNotSent(DeveloperContactMail::class);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\DeveloperContactController@send
     */
    public function 開発者コンタクトメールの本文チェック()
    {
        $mailable = new DeveloperContactMail(
            'test@example.com',
            'https://example.com',
            'php',
            str_repeat('test', 10),
        );

        $mailable->assertSeeInOrderInHtml([
            'test@example.com',
            'https://example.com',
            'php',
            str_repeat('test', 10),
        ]);
    }
}
