<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\DataProviders\Models\ListenerMyProgram;
use Tests\TestCase;

class ListenerMyProgramTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setUp();

        $this->listener = $this->loginAsListener();
    }

    /**
     * @test
     * App\Http\Controllers\ListenerMyProgramController@store
     */
    public function マイ番組が作成できる()
    {
        $response = $this->postJson('api/listener_my_programs', ['program_name' => 'テストマイ番組', 'email' => 'test@example.com']);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'マイ番組が作成されました。'
            ]);

        $listener_my_program = ListenerMyProgram::first();
        $this->assertEquals('テストマイ番組', $listener_my_program->program_name);
        $this->assertEquals('test@example.com', $listener_my_program->email);
        $this->assertEquals($this->listener->id, $listener_my_program->listener_id);
        $this->assertEquals($this->listener->name, $listener_my_program->Listener->name);
    }

    /**
     * @test
     * App\Http\Controllers\ListenerMyProgramController@store
     */
    public function マイ番組作成に失敗する（名前関連）()
    {
        $response1 = $this->postJson('api/listener_my_programs', ['program_name' => '', 'email' => 'test@example.com']);
        $response1->assertStatus(400)
            ->assertJsonValidationErrors([
                'program_name' => [
                    '番組名を入力してください。'
                ]
            ]);

        $response2 = $this->postJson('api/listener_my_programs', ['program_name' => str_repeat('あ', 151), 'content' => 'こんにちは！　いつも楽しく聴いています。', 'listener_id' => $this->listener->id]);
        $response2->assertStatus(400)
            ->assertJsonValidationErrors([
                'program_name' => [
                    '番組名は150文字以下で入力してください。'
                ]
            ]);

        $this->assertEquals(0, ListenerMyProgram::count());
    }

    /**
     * @test
     * App\Http\Controllers\ListenerMyProgramController@store
     */
    public function マイ番組作成に失敗する（メールアドレス関連）()
    {
        $response1 = $this->postJson('api/listener_my_programs', ['program_name' => 'テストマイ番組', 'email' => '']);
        $response1->assertStatus(400)
            ->assertJsonValidationErrors([
                'email' => [
                    '番組メールアドレスを入力してください。'
                ]
            ]);

        $this->postJson('api/listener_my_programs', ['program_name' => 'テストマイ番組', 'email' => 'test@example.com']);
        $response2 = $this->postJson('api/listener_my_programs', ['program_name' => 'テストマイ番組', 'email' => 'test@example.com']);
        $response2->assertStatus(400)
            ->assertJsonValidationErrors([
                'email' => [
                    '番組メールアドレスは既に保存されています。'
                ]
            ]);

        $this->assertEquals(1, ListenerMyProgram::count());
    }

    /**
     * @test
     * App\Http\Controllers\ListenerMyProgramController@index
     */
    public function マイ番組一覧を取得できる()
    {
        $this->postJson('api/listener_my_programs', ['program_name' => 'テストマイ番組1', 'email' => 'test1@example.com']);
        $this->postJson('api/listener_my_programs', ['program_name' => 'テストマイ番組2', 'email' => 'test2@example.com']);


        $response = $this->getJson('api/listener_my_programs');

        $response->assertStatus(200)
            ->assertJsonFragment(['program_name' => 'テストマイ番組1'])
            ->assertJsonFragment(['program_name' => 'テストマイ番組2'])
            ->assertJsonFragment(['email' => 'test1@example.com'])
            ->assertJsonFragment(['email' => 'test2@example.com']);
    }
}
