<?php

namespace Tests\Feature;

use App\DataProviders\Models\RadioStation;
use App\DataProviders\Models\RadioProgram;
use App\DataProviders\Models\ProgramCorner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProgramCornerTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setUp();
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
}
