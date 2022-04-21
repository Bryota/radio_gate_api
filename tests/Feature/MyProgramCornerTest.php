<?php

namespace Tests\Feature;

use App\DataProviders\Models\ListenerMyProgram;
use App\DataProviders\Models\MyProgramCorner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MyProgramCornerTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setUp();

        $this->listener = $this->loginAsListener();

        $this->postJson('api/listener_my_programs', ['program_name' => 'テストマイ番組', 'email' => 'test@example.com']);
        $this->listener_my_program = ListenerMyProgram::first();
    }

    /**
     * @test
     * App\Http\Controllers\MyProgramCornerController@store
     */
    public function 番組コーナーを作成できる()
    {
        $response = $this->postJson('api/my_program_corners', ['listener_my_program_id' => $this->listener_my_program->id, 'name' => 'BBSリクエスト', 'listener_id' => $this->listener->id]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'マイ番組コーナーが作成されました。'
            ]);

        $my_program_corner = MyProgramCorner::first();
        $this->assertEquals('BBSリクエスト', $my_program_corner->name);
        $this->assertEquals($this->listener_my_program->id, $my_program_corner->listener_my_program_id);
        $this->assertEquals('テストマイ番組', $my_program_corner->ListenerMyProgram->program_name);
    }

    /**
     * @test
     * App\Http\Controllers\MyProgramCornerController@store
     */
    public function 番組コーナー作に失敗する（名前関連）()
    {
        $response1 = $this->postJson('api/my_program_corners', ['listener_my_program_id' => $this->listener_my_program->id, 'name' => '', 'listener_id' => $this->listener->id]);
        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => [
                    'マイ番組コーナー名を入力してください。'
                ]
            ]);

        $response2 = $this->postJson('api/my_program_corners', ['listener_my_program_id' => $this->listener_my_program->id, 'name' => str_repeat('あ', 101), 'listener_id' => $this->listener->id]);
        $response2->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => [
                    '番組コーナー名は100文字以下で入力してください。'
                ]
            ]);

        $this->assertEquals(0, MyProgramCorner::count());
    }

    /**
     * @test
     * App\Http\Controllers\MyProgramCornerController@store
     */
    public function 番組コーナー作に失敗する（ログイン関連）()
    {
        $response1 = $this->postJson('api/my_program_corners', ['listener_my_program_id' => $this->listener_my_program->id, 'name' => 'BBSリクエスト', 'listener_id' => 11111111111111]);
        $response1->assertStatus(409)
            ->assertJson([
                'message' => 'ログインし直してください。'
            ]);

        $this->assertEquals(0, MyProgramCorner::count());
    }

    /**
     * @test
     * App\Http\Controllers\MyProgramCornerController@index
     */
    public function 番組コーナー一覧を取得できる()
    {
        $this->postJson('api/my_program_corners', ['listener_my_program_id' => $this->listener_my_program->id, 'name' => 'BBSリクエスト', 'listener_id' => $this->listener->id]);
        $this->postJson('api/my_program_corners', ['listener_my_program_id' => $this->listener_my_program->id, 'name' => 'ザワニュー', 'listener_id' => $this->listener->id]);

        $response = $this->getJson('api/my_program_corners?listener_my_program=' . $this->listener_my_program->id . '&listener=' . $this->listener->id);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'BBSリクエスト'])
            ->assertJsonFragment(['name' => 'ザワニュー']);

        $response = $this->getJson('api/my_program_corners?listener_my_program=' . 100000 . '&listener=' . $this->listener->id);
        $response->assertStatus(200)
            ->assertJsonMissing(['name' => 'BBSリクエスト'])
            ->assertJsonMissing(['name' => 'ザワニュー']);

        $response = $this->getJson('api/my_program_corners?listener_my_program=' . $this->listener_my_program->id . '&listener=111111');
        $response->assertStatus(409)
            ->assertJson([
                'message' => 'ログインし直してください。'
            ]);
    }

    /**
     * @test
     * App\Http\Controllers\MyProgramCornerController@update
     */
    public function 番組コーナーを更新できる()
    {
        $this->postJson('api/my_program_corners', ['listener_my_program_id' => $this->listener_my_program->id, 'name' => 'BBSリクエスト', 'listener_id' => $this->listener->id]);
        $my_program_corner = MyProgramCorner::first();

        $response = $this->putJson('api/my_program_corners/' . $my_program_corner->id, ['listener_my_program_id' => $this->listener_my_program->id, 'name' => 'ザワニュー', 'listener_id' => $this->listener->id]);
        $response->assertStatus(201)
            ->assertJson([
                'message' => 'マイ番組コーナーが更新されました。'
            ]);

        $my_program_corner = MyProgramCorner::first();
        $this->assertEquals('ザワニュー', $my_program_corner->name);
    }

    /**
     * @test
     * App\Http\Controllers\MyProgramCornerController@update
     */
    public function 番組コーナー更新に失敗する（名前関連）()
    {
        $this->postJson('api/my_program_corners', ['listener_my_program_id' => $this->listener_my_program->id, 'name' => 'BBSリクエスト', 'listener_id' => $this->listener->id]);
        $my_program_corner = MyProgramCorner::first();

        $response1 = $this->putJson('api/my_program_corners/' . $my_program_corner->id, ['listener_my_program_id' => $this->listener_my_program->id, 'name' => '', 'listener_id' => $this->listener->id]);
        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => [
                    'マイ番組コーナー名を入力してください。'
                ]
            ]);

        $response1 = $this->putJson('api/my_program_corners/' . $my_program_corner->id, ['listener_my_program_id' => $this->listener_my_program->id, 'name' => str_repeat('あ', 101), 'listener_id' => $this->listener->id]);
        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => [
                    '番組コーナー名は100文字以下で入力してください。'
                ]
            ]);

        $my_program_corner = MyProgramCorner::first();
        $this->assertEquals('BBSリクエスト', $my_program_corner->name);
    }

    /**
     * @test
     * App\Http\Controllers\MyProgramCornerController@update
     */
    public function 番組コーナー更新に失敗する（ログイン関連）()
    {
        $this->postJson('api/my_program_corners', ['listener_my_program_id' => $this->listener_my_program->id, 'name' => 'BBSリクエスト', 'listener_id' => $this->listener->id]);
        $my_program_corner = MyProgramCorner::first();

        $response = $this->putJson('api/my_program_corners/' . $my_program_corner->id, ['listener_my_program_id' => $this->listener_my_program->id, 'name' => 'ザワニュー', 'listener_id' => 11111111]);
        $response->assertStatus(409)
            ->assertJson([
                'message' => 'ログインし直してください。'
            ]);

        $my_program_corner = MyProgramCorner::first();
        $this->assertEquals('BBSリクエスト', $my_program_corner->name);
    }
}
