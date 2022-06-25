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
     * App\Http\Controllers\Listener\ListenerMyProgramController@store
     */
    public function マイ番組が作成できる()
    {
        $response = $this->postJson('api/listener_my_programs', ['name' => 'テストマイ番組', 'email' => 'test@example.com']);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'テストマイ番組'])
            ->assertJsonFragment(['email' => 'test@example.com']);

        $listener_my_program = ListenerMyProgram::first();
        $this->assertEquals('テストマイ番組', $listener_my_program->name);
        $this->assertEquals('test@example.com', $listener_my_program->email);
        $this->assertEquals($this->listener->id, $listener_my_program->listener_id);
        $this->assertEquals($this->listener->name, $listener_my_program->Listener->name);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\ListenerMyProgramController@store
     */
    public function マイ番組作成に失敗する（名前関連）()
    {
        $response1 = $this->postJson('api/listener_my_programs', ['name' => '', 'email' => 'test@example.com']);
        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => [
                    '番組名を入力してください。'
                ]
            ]);

        $response2 = $this->postJson('api/listener_my_programs', ['name' => str_repeat('あ', 151), 'content' => 'こんにちは！　いつも楽しく聴いています。', 'listener_id' => $this->listener->id]);
        $response2->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => [
                    '番組名は150文字以下で入力してください。'
                ]
            ]);

        $this->assertEquals(0, ListenerMyProgram::count());
    }

    /**
     * @test
     * App\Http\Controllers\Listener\ListenerMyProgramController@store
     */
    public function マイ番組作成に失敗する（メールアドレス関連）()
    {
        $response1 = $this->postJson('api/listener_my_programs', ['name' => 'テストマイ番組', 'email' => '']);
        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => [
                    '番組メールアドレスを入力してください。'
                ]
            ]);

        $this->assertEquals(0, ListenerMyProgram::count());
    }

    /**
     * @test
     * App\Http\Controllers\Listener\ListenerMyProgramController@index
     */
    public function マイ番組一覧を取得できる()
    {
        $this->postJson('api/listener_my_programs', ['name' => 'テストマイ番組1', 'email' => 'test1@example.com']);
        $this->postJson('api/listener_my_programs', ['name' => 'テストマイ番組2', 'email' => 'test2@example.com']);


        $response = $this->getJson('api/listener_my_programs');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'テストマイ番組1'])
            ->assertJsonFragment(['name' => 'テストマイ番組2'])
            ->assertJsonFragment(['email' => 'test1@example.com'])
            ->assertJsonFragment(['email' => 'test2@example.com']);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\ListenerMyProgramController@show
     */
    public function 個別の投稿テンプレートを取得できる()
    {
        $this->postJson('api/listener_my_programs', ['name' => 'テストマイ番組1', 'email' => 'test1@example.com']);
        $listener_my_program = ListenerMyProgram::first();

        $response = $this->getJson('api/listener_my_programs/' . $listener_my_program->id);

        $response->assertStatus(200)
            ->assertJson([
                'listener_my_program' => [
                    'name' => 'テストマイ番組1',
                    'email' => 'test1@example.com',
                    'listener_id' => $this->listener->id
                ]
            ]);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\ListenerMyProgramController@update
     */
    public function マイ番組を更新できる()
    {
        $this->postJson('api/listener_my_programs', ['name' => 'テストマイ番組1', 'email' => 'test1@example.com']);
        $listener_my_program = ListenerMyProgram::first();

        $response = $this->putJson('api/listener_my_programs/' . $listener_my_program->id, ['listener_id' => $this->listener->id, 'name' => 'テストマイ番組更新', 'email' => 'testupdate@example.com']);
        $response->assertStatus(201)
            ->assertJson([
                'message' => 'マイ番組が更新されました。'
            ]);

        $listener_my_program = ListenerMyProgram::first();
        $this->assertEquals('テストマイ番組更新', $listener_my_program->name);
        $this->assertEquals('testupdate@example.com', $listener_my_program->email);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\ListenerMyProgramController@update
     */
    public function マイ番組更新に失敗する（名前関連）()
    {
        $this->postJson('api/listener_my_programs', ['name' => 'テストマイ番組1', 'email' => 'test1@example.com']);
        $listener_my_program = ListenerMyProgram::first();

        $response1 = $this->putJson('api/listener_my_programs/' . $listener_my_program->id, ['listener_id' => $this->listener->id, 'name' => '', 'email' => 'testupdate@example.com']);
        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => [
                    '番組名を入力してください。'
                ]
            ]);

        $response2 = $this->putJson('api/listener_my_programs/' . $listener_my_program->id, ['listener_id' => $this->listener->id, 'name' => str_repeat('あ', 151), 'email' => 'testupdate@example.com']);
        $response2->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => [
                    '番組名は150文字以下で入力してください。'
                ]
            ]);

        $listener_my_program = ListenerMyProgram::first();
        $this->assertEquals('テストマイ番組1', $listener_my_program->name);
        $this->assertEquals('test1@example.com', $listener_my_program->email);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\ListenerMyProgramController@update
     */
    public function マイ番組更新に失敗する（メールアドレス関連）()
    {
        $this->postJson('api/listener_my_programs', ['name' => 'テストマイ番組1', 'email' => 'test1@example.com']);
        $this->postJson('api/listener_my_programs', ['name' => 'テストマイ番組1', 'email' => 'test2@example.com']);
        $listener_my_program = ListenerMyProgram::first();

        $response1 = $this->putJson('api/listener_my_programs/' . $listener_my_program->id, ['listener_id' => $this->listener->id, 'name' => 'テストマイ番組1', 'email' => '']);
        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => [
                    '番組メールアドレスを入力してください。'
                ]
            ]);

        $listener_my_program = ListenerMyProgram::first();
        $this->assertEquals('テストマイ番組1', $listener_my_program->name);
        $this->assertEquals('test1@example.com', $listener_my_program->email);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\ListenerMyProgramController@destory
     */
    public function マイ番組を削除できる()
    {
        $this->postJson('api/listener_my_programs', ['name' => 'テストマイ番組1', 'email' => 'test1@example.com']);
        $listener_my_program = ListenerMyProgram::first();

        $response = $this->deleteJson('api/listener_my_programs/' . $listener_my_program->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'マイ番組が削除されました。'
            ]);

        $this->assertEquals(0, ListenerMyProgram::count());
    }
}
