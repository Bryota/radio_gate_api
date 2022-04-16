<?php

namespace Tests\Feature;

use App\DataProviders\Models\RadioStation;
use App\DataProviders\Models\RadioProgram;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RadioProgramTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setUp();

        $this->loginAsListener();

        $this->postJson('api/radio_stations', ['name' => 'テスト局']);

        $this->radio_station = RadioStation::first();
    }

    /**
     * @test
     * App\Http\Controllers\RadioProgramController@store
     */
    public function ラジオ番組を追加できる()
    {
        $response = $this->postJson('api/radio_programs', ['radio_station_id' => $this->radio_station->id, 'name' => 'テスト番組', 'email' => 'test@example.com']);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'ラジオ番組が作成されました。'
            ]);

        $radio_program = RadioProgram::first();
        $this->assertEquals('テスト番組', $radio_program->name);
        $this->assertEquals('test@example.com', $radio_program->email);
        $this->assertEquals($this->radio_station->id, $radio_program->radio_station_id);
        $this->assertEquals('テスト局', $radio_program->radioStation->name);
    }

    /**
     * @test
     * App\Http\Controllers\RadioProgramController@store
     */
    public function ラジオ番組作成に失敗する（名前関連）()
    {
        $response1 = $this->postJson('api/radio_programs', ['radio_station_id' => $this->radio_station->id, 'name' => '', 'email' => 'test@example.com']);
        $response1->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        'ラジオ番組名を入力してください。'
                    ]
                ]
            ]);

        $response2 = $this->postJson('api/radio_programs', ['radio_station_id' => $this->radio_station->id, 'name' => str_repeat('あ', 101), 'email' => 'test@example.com']);
        $response2->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        'ラジオ番組名は100文字以下で入力してください。'
                    ]
                ]
            ]);

        $this->assertEquals(0, RadioProgram::count());
    }

    /**
     * @test
     * App\Http\Controllers\RadioProgramController@store
     */
    public function ラジオ番組作成に失敗する（メールアドレス関連）()
    {
        $response1 = $this->postJson('api/radio_programs', ['radio_station_id' => $this->radio_station->id, 'name' => 'テスト番組', 'email' => '']);
        $response1->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'email' => [
                        'メールアドレスを入力してください。'
                    ]
                ]
            ]);

        $this->assertEquals(0, RadioProgram::count());

        $this->postJson('api/radio_programs', ['radio_station_id' => $this->radio_station->id, 'name' => 'テスト番組', 'email' => 'test@example.com']);
        $response2 = $this->postJson('api/radio_programs', ['radio_station_id' => $this->radio_station->id, 'name' => 'テスト番組', 'email' => 'test@example.com']);
        $response2->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'email' => [
                        'メールアドレスが既に使われています。'
                    ]
                ]
            ]);
    }

    /**
     * @test
     * App\Http\Controllers\RadioProgramController@index
     */
    public function ラジオ番組一覧を取得できる()
    {
        $this->postJson('api/radio_programs', ['radio_station_id' => $this->radio_station->id, 'name' => 'テスト番組1', 'email' => 'test1@example.com']);
        $this->postJson('api/radio_programs', ['radio_station_id' => $this->radio_station->id, 'name' => 'テスト番組2', 'email' => 'test2@example.com']);

        $response = $this->getJson('api/radio_programs?radio_station=' . $this->radio_station->id);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'テスト番組1', 'email' => 'test1@example.com'])
            ->assertJsonFragment(['name' => 'テスト番組2', 'email' => 'test2@example.com']);

        $response = $this->getJson('api/radio_programs?radio_station=' . 100000);

        $response->assertStatus(200)
            ->assertJsonMissing(['name' => 'テスト番組1', 'email' => 'test1@example.com'])
            ->assertJsonMissing(['name' => 'テスト番組2', 'email' => 'test2@example.com']);
    }

    /**
     * @test
     * App\Http\Controllers\RadioProgramController@show
     */
    public function 個別のラジオ番組を取得できる()
    {
        $this->postJson('api/radio_programs', ['radio_station_id' => $this->radio_station->id, 'name' => 'テスト番組1', 'email' => 'test1@example.com']);
        $radio_program = RadioProgram::first();

        $response = $this->getJson('api/radio_programs/' . $radio_program->id);

        $response->assertStatus(200)
            ->assertJson([
                'radio_program' => [
                    'name' => 'テスト番組1',
                    'email' => 'test1@example.com'
                ]
            ]);
    }

    /**
     * @test
     * App\Http\Controllers\RadioProgramController@update
     */
    public function ラジオ番組を更新できる()
    {
        $this->postJson('api/radio_programs', ['radio_station_id' => $this->radio_station->id, 'name' => 'テスト番組1', 'email' => 'test1@example.com']);
        $radio_program = RadioProgram::first();

        $response = $this->putJson('api/radio_programs/' . $radio_program->id, ['radio_station_id' => $this->radio_station->id, 'name' => 'テスト番組1更新', 'email' => 'test1update@example.com']);
        $response->assertStatus(201)
            ->assertJson([
                'message' => 'ラジオ番組が更新されました。'
            ]);

        $radio_program = RadioProgram::first();
        $this->assertEquals('テスト番組1更新', $radio_program->name);
        $this->assertEquals('test1update@example.com', $radio_program->email);
    }

    /**
     * @test
     * App\Http\Controllers\RadioProgramController@update
     */
    public function ラジオ番組更新に失敗する（番組名）()
    {
        $this->postJson('api/radio_programs', ['radio_station_id' => $this->radio_station->id, 'name' => 'テスト番組1', 'email' => 'test1@example.com']);
        $radio_program = RadioProgram::first();

        $response1 = $this->putJson('api/radio_programs/' . $radio_program->id, ['radio_station_id' => $this->radio_station->id, 'name' => '', 'email' => 'testupdate@example.com']);
        $response1->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        'ラジオ番組名を入力してください。'
                    ]
                ]
            ]);

        $response2 = $this->putJson('api/radio_programs/' . $radio_program->id, ['radio_station_id' => $this->radio_station->id, 'name' => str_repeat('あ', 101), 'email' => 'testupdate@example.com']);
        $response2->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        'ラジオ番組名は100文字以下で入力してください。'
                    ]
                ]
            ]);

        $radio_program = RadioProgram::first();
        $this->assertEquals('テスト番組1', $radio_program->name);
        $this->assertEquals('test1@example.com', $radio_program->email);
    }

    /**
     * @test
     * App\Http\Controllers\RadioProgramController@update
     */
    public function ラジオ番組更新に失敗する（メールアドレス）()
    {
        $this->postJson('api/radio_programs', ['radio_station_id' => $this->radio_station->id, 'name' => 'テスト番組1', 'email' => 'test1@example.com']);
        $this->postJson('api/radio_programs', ['radio_station_id' => $this->radio_station->id, 'name' => 'テスト番組1', 'email' => 'test2@example.com']);
        $radio_program = RadioProgram::first();

        $response1 = $this->putJson('api/radio_programs/' . $radio_program->id, ['radio_station_id' => $this->radio_station->id, 'name' => 'テスト番組1更新', 'email' => '']);
        $response1->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'email' => [
                        'メールアドレスを入力してください。'
                    ]
                ]
            ]);

        $response2 = $this->putJson('api/radio_programs/' . $radio_program->id, ['radio_station_id' => $this->radio_station->id, 'name' => 'テスト番組1更新', 'email' => 'test2@example.com']);
        $response2->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'email' => [
                        'メールアドレスが既に使われています。'
                    ]
                ]
            ]);

        $radio_program = RadioProgram::first();
        $this->assertEquals('テスト番組1', $radio_program->name);
        $this->assertEquals('test1@example.com', $radio_program->email);
    }

    /**
     * @test
     * App\Http\Controllers\RadioProgramController@destroy
     */
    public function ラジオ局を削除できる()
    {
        $this->postJson('api/radio_programs', ['radio_station_id' => $this->radio_station->id, 'name' => 'テスト番組1', 'email' => 'test1@example.com']);
        $radio_program = RadioProgram::first();

        $response = $this->deleteJson('api/radio_programs/' . $radio_program->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'ラジオ番組が削除されました。'
            ]);

        $this->assertEquals(0, RadioProgram::count());
    }
}
