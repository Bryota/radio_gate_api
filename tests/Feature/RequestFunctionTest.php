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
        $this->loginAsAdmin();
        $this->postJson('api/admin/request-functions', ['name' => 'テスト機能1', 'detail' => str_repeat('いい機能ですね', 100), 'is_open' => true]);
        $this->postJson('api/admin/request-functions', ['name' => 'テスト機能2', 'detail' => str_repeat('本当にいい機能ですね', 100), 'is_open' => true]);
        $this->postJson('api/admin/request-functions', ['name' => 'テスト機能3', 'detail' => str_repeat('いい機能ですね', 100), 'is_open' => false]);
        $this->postJson('api/admin/request-functions', ['name' => 'テスト機能4', 'detail' => str_repeat('本当にいい機能ですね', 100), 'is_open' => false]);

        $response = $this->getJson('api/request-functions');

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
        $this->loginAsAdmin();
        $this->postJson('api/admin/request-functions', ['name' => 'テスト機能1', 'detail' => str_repeat('いい機能ですね', 100), 'is_open' => true]);

        $request_function = RequestFunction::first();

        $response = $this->getJson('api/request-functions/' . $request_function->id);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'テスト機能1'])
            ->assertJsonFragment(['detail' => str_repeat('いい機能ですね', 100)]);
    }

    /**
     * @test
     * App\Http\Controllers\Admin\RequestFunctionController@store
     */
    public function 管理者がリクエスト機能が作成できる()
    {
        $this->loginAsAdmin();
        $response = $this->postJson('api/admin/request-functions', ['name' => 'テスト機能', 'detail' => str_repeat('いい機能ですね', 100), 'is_open' => true]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'リクエスト機能が作成されました。'
            ]);

        $request_function = RequestFunction::first();
        $this->assertEquals('テスト機能', $request_function->name);
        $this->assertEquals(str_repeat('いい機能ですね', 100), $request_function->detail);
    }

    /**
     * @test
     * App\Http\Controllers\Admin\RequestFunctionController@store
     */
    public function リクエスト機能に失敗する（名前関連）()
    {
        $this->loginAsAdmin();
        $response1 = $this->postJson('api/admin/request-functions', ['name' => '', 'detail' => 'テスト機能ですね', 'is_open' => true]);
        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => [
                    '機能名を入力してください。'
                ]
            ]);

        $response2 = $this->postJson('api/admin/request-functions', ['name' => str_repeat('あ', 151), 'detail' => 'テスト機能ですね', 'is_open' => true]);
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
     * App\Http\Controllers\Admin\RequestFunctionController@store
     */
    public function リクエスト機能作成に失敗する（本文関連）()
    {
        $this->loginAsAdmin();
        $response1 = $this->postJson('api/admin/request-functions', ['name' => 'テスト機能', 'detail' => '', 'is_open' => true]);
        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'detail' => [
                    '機能詳細を入力してください。'
                ]
            ]);

        $this->assertEquals(0, RequestFunction::count());
    }

    /**
     * @test
     * App\Http\Controllers\Listener\RequestFunctionController@submitListenerPoint
     */
    public function リスナーがリクエスト機能に投票できる()
    {
        $this->loginAsAdmin();
        $this->postJson('api/admin/request-functions', ['name' => 'テスト機能1', 'detail' => str_repeat('いい機能ですね', 100), 'is_open' => true]);

        $request_function = RequestFunction::first();
        $this->assertEquals(0, $request_function->point);

        $response = $this->postJson('api/request-functions/' . $request_function->id . '/point', ['point' => 1]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => '投票が完了しました。'
            ]);

        $request_function = RequestFunction::first();
        $this->assertEquals(1, $request_function->point);
    }
}
