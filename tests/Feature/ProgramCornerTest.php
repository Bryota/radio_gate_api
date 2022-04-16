<?php

namespace Tests\Feature;

use App\DataProviders\Models\RadioStation;
use App\DataProviders\Models\RadioProgram;
use App\DataProviders\Models\ProgramCorner;
use App\DataProviders\Models\Listener;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProgramCornerTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setUp();

        $this->loginAsListener();

        $this->postJson('api/radio_stations', ['name' => 'テスト局']);
        $this->radio_station = RadioStation::first();

        $this->postJson('api/radio_programs', ['radio_station_id' => $this->radio_station->id, 'name' => 'テスト番組', 'email' => 'test@example.com']);
        $this->radio_program = RadioProgram::first();
    }

    /**
     * @test
     * App\Http\Controllers\ProgramCornerController@store
     */
    public function 番組コーナーを作成できる()
    {
        $response = $this->postJson('api/program_corners', ['radio_program_id' => $this->radio_program->id, 'name' => '死んでもやめんじゃねーぞ']);

        $response->assertStatus(201)
            ->assertJson([
                'message' => '番組コーナーが作成されました。'
            ]);

        $program_corner = ProgramCorner::first();
        $this->assertEquals('死んでもやめんじゃねーぞ', $program_corner->name);
        $this->assertEquals($this->radio_program->id, $program_corner->radio_program_id);
        $this->assertEquals('テスト番組', $program_corner->radioProgram->name);
    }

    /**
     * @test
     * App\Http\Controllers\ProgramCornerController@store
     */
    public function 番組コーナー作成に失敗する()
    {
        $response1 = $this->postJson('api/program_corners', ['radio_program_id' => $this->radio_program->id, 'name' => '']);
        $response1->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        '番組コーナー名を入力してください。'
                    ]
                ]
            ]);

        $response2 = $this->postJson('api/program_corners', ['radio_program_id' => $this->radio_program->id, 'name' => str_repeat('あ', 151)]);
        $response2->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        '番組コーナー名は150文字以下で入力してください。'
                    ]
                ]
            ]);

        $this->assertEquals(0, ProgramCorner::count());
    }

    /**
     * @test
     * App\Http\Controllers\ProgramCornerController@index
     */
    public function 番組コーナー一覧を取得できる()
    {
        $response = $this->postJson('api/program_corners', ['radio_program_id' => $this->radio_program->id, 'name' => '死んでもやめんじゃねーぞ']);
        $response = $this->postJson('api/program_corners', ['radio_program_id' => $this->radio_program->id, 'name' => '企画書はラブレター']);


        $response = $this->getJson('api/program_corners?radio_program=' . $this->radio_program->id);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => '死んでもやめんじゃねーぞ'])
            ->assertJsonFragment(['name' => '企画書はラブレター']);

        $response = $this->getJson('api/program_corners?radio_program=' . 100000);
        $response->assertStatus(200)
            ->assertJsonMissing(['name' => '死んでもやめんじゃねーぞ'])
            ->assertJsonMissing(['name' => '企画書はラブレター']);
    }

    /**
     * @test
     * App\Http\Controllers\ProgramCornerController@update
     */
    public function 番組コーナーを更新できる()
    {
        $response = $this->postJson('api/program_corners', ['radio_program_id' => $this->radio_program->id, 'name' => '死んでもやめんじゃねーぞ']);
        $program_corner = ProgramCorner::first();

        $response = $this->putJson('api/program_corners/' . $program_corner->id, ['radio_program_id' => $this->radio_program->id, 'name' => '東洋一のツッコミ']);
        $response->assertStatus(201)
            ->assertJson([
                'message' => '番組コーナーが更新されました。'
            ]);

        $program_corner = ProgramCorner::first();
        $this->assertEquals('東洋一のツッコミ', $program_corner->name);
    }

    /**
     * @test
     * App\Http\Controllers\ProgramCornerController@update
     */
    public function 番組コーナーの更新に失敗する()
    {
        $response = $this->postJson('api/program_corners', ['radio_program_id' => $this->radio_program->id, 'name' => '死んでもやめんじゃねーぞ']);
        $program_corner = ProgramCorner::first();

        $response1 = $this->putJson('api/program_corners/' . $program_corner->id, ['radio_program_id' => $this->radio_program->id, 'name' => '']);

        $response1->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        '番組コーナー名を入力してください。'
                    ]
                ]
            ]);

        $response2 = $this->putJson('api/program_corners/' . $program_corner->id, ['radio_program_id' => $this->radio_program->id, 'name' => str_repeat('あ', 151)]);

        $response2->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        '番組コーナー名は150文字以下で入力してください。'
                    ]
                ]
            ]);

        $program_corner = ProgramCorner::first();

        $this->assertEquals('死んでもやめんじゃねーぞ', $program_corner->name);
    }

    /**
     * @test
     * App\Http\Controllers\ProgramCornerController@destroy
     */
    public function 番組コーナーを削除できる()
    {
        $this->postJson('api/program_corners', ['radio_program_id' => $this->radio_program->id, 'name' => '死んでもやめんじゃねーぞ']);
        $program_corner = ProgramCorner::first();

        $response = $this->deleteJson('api/program_corners/' . $program_corner->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => '番組コーナーが削除されました。'
            ]);

        $this->assertEquals(0, ProgramCorner::count());
    }
}
