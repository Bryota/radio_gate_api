<?php

namespace Tests\Feature;

use App\DataProviders\Models\RequestFunction;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
     * App\Http\Controllers\Listener\RequestFunctionController@index
     */
    public function リクエスト機能一覧を取得できる()
    {
        $this->postJson('api/admin/request_functions', ['name' => 'テスト機能1', 'detail' => str_repeat('いい機能ですね', 100), 'is_open' => true]);
        $this->postJson('api/admin/request_functions', ['name' => 'テスト機能2', 'detail' => str_repeat('本当にいい機能ですね', 100), 'is_open' => true]);
        $this->postJson('api/admin/request_functions', ['name' => 'テスト機能3', 'detail' => str_repeat('いい機能ですね', 100), 'is_open' => false]);
        $this->postJson('api/admin/request_functions', ['name' => 'テスト機能4', 'detail' => str_repeat('本当にいい機能ですね', 100), 'is_open' => false]);

        $response = $this->getJson('api/request_functions');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'テスト機能1'])
            ->assertJsonFragment(['name' => 'テスト機能2'])
            ->assertJsonMissing(['detail' => str_repeat('いい機能ですね', 100)])
            ->assertJsonMissing(['name' => 'テスト機能3'])
            ->assertJsonMissing(['name' => 'テスト機能4']);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\RequestFunctionController@show
     */
    public function 個別のリクエスト機能を取得できる()
    {
        $this->postJson('api/admin/request_functions', ['name' => 'テスト機能1', 'detail' => str_repeat('いい機能ですね', 100), 'is_open' => true]);

        $request_function = RequestFunction::first();

        $response = $this->getJson('api/request_functions/' . $request_function->id);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'テスト機能1'])
            ->assertJsonFragment(['detail' => str_repeat('いい機能ですね', 100)]);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\RequestFunctionController@submitListenerPoint
     */
    public function リスナーがリクエスト機能に投票できる()
    {
        $this->postJson('api/admin/request_functions', ['name' => 'テスト機能1', 'detail' => str_repeat('いい機能ですね', 100), 'is_open' => true]);

        $request_function = RequestFunction::first();
        $this->assertEquals(0, $request_function->point);

        $response = $this->postJson('api/request_functions/submit_point', ['listener_id' => $this->listener->id, 'request_function_id' => $request_function->id, 'point' => 1]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => '投票が完了しました。'
            ]);

        $request_function = RequestFunction::first();
        $this->assertEquals(1, $request_function->point);
    }
}
