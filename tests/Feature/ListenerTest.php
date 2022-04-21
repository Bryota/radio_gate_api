<?php

namespace Tests\Feature;

use App\DataProviders\Models\Listener;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListenerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * App\Http\Controllers\Auth\RegisterController@create
     */
    public function リスナーを登録できる()
    {
        $response = $this->postJson('api/register', [
            'last_name' => 'テスト',
            'first_name' => '太郎',
            'last_name_kana' => 'てすと',
            'first_name_kana' => 'たろう',
            'radio_name' => 'ハイキングベアー',
            'post_code' => '1111111',
            'prefecture' => '東京都',
            'city' => '新宿区',
            'house_number' => '00-000000-000000',
            'tel' => '00-000-0000',
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                "message" => "アカウントが作成されました。",
                "listener" => [
                    'last_name' => 'テスト',
                    'first_name' => '太郎',
                    'last_name_kana' => 'てすと',
                    'first_name_kana' => 'たろう',
                    'radio_name' => 'ハイキングベアー',
                    'post_code' => '1111111',
                    'prefecture' => '東京都',
                    'city' => '新宿区',
                    'house_number' => '00-000000-000000',
                    'tel' => '00-000-0000',
                    'email' => 'test@example.com',
                ]
            ]);

        $listener = Listener::first();
        $this->assertEquals('テスト', $listener->last_name);
        $this->assertEquals('太郎', $listener->first_name);
        $this->assertEquals('てすと', $listener->last_name_kana);
        $this->assertEquals('たろう', $listener->first_name_kana);
        $this->assertEquals('ハイキングベアー', $listener->radio_name);
        $this->assertEquals('1111111', $listener->post_code);
        $this->assertEquals('東京都', $listener->prefecture);
        $this->assertEquals('新宿区', $listener->city);
        $this->assertEquals('00-000000-000000', $listener->house_number);
        $this->assertEquals('00-000-0000', $listener->tel);
        $this->assertEquals('test@example.com', $listener->email);
    }

    /**
     * @test
     * App\Http\Controllers\Auth\RegisterController@create
     */
    public function リスナー登録に失敗する（メール関連）()
    {
        $response = $this->postJson('api/register', [
            'email' => '',
            'password' => 'password123'
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => [
                    'メールアドレスを入力してください。'
                ]
            ]);

        $response = $this->postJson('api/register', [
            'email' => 'testtest',
            'password' => 'password123'
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => [
                    'メールアドレスは正しい形式で入力してください。'
                ]
            ]);

        $this->postJson('api/register', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);
        $response = $this->postJson('api/register', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => [
                    'このメールアドレスは既に使用されています。'
                ]
            ]);

        $this->assertEquals(1, Listener::count());
    }

    /**
     * @test
     * App\Http\Controllers\Auth\RegisterController@create
     */
    public function リスナー登録に失敗する（パスワード関連）()
    {
        $response = $this->postJson('api/register', [
            'email' => 'test@example.com',
            'password' => ''
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'password' => [
                    'パスワードを入力してください。'
                ]
            ]);

        $this->assertEquals(0, Listener::count());
    }

    /**
     * @test
     * App\Http\Controllers\Auth\RegisterController@create
     */
    public function リスナー登録に失敗する（郵便番号関連）()
    {
        $response = $this->postJson('api/register', [
            'post_code' => 'testtest',
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'post_code' => [
                    '郵便番号は数字で入力してください。'
                ]
            ]);

        $this->assertEquals(0, Listener::count());
    }

    /**
     * @test
     * App\Http\Controllers\MessageTemplateController@show
     */
    public function リスナー情報を取得できる()
    {
        $listener = $this->loginAsListener();

        $response = $this->getJson('api/listener');

        $response->assertStatus(200)
            ->assertJson([
                'listener' => [
                    'last_name' => $listener->last_name,
                    'first_name' => $listener->first_name,
                    'last_name_kana' => $listener->last_name_kana,
                    'first_name_kana' => $listener->first_name_kana,
                    'radio_name' => $listener->radio_name,
                    'post_code' => $listener->post_code,
                    'prefecture' => $listener->prefecture,
                    'city' => $listener->city,
                    'house_number' => $listener->house_number,
                    'tel' => $listener->tel,
                    'email' => $listener->email,
                ]
            ]);
    }
}
