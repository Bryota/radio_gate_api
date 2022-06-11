<?php

namespace Tests\Feature;

use App\DataProviders\Models\RequestFunctionRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestFunctionRequestTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setUp();
        $this->listener = $this->loginAsListener();
    }

    /**
     * @test
     * App\Http\Controllers\Listener\RequestFunctionRequestController@store
     */
    public function リクエスト機能申請が作成できる()
    {
        $response = $this->postJson('api/request_function_requests', ['name' => 'テスト機能', 'detail' => str_repeat('いい機能ですね', 100), 'listener_id' => $this->listener->id]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'リクエスト機能申請が作成されました。'
            ]);

        $request_function_request = RequestFunctionRequest::first();
        $this->assertEquals('テスト機能', $request_function_request->name);
        $this->assertEquals(str_repeat('いい機能ですね', 100), $request_function_request->detail);
        $this->assertEquals($this->listener->id, $request_function_request->listener_id);
        $this->assertEquals($this->listener->name, $request_function_request->Listener->name);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\RequestFunctionRequestController@store
     */
    public function リクエスト機能申請の作成に失敗する（名前関連）()
    {
        $response1 = $this->postJson('api/request_function_requests', ['name' => '', 'detail' => 'テスト機能ですね', 'listener_id' => $this->listener->id]);
        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => [
                    '機能名を入力してください。'
                ]
            ]);

        $response2 = $this->postJson('api/request_function_requests', ['name' => str_repeat('あ', 151), 'detail' => 'テスト機能ですね', 'listener_id' => $this->listener->id]);
        $response2->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => [
                    '機能名は150文字以下で入力してください。'
                ]
            ]);

        $this->assertEquals(0, RequestFunctionRequest::count());
    }

    /**
     * @test
     * App\Http\Controllers\Listener\RequestFunctionRequestController@store
     */
    public function リクエスト機能作成に失敗する（本文関連）()
    {
        $response1 = $this->postJson('api/request_function_requests', ['name' => 'テスト機能', 'detail' => '', 'listener_id' => $this->listener->id]);
        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'detail' => [
                    '機能詳細を入力してください。'
                ]
            ]);

        $this->assertEquals(0, RequestFunctionRequest::count());
    }
}
