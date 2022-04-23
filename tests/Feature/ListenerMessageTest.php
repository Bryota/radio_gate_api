<?php

namespace Tests\Feature;

use App\DataProviders\Models\ListenerMessage;
use App\DataProviders\Models\RadioStation;
use App\DataProviders\Models\RadioProgram;
use App\DataProviders\Models\ProgramCorner;
use App\DataProviders\Models\ListenerMyProgram;
use App\DataProviders\Models\MyProgramCorner;
use App\Mail\ListenerMessageMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ListenerMessageTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setUp();

        $this->listener = $this->loginAsListener();

        $this->postJson('api/radio_stations', ['name' => 'テスト局']);
        $this->radio_station = RadioStation::first();
        $this->postJson('api/radio_programs', ['radio_station_id' => $this->radio_station->id, 'name' => 'テスト番組', 'email' => 'test@example.com']);
        $this->radio_program = RadioProgram::first();
        $this->postJson('api/program_corners', ['radio_program_id' => $this->radio_program->id, 'name' => '死んでもやめんじゃねーぞ']);
        $this->program_corner = ProgramCorner::first();

        $this->postJson('api/listener_my_programs', ['program_name' => 'テストマイ番組', 'email' => 'test@example.com']);
        $this->listener_my_program = ListenerMyProgram::first();
        $this->postJson('api/my_program_corners', ['listener_my_program_id' => $this->listener_my_program->id, 'name' => 'BBSリクエスト', 'listener_id' => $this->listener->id]);
        $this->my_program_corner = MyProgramCorner::first();
    }

    /**
     * @test
     * App\Http\Controllers\ListenerMessageController@store
     */
    public function ラジオ番組にメッセージが投稿できる（コーナー指定）()
    {
        Mail::fake();

        $response = $this->postJson('api/listener_messages', [
            'radio_program_id' => $this->radio_program->id,
            'program_corner_id' => $this->program_corner->id,
            'listener_id' => $this->listener->id,
            'content' => 'こんにちは。こんばんは。',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'メッセージが投稿されました。'
            ]);

        $listener_message = ListenerMessage::first();
        $this->assertEquals($this->radio_program->id, $listener_message->radio_program_id);
        $this->assertEquals($this->program_corner->id, $listener_message->program_corner_id);
        $this->assertEquals($this->listener->id, $listener_message->listener_id);
        $this->assertEquals('こんにちは。こんばんは。', $listener_message->content);
        $this->assertEquals('テスト番組', $listener_message->RadioProgram->name);
        $this->assertEquals('死んでもやめんじゃねーぞ', $listener_message->ProgramCorner->name);

        Mail::assertSent(function (ListenerMessageMail $mail) {
            $mail->build();
            return $mail->hasTo($this->radio_program->email) &&
                $mail->subject == $this->program_corner->name;
        });
    }

    /**
     * @test
     * App\Http\Controllers\ListenerMessageController@store
     */
    public function ラジオ番組にメッセージが投稿できる（コーナー指定なし）()
    {
        Mail::fake();

        $response = $this->postJson('api/listener_messages', [
            'radio_program_id' => $this->radio_program->id,
            'listener_id' => $this->listener->id,
            'subject' => 'ふつおた',
            'content' => 'こんにちは。こんばんは。',
            'radio_name' => 'ハイキングベアー'
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'メッセージが投稿されました。'
            ]);

        $listener_message = ListenerMessage::first();
        $this->assertEquals($this->radio_program->id, $listener_message->radio_program_id);
        $this->assertEquals('ふつおた', $listener_message->subject);
        $this->assertEquals($this->listener->id, $listener_message->listener_id);
        $this->assertEquals('こんにちは。こんばんは。', $listener_message->content);
        $this->assertEquals('テスト番組', $listener_message->RadioProgram->name);

        Mail::assertSent(function (ListenerMessageMail $mail) {
            $mail->build();
            return $mail->hasTo($this->radio_program->email) &&
                $mail->subject == 'ふつおた';
        });
    }

    /**
     * @test
     * App\Http\Controllers\ListenerMessageController@store
     */
    public function マイラジオ番組にメッセージが投稿できる（コーナー指定）()
    {
        Mail::fake();

        $response = $this->postJson('api/listener_messages', [
            'listener_my_program_id' => $this->listener_my_program->id,
            'my_program_corner_id' => $this->my_program_corner->id,
            'listener_id' => $this->listener->id,
            'content' => 'こんにちは。こんばんは。',
            'radio_name' => 'ハイキングベアー'
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'メッセージが投稿されました。'
            ]);

        $listener_message = ListenerMessage::first();
        $this->assertEquals($this->listener_my_program->id, $listener_message->listener_my_program_id);
        $this->assertEquals($this->my_program_corner->id, $listener_message->my_program_corner_id);
        $this->assertEquals($this->listener->id, $listener_message->listener_id);
        $this->assertEquals('こんにちは。こんばんは。', $listener_message->content);
        $this->assertEquals('テストマイ番組', $listener_message->ListenerMyProgram->program_name);
        $this->assertEquals('BBSリクエスト', $listener_message->MyProgramCorner->name);

        Mail::assertSent(function (ListenerMessageMail $mail) {
            $mail->build();
            return $mail->hasTo($this->listener_my_program->email) &&
                $mail->subject == $this->my_program_corner->name;
        });
    }

    /**
     * @test
     * App\Http\Controllers\ListenerMessageController@store
     */
    public function マイラジオ番組にメッセージが投稿できる（コーナー指定なし）()
    {
        Mail::fake();

        $response = $this->postJson('api/listener_messages', [
            'listener_my_program_id' => $this->listener_my_program->id,
            'listener_id' => $this->listener->id,
            'subject' => 'ふつおた',
            'content' => 'こんにちは。こんばんは。',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'メッセージが投稿されました。'
            ]);

        $listener_message = ListenerMessage::first();
        $this->assertEquals($this->listener_my_program->id, $listener_message->listener_my_program_id);
        $this->assertEquals($this->listener->id, $listener_message->listener_id);
        $this->assertEquals('こんにちは。こんばんは。', $listener_message->content);
        $this->assertEquals('テストマイ番組', $listener_message->ListenerMyProgram->program_name);

        Mail::assertSent(function (ListenerMessageMail $mail) {
            $mail->build();
            return $mail->hasTo($this->listener_my_program->email) &&
                $mail->subject == 'ふつおた';
        });
    }

    /**
     * @test
     * App\Http\Controllers\ListenerMessageController@store
     */
    public function ラジオ番組へのメッセージ投稿に失敗する（番組関連）()
    {
        Mail::fake();

        $response = $this->postJson('api/listener_messages', [
            'my_program_corner_id' => $this->my_program_corner->id,
            'listener_id' => $this->listener->id,
            'content' => 'こんにちは。こんばんは。',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'listener_my_program_id' => [
                    '番組を選択してください。'
                ]
            ]);

        $this->assertEquals(0, ListenerMessage::count());

        Mail::assertNotSent(ListenerMessageMail::class);
    }

    /**
     * @test
     * App\Http\Controllers\ListenerMessageController@store
     */
    public function ラジオ番組へのメッセージ投稿に失敗する（コーナー関連）()
    {
        Mail::fake();

        $response1 = $this->postJson('api/listener_messages', [
            'radio_program_id' => $this->radio_program->id,
            'listener_id' => $this->listener->id,
            'content' => 'こんにちは。こんばんは。',
        ]);

        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'subject' => [
                    'コーナーを選択するか件名を入力してください。'
                ]
            ]);

        $response2 = $this->postJson('api/listener_messages', [
            'listener_my_program_id' => $this->listener_my_program->id,
            'listener_id' => $this->listener->id,
            'content' => 'こんにちは。こんばんは。',
        ]);

        $response2->assertStatus(422)
            ->assertJsonValidationErrors([
                'subject' => [
                    'コーナーを選択するか件名を入力してください。'
                ]
            ]);

        $this->assertEquals(0, ListenerMessage::count());

        Mail::assertNotSent(ListenerMessageMail::class);
    }

    /**
     * @test
     * App\Http\Controllers\ListenerMessageController@store
     */
    public function ラジオ番組へのメッセージ投稿に失敗する（本文関連）()
    {
        Mail::fake();

        $response1 = $this->postJson('api/listener_messages', [
            'radio_program_id' => $this->radio_program->id,
            'program_corner_id' => $this->program_corner->id,
            'listener_id' => $this->listener->id,
        ]);

        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'content' => [
                    '本文を入力してください。'
                ]
            ]);

        $response2 = $this->postJson('api/listener_messages', [
            'radio_program_id' => $this->radio_program->id,
            'listener_id' => $this->listener->id,
            'subject' => 'ふつおた',
        ]);

        $response2->assertStatus(422)
            ->assertJsonValidationErrors([
                'content' => [
                    '本文を入力してください。'
                ]
            ]);

        $response3 = $this->postJson('api/listener_messages', [
            'listener_my_program_id' => $this->listener_my_program->id,
            'my_program_corner_id' => $this->my_program_corner->id,
            'listener_id' => $this->listener->id,
        ]);

        $response3->assertStatus(422)
            ->assertJsonValidationErrors([
                'content' => [
                    '本文を入力してください。'
                ]
            ]);

        $response4 = $this->postJson('api/listener_messages', [
            'listener_my_program_id' => $this->listener_my_program->id,
            'listener_id' => $this->listener->id,
            'subject' => 'ふつおた',
        ]);

        $response4->assertStatus(422)
            ->assertJsonValidationErrors([
                'content' => [
                    '本文を入力してください。'
                ]
            ]);

        $this->assertEquals(0, ListenerMessage::count());

        Mail::assertNotSent(ListenerMessageMail::class);
    }

    /**
     * @test
     * App\Http\Controllers\ListenerMessageController@store
     */
    public function 投稿メッセージの本文チェック()
    {
        $mailable = new ListenerMessageMail(
            '死んでもやめんじゃねーぞ',
            'テストユーザー',
            'てすとゆーざー',
            'ハイキングベアー',
            1111111,
            '東京都',
            '新宿区',
            '000-0',
            '000-0000-0000',
            'test@example.com',
            'こんにちは、これはテストです'
        );

        $mailable->assertSeeInOrderInHtml([
            '東京都',
            'ハイキングベアー',
            'こんにちは、これはテストです',
            1111111,
            '新宿区',
            '000-0',
            'テストユーザー',
            'てすとゆーざー',
            '000-0000-0000',
            'test@example.com',
        ]);
    }
}
