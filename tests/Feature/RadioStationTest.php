<?php

namespace Tests\Feature;

use App\DataProviders\Models\RadioStation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RadioStationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->loginAsListener();
    }

    /**
     * @test
     * App\Http\Controllers\Listener\RadioStationController@store
     */
    public function ラジオ局を作成できる()
    {
        $response = $this->postJson('api/radio-stations', ['name' => 'テスト局']);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'ラジオ局が作成されました。'
            ]);

        $radio_station = RadioStation::first();
        $this->assertEquals('テスト局', $radio_station->name);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\RadioStationController@store
     */
    public function ラジオ局作成に失敗する()
    {
        $response1 = $this->postJson('api/radio-stations', ['name' => '']);

        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => [
                    'ラジオ局名を入力してください。'
                ]
            ]);

        $response2 = $this->postJson('api/radio-stations', ['name' => str_repeat('あ', 101)]);

        $response2->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => [
                    'ラジオ局名は100文字以下で入力してください。'
                ]
            ]);

        $this->assertEquals(0, RadioStation::count());
    }

    /**
     * @test
     * App\Http\Controllers\Listener\RadioStationController@index
     */
    public function ラジオ局一覧を取得できる()
    {
        $this->postJson('api/radio-stations', ['name' => 'テスト局1']);
        $this->postJson('api/radio-stations', ['name' => 'テスト局2']);

        $response = $this->getJson('api/radio-stations');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'テスト局1'])
            ->assertJsonFragment(['name' => 'テスト局2']);
    }

    /**
     * @test
     * App\Http\Controllers\Admin\RadioStationController@show
     */
    public function 個別のラジオ番組を取得できる()
    {
        $this->loginAsAdmin();
        $this->postJson('api/admin/radio-stations', ['name' => 'テスト局1']);
        $radio_station = RadioStation::first();

        $response = $this->getJson('api/admin/radio-stations/' . $radio_station->id);

        $response->assertStatus(200)
            ->assertJson([
                'radio_station' => [
                    'name' => 'テスト局1',
                ]
            ]);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\RadioStationController@update
     */
    public function ラジオ局を更新できる()
    {
        $this->postJson('api/radio-stations', ['name' => 'テスト局1']);
        $radio_station = RadioStation::first();

        $response = $this->putJson('api/radio-stations/' . $radio_station->id, ['name' => 'テスト局1更新']);
        $response->assertStatus(201)
            ->assertJson([
                'message' => 'ラジオ局が更新されました。'
            ]);

        $radio_station = RadioStation::first();
        $this->assertEquals('テスト局1更新', $radio_station->name);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\RadioStationController@update
     */
    public function ラジオ局を更新に失敗する()
    {
        $this->postJson('api/radio-stations', ['name' => 'テスト局1']);
        $radio_station = RadioStation::first();

        $response1 = $this->putJson('api/radio-stations/' . $radio_station->id, ['name' => '']);

        $response1->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => [
                    'ラジオ局名を入力してください。'
                ]
            ]);

        $response2 = $this->putJson('api/radio-stations/' . $radio_station->id, ['name' => str_repeat('あ', 101)]);

        $response2->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => [
                    'ラジオ局名は100文字以下で入力してください。'
                ]
            ]);

        $radio_station = RadioStation::first();
        $this->assertEquals('テスト局1', $radio_station->name);
    }

    /**
     * @test
     * App\Http\Controllers\Listener\RadioStationController@destroy
     */
    public function ラジオ局を削除できる()
    {
        $this->postJson('api/radio-stations', ['name' => 'テスト局1']);
        $radio_station = RadioStation::first();

        $response = $this->deleteJson('api/radio-stations/' . $radio_station->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'ラジオ局が削除されました。'
            ]);

        $this->assertEquals(0, RadioStation::count());
    }

    /**
     * @test
     * App\Http\Controllers\Listener\RadioStationController@getRadioStationName
     */
    public function ラジオ局名を取得できる()
    {
        $this->postJson('api/radio-stations', ['name' => 'テスト局1']);
        $radio_station = RadioStation::first();

        $response = $this->getJson('api/radio-station/' . $radio_station->id . '/name');

        $response->assertStatus(200)
            ->assertJsonFragment(['radio_station_name' => 'テスト局1']);
    }
}
