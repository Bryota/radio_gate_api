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
}
