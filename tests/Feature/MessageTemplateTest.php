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
     * App\Http\Controllers\Listener\MessageTemplateController@store
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
     * App\Http\Controllers\Listener\MessageTemplateController@store
     */
    public function 投稿テンプレート作成に失敗する（名前関連）()
    {
        $response1 = $this->postJson('api/message_templates', ['name' => '', 'content' => 'こんにちは！　いつも楽しく聴いています。', 'listener_id' => $this->listener->id]);
        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => [
                    'テンプレート名を入力してください。'
                ]
            ]);

        $response2 = $this->postJson('api/message_templates', ['name' => str_repeat('あ', 151), 'content' => 'こんにちは！　いつも楽しく聴いています。', 'listener_id' => $this->listener->id]);
        $response2->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => [
                    'テンプレート名は150文字以下で入力してください。'
                ]
            ]);

        $this->assertEquals(0, MessageTemplate::count());
    }

    /**
     * @test
     * App\Http\Controllers\Listener\MessageTemplateController@store
     */
    public function 投稿テンプレート作成に失敗する（本文関連）()
    {
        $response1 = $this->postJson('api/message_templates', ['name' => 'テストテンプレート', 'content' => '', 'listener_id' => $this->listener->id]);
        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'content' => [
                    'テンプレート本文を入力してください。'
                ]
            ]);

        $response2 = $this->postJson('api/message_templates', ['name' => 'テストテンプレート', 'content' => str_repeat('あ', 1001), 'listener_id' => $this->listener->id]);
        $response2->assertStatus(422)
            ->assertJsonValidationErrors([
                'content' => [
                    'テンプレート本文は1000文字以下で入力してください。'
                ]
            ]);

        $this->assertEquals(0, MessageTemplate::count());
    }

    /**
     * @test
     * App\Http\Controllers\Listener\MessageTemplateController@index
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

    /**
     * @test
     * App\Http\Controllers\Listener\MessageTemplateController@show
     */
    public function 個別の投稿テンプレートを取得できる()
    {
        $this->postJson('api/message_templates', ['name' => 'テストテンプレート1', 'content' => 'hello', 'listener_id' => $this->listener->id]);
        $message_template = MessageTemplate::first();

        $response = $this->getJson('api/message_templates/' . $message_template->id);

        $response->assertStatus(200)
            ->assertJson([
                'message_template' => [
                    'name' => 'テストテンプレート1',
                    'content' => 'hello',
                    'listener_id' => $this->listener->id
                ]
            ]);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\MessageTemplateController@update
     */
    public function 投稿テンプレートを更新できる()
    {
        $this->postJson('api/message_templates', ['name' => 'テストテンプレート1', 'content' => 'hello', 'listener_id' => $this->listener->id]);
        $message_template = MessageTemplate::first();

        $response = $this->putJson('api/message_templates/' . $message_template->id, ['listener_id' => $this->listener->id, 'name' => 'テストテンプレート更新', 'content' => 'hello update']);
        $response->assertStatus(201)
            ->assertJson([
                'message' => '投稿テンプレートが更新されました。'
            ]);

        $message_template = MessageTemplate::first();
        $this->assertEquals('テストテンプレート更新', $message_template->name);
        $this->assertEquals('hello update', $message_template->content);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\MessageTemplateController@update
     */
    public function 投稿テンプレート更新に失敗する（名前関連）()
    {
        $this->postJson('api/message_templates', ['name' => 'テストテンプレート1', 'content' => 'hello', 'listener_id' => $this->listener->id]);
        $message_template = MessageTemplate::first();

        $response1 = $this->putJson('api/message_templates/' . $message_template->id, ['listener_id' => $this->listener->id, 'name' => '', 'content' => 'hello update']);
        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => [
                    'テンプレート名を入力してください。'
                ]
            ]);

        $response2 = $this->putJson('api/message_templates/' . $message_template->id, ['listener_id' => $this->listener->id, 'name' => str_repeat('あ', 151), 'content' => 'hello update']);
        $response2->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => [
                    'テンプレート名は150文字以下で入力してください。'
                ]
            ]);

        $message_template = MessageTemplate::first();
        $this->assertEquals('テストテンプレート1', $message_template->name);
        $this->assertEquals('hello', $message_template->content);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\MessageTemplateController@update
     */
    public function 投稿テンプレート更新に失敗する（コンテンツ関連）()
    {
        $this->postJson('api/message_templates', ['name' => 'テストテンプレート1', 'content' => 'hello', 'listener_id' => $this->listener->id]);
        $message_template = MessageTemplate::first();

        $response1 = $this->putJson('api/message_templates/' . $message_template->id, ['listener_id' => $this->listener->id, 'name' => 'テストテンプレート1', 'content' => '']);
        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'content' => [
                    'テンプレート本文を入力してください。'
                ]
            ]);

        $response2 = $this->putJson('api/message_templates/' . $message_template->id, ['listener_id' => $this->listener->id, 'name' => 'テストテンプレート1', 'content' => str_repeat('あ', 1001)]);
        $response2->assertStatus(422)
            ->assertJsonValidationErrors([
                'content' => [
                    'テンプレート本文は1000文字以下で入力してください。'
                ]
            ]);


        $message_template = MessageTemplate::first();
        $this->assertEquals('テストテンプレート1', $message_template->name);
        $this->assertEquals('hello', $message_template->content);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\MessageTemplateController@destory
     */
    public function 投稿テンプレートを削除できる()
    {
        $this->postJson('api/message_templates', ['name' => 'テストテンプレート1', 'content' => 'hello', 'listener_id' => $this->listener->id]);
        $message_template = MessageTemplate::first();

        $response = $this->deleteJson('api/message_templates/' . $message_template->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => '投稿テンプレートが削除されました。'
            ]);

        $this->assertEquals(0, MessageTemplate::count());
    }
}
