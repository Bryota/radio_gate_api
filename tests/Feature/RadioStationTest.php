<?php

namespace Tests\Feature;

use App\DataProviders\Models\RadioStation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RadioStationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * App\Http\Controllers\RadioStationController@store
     */
    public function ラジオ局を作成できる()
    {
        $response = $this->postJson('api/radio_stations', ['name' => 'テスト局']);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'ラジオ局が作成されました。'
            ]);

        $radio_station = RadioStation::first();
        $this->assertEquals('テスト局', $radio_station->name);
    }

    /**
     * @test
     * App\Http\Controllers\RadioStationController@store
     */
    public function ラジオ局作成に失敗する()
    {
        $response1 = $this->postJson('api/radio_stations', ['name' => '']);

        $response1->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        'ラジオ局名を入力してください。'
                    ]
                ]
            ]);

        $response2 = $this->postJson('api/radio_stations', ['name' => str_repeat('あ', 101)]);

        $response2->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        'ラジオ局名は100文字以下で入力してください。'
                    ]
                ]
            ]);

        $this->assertEquals(0, RadioStation::count());
    }

    /**
     * @test
     * App\Http\Controllers\RadioStationController@store
     */
    public function ラジオ局一覧を取得できる()
    {
        $this->postJson('api/radio_stations', ['name' => 'テスト局1']);
        $this->postJson('api/radio_stations', ['name' => 'テスト局2']);

        $response = $this->getJson('api/radio_stations');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'テスト局1'])
            ->assertJsonFragment(['name' => 'テスト局2']);
    }
}
