<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\DataProviders\Models\MessageTemplate;
use Tests\TestCase;

class MessageTemplateTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setUp();

        $this->listener = $this->loginAsListener();
    }

    /**
     * @test
     * App\Http\Controllers\MessageTemplateController@store
     */
    public function 投稿テンプレートが作成できる()
    {
        $response = $this->postJson('api/message_templates', ['name' => 'テストテンプレート', 'content' => 'こんにちは！　いつも楽しく聴いています。', 'listener_id' => $this->listener->id]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => '投稿テンプレートが作成されました。'
            ]);

        $message_template = MessageTemplate::first();
        $this->assertEquals('テストテンプレート', $message_template->name);
        $this->assertEquals('こんにちは！　いつも楽しく聴いています。', $message_template->content);
        $this->assertEquals($this->listener->id, $message_template->listener_id);
        $this->assertEquals($this->listener->name, $message_template->Listener->name);
    }

    /**
     * @test
     * App\Http\Controllers\MessageTemplateController@store
     */
    public function 投稿テンプレート作成に失敗する（名前関連）()
    {
        $response1 = $this->postJson('api/message_templates', ['name' => '', 'content' => 'こんにちは！　いつも楽しく聴いています。', 'listener_id' => $this->listener->id]);
        $response1->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        'テンプレート名を入力してください。'
                    ]
                ]
            ]);

        $response2 = $this->postJson('api/message_templates', ['name' => str_repeat('あ', 151), 'content' => 'こんにちは！　いつも楽しく聴いています。', 'listener_id' => $this->listener->id]);
        $response2->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        'テンプレート名は150文字以下で入力してください。'
                    ]
                ]
            ]);

        $this->assertEquals(0, MessageTemplate::count());
    }

    /**
     * @test
     * App\Http\Controllers\MessageTemplateController@store
     */
    public function 投稿テンプレート作成に失敗する（本文関連）()
    {
        $response1 = $this->postJson('api/message_templates', ['name' => 'テストテンプレート', 'content' => '', 'listener_id' => $this->listener->id]);
        $response1->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'content' => [
                        'テンプレート本文を入力してください。'
                    ]
                ]
            ]);

        $response2 = $this->postJson('api/message_templates', ['name' => 'テストテンプレート', 'content' => str_repeat('あ', 1001), 'listener_id' => $this->listener->id]);
        $response2->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'content' => [
                        'テンプレート本文は1000文字以下で入力してください。'
                    ]
                ]
            ]);

        $this->assertEquals(0, MessageTemplate::count());
    }

    /**
     * @test
     * App\Http\Controllers\MessageTemplateController@index
     */
    public function 投稿テンプレート一覧を取得できる()
    {
        $this->postJson('api/message_templates', ['name' => 'テストテンプレート1', 'content' => 'hello', 'listener_id' => $this->listener->id]);
        $this->postJson('api/message_templates', ['name' => 'テストテンプレート2', 'content' => 'こんにちは', 'listener_id' => $this->listener->id]);

        $response = $this->getJson('api/message_templates');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'テストテンプレート1'])
            ->assertJsonFragment(['name' => 'テストテンプレート2'])
            ->assertJsonFragment(['content' => 'hello'])
            ->assertJsonFragment(['content' => 'こんにちは']);
    }
}
