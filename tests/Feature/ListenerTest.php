<?php

namespace Tests\Feature;

use App\DataProviders\Models\Listener;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ListenerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * App\Http\Controllers\Listener\Auth\RegisterController@create
     */
    // TODO: sanctumを含めたテストが可能か検討
    // public function リスナーを登録できる()
    // {
    //     $response = $this->postJson('api/register', [
    //         'last_name' => 'テスト',
    //         'first_name' => '太郎',
    //         'last_name_kana' => 'てすと',
    //         'first_name_kana' => 'たろう',
    //         'radio_name' => 'ハイキングベアー',
    //         'post_code' => '1111111',
    //         'prefecture' => '東京都',
    //         'city' => '新宿区',
    //         'house_number' => '00-000000-000000',
    //         'building' => '建物',
    //         'room_number' => '100',
    //         'tel' => '00-000-0000',
    //         'email' => 'test@example.com',
    //         'password' => 'password123'
    //     ]);

    //     $response->assertStatus(200)
    //         ->assertJson([
    //             "message" => "アカウントが作成されました。",
    //             "listener" => [
    //                 'last_name' => 'テスト',
    //                 'first_name' => '太郎',
    //                 'last_name_kana' => 'てすと',
    //                 'first_name_kana' => 'たろう',
    //                 'radio_name' => 'ハイキングベアー',
    //                 'post_code' => '1111111',
    //                 'prefecture' => '東京都',
    //                 'city' => '新宿区',
    //                 'house_number' => '00-000000-000000',
    //                 'building' => '建物',
    //                 'room_number' => '100',
    //                 'tel' => '00-000-0000',
    //                 'email' => 'test@example.com',
    //             ]
    //         ]);

    //     $listener = Listener::first();
    //     $this->assertEquals('テスト', $listener->last_name);
    //     $this->assertEquals('太郎', $listener->first_name);
    //     $this->assertEquals('てすと', $listener->last_name_kana);
    //     $this->assertEquals('たろう', $listener->first_name_kana);
    //     $this->assertEquals('ハイキングベアー', $listener->radio_name);
    //     $this->assertEquals('1111111', $listener->post_code);
    //     $this->assertEquals('東京都', $listener->prefecture);
    //     $this->assertEquals('新宿区', $listener->city);
    //     $this->assertEquals('00-000000-000000', $listener->house_number);
    //     $this->assertEquals('建物', $listener->building);
    //     $this->assertEquals('100', $listener->room_number);
    //     $this->assertEquals('00-000-0000', $listener->tel);
    //     $this->assertEquals('test@example.com', $listener->email);
    // }

    /**
     * @test
     * App\Http\Controllers\Listener\Auth\RegisterController@create
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
     * App\Http\Controllers\Listener\Auth\RegisterController@create
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
     * App\Http\Controllers\Listener\Auth\RegisterController@create
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
     * App\Http\Controllers\Listener\Auth\ListenerController@update
     */
    public function リスナー情報を更新できる()
    {
        $listener = $this->loginAsListener();

        $response = $this->putJson('api/listener', [
            'radio_name' => 'エアーポップ',
            'prefecture' => '東京都',
            'email' => 'testupdate@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'リスナーデータの更新に成功しました。'
            ]);

        $update_listener = Listener::first();
        $this->assertEquals('エアーポップ', $update_listener->radio_name);
        $this->assertEquals('東京都', $update_listener->prefecture);
        $this->assertEquals($listener->email, $update_listener->email);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\Auth\loginController@login
     */
    // TODO: sanctumを含めたテストが可能か検討
    // public function ログインできる()
    // {
    //     $this->postJson('api/register', [
    //         'radio_name' => 'ハイキングベアー',
    //         'email' => 'test@example.com',
    //         'password' => 'password123'
    //     ]);

    //     $response = $this->postJson('api/login', [
    //         'email' => 'test@example.com',
    //         'password' => 'password123'
    //     ]);

    //     $response->assertStatus(200)
    //         ->assertJson([
    //             'listener_info' => [
    //                 'radio_name' => 'ハイキングベアー',
    //                 'email' => 'test@example.com'
    //             ]
    //         ]);
    // }

    /**
     * @test
     * App\Http\Controllers\Listener\Auth\loginController@login
     */
    public function ログインに失敗する（バリデーション関連）()
    {
        $this->postJson('api/register', [
            'radio_name' => 'ハイキングベアー',
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response1 = $this->postJson('api/login', [
            'email' => '',
            'password' => 'password123'
        ]);
        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => [
                    'メールアドレスを入力してください。'
                ]
            ]);

        $response2 = $this->postJson('api/login', [
            'email' => 'test',
            'password' => 'password123'
        ]);
        $response2->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => [
                    'メールアドレスは正しい形式で入力してください。'
                ]
            ]);

        $response3 = $this->postJson('api/login', [
            'email' => 'test@example.com',
            'password' => ''
        ]);
        $response3->assertStatus(422)
            ->assertJsonValidationErrors([
                'password' => [
                    'パスワードを入力してください。'
                ]
            ]);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\Auth\loginController@login
     */
    public function ログインに失敗する（認証関連）()
    {
        $this->postJson('api/register', [
            'radio_name' => 'ハイキングベアー',
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response1 = $this->postJson('api/login', [
            'email' => 'testfail@example.com',
            'password' => 'password123'
        ]);
        $response1->assertStatus(500)
            ->assertJson([
                'message' => 'ログインに失敗しました。メールアドレスまたはパスワードが間違えていないかご確認ください。'
            ]);

        $response2 = $this->postJson('api/login', [
            'email' => 'test@example.com',
            'password' => 'password123456'
        ]);
        $response2->assertStatus(500)
            ->assertJson([
                'message' => 'ログインに失敗しました。メールアドレスまたはパスワードが間違えていないかご確認ください。'
            ]);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\Auth\loginController@logout
     */
    // TODO: sanctumを含めたテストが可能か検討
    // public function ログアウトに成功する()
    // {
    //     $this->postJson('api/register', [
    //         'radio_name' => 'ハイキングベアー',
    //         'email' => 'test@example.com',
    //         'password' => 'password123'
    //     ]);

    //     $response = $this->postJson('api/logout', []);
    //     $response->assertStatus(200)
    //         ->assertJson([
    //             'message' => 'ログアウトに成功しました。'
    //         ]);
    // }

    /**
     * @test
     * App\Http\Controllers\Listener\ListenerController@index
     */
    public function リスナー一覧を取得できる()
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
                    'building' => $listener->building,
                    'room_number' => $listener->room_number,
                    'tel' => $listener->tel,
                    'email' => $listener->email,
                ]
            ]);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\ListenerController@show
     */
    public function リスナー情報を取得できる()
    {
        $listener = $this->loginAsListener();
        $this->postJson('api/register', [
            'last_name' => 'テスト',
            'first_name' => '太郎',
            'last_name_kana' => 'てすと',
            'first_name_kana' => 'たろう',
            'radio_name' => 'ハイキングベアー',
            'post_code' => '1111111',
            'prefecture' => '東京都',
            'city' => '新宿区',
            'house_number' => '00-000000-000000',
            'building' => '建物',
            'room_number' => '100',
            'tel' => '00-000-0000',
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response = $this->getJson('api/listeners');

        $response->assertStatus(200)
            ->assertJsonFragment(['email' => 'test@example.com'])
            ->assertJsonFragment(['email' => $listener->email])
            ->assertJsonFragment(['last_name' => 'テスト'])
            ->assertJsonFragment(['last_name' => $listener->last_name])
            ->assertJsonFragment(['radio_name' => 'ハイキングベアー']);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\ForgotPasswordController@sendResetLinkEmail
     */
    public function パスワード再設定用のメールを送信できる()
    {
        Notification::fake();
        Mail::fake();

        $this->postJson('api/register', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response = $this->postJson('api/forgot_password', [
            'email' => 'test@example.com'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'messege' => 'パスワード再設定用のメールを送信しました。'
            ]);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\ForgotPasswordController@sendResetLinkEmail
     */
    public function パスワード再設定用のメール送信に失敗する()
    {
        Notification::fake();
        Mail::fake();

        $this->postJson('api/register', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response1 = $this->postJson('api/forgot_password', [
            'email' => ''
        ]);
        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => [
                    'メールアドレスを入力してください。'
                ]
            ]);

        $response2 = $this->postJson('api/forgot_password', [
            'email' => 'test'
        ]);
        $response2->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => [
                    'メールアドレスは正しい形式で入力してください。'
                ]
            ]);

        $response3 = $this->postJson('api/forgot_password', [
            'email' => 'test2@example.com'
        ]);
        $response3->assertStatus(500)
            ->assertJson([
                'messege' => 'パスワード再設定用のメールの送信に失敗しました。'
            ]);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\PasswordResetController@resetPassword
     */
    public function パスワードを更新できる()
    {
        Notification::fake();

        $listener = Listener::factory()->create();
        $token = $this->passwordRequest($listener);
        $this->assertTrue(Hash::check('password123', $listener->password));

        $new_password = 'password1234567';
        $params = [
            'email' => $listener->email,
            'token' => $token,
            'password' => $new_password,
            'password_confirmation' => $new_password
        ];

        $response = $this->put('api/listener/password', $params);

        $response->assertStatus(200)
            ->assertJson([
                'messege' => 'パスワード変更に成功しました。'
            ]);

        $listener = Listener::first();
        $this->assertTrue(Hash::check($new_password, $listener->password));
    }

    /**
     * @test
     * App\Http\Controllers\Listener\PasswordResetController@resetPassword
     */
    public function パスワード更新に失敗する()
    {
        Notification::fake();

        $listener = Listener::factory()->create();
        $token = $this->passwordRequest($listener);
        $this->assertTrue(Hash::check('password123', $listener->password));

        $new_password = 'password1234567';
        $params = [
            'email' => $listener->email,
            'token' => 'sampletoken',
            'password' => $new_password,
            'password_confirmation' => $new_password
        ];
        $response = $this->put('api/listener/password', $params);

        $response->assertStatus(500)
            ->assertJson([
                'messege' => 'パスワード変更に失敗しました。'
            ]);

        $listener = Listener::first();
        $this->assertFalse(Hash::check($new_password, $listener->password));
    }

    /**
     * @test
     * App\Http\Controllers\Listener\ListenerController@destroy
     */
    public function アカウントを削除できる()
    {
        $listener = $this->loginAsListener();

        $response = $this->deleteJson('api/listener');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'アカウントが削除されました。'
            ]);

        $this->assertEquals(0, Listener::count());
    }

    /**
     * トークンを取得
     * 
     * @param Listener $listener リスナーインスタンス
     * @return string $token トークン
     */
    private function passwordRequest(Listener $listener)
    {
        // パスワードリセットをリクエスト（トークンを作成・取得するため）
        $this->post('api/forgot_password', [
            'email' => $listener->email
        ]);

        // トークンを取得する
        $token = '';

        Notification::assertSentTo(
            $listener,
            ResetPasswordNotification::class,
            function ($notification, $channels) use ($listener, &$token) {
                $token = $notification->token;
                return true;
            }
        );
        return $token;
    }
}
