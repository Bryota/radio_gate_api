<?php

namespace Tests\Feature;

use App\DataProviders\Models\RequestFunction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RequestFunctionTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setUp();

        $this->listener = $this->loginAsListener();
    }

    /**
     * @test
     * App\Http\Controllers\RequestFunctionController@store
     */
    public function リクエスト機能が作成できる()
    {
        $response = $this->postJson('api/request_functions', ['name' => 'テスト機能', 'detail' => str_repeat('いい機能ですね', 100), 'listener_id' => $this->listener->id]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'リクエスト機能が作成されました。'
            ]);

        $request_function = RequestFunction::first();
        $this->assertEquals('テスト機能', $request_function->name);
        $this->assertEquals(str_repeat('いい機能ですね', 100), $request_function->detail);
        $this->assertEquals($this->listener->id, $request_function->listener_id);
        $this->assertEquals($this->listener->name, $request_function->Listener->name);
    }

    /**
     * @test
     * App\Http\Controllers\RequestFunctionController@store
     */
    public function リクエスト機能に失敗する（名前関連）()
    {
        $response1 = $this->postJson('api/request_functions', ['name' => '', 'detail' => 'テスト機能ですね', 'listener_id' => $this->listener->id]);
        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => [
                    '機能名を入力してください。'
                ]
            ]);

        $response2 = $this->postJson('api/request_functions', ['name' => str_repeat('あ', 151), 'detail' => 'テスト機能ですね', 'listener_id' => $this->listener->id]);
        $response2->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => [
                    '機能名は150文字以下で入力してください。'
                ]
            ]);

        $this->assertEquals(0, RequestFunction::count());
    }

    /**
     * @test
     * App\Http\Controllers\RequestFunctionController@store
     */
    public function 投稿テンプレート作成に失敗する（本文関連）()
    {
        $response1 = $this->postJson('api/request_functions', ['name' => 'テスト機能', 'detail' => '', 'listener_id' => $this->listener->id]);
        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'detail' => [
                    '機能詳細を入力してください。'
                ]
            ]);

        $this->assertEquals(0, RequestFunction::count());
    }
}
