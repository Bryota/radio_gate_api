<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RadioProgramTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * App\Http\Controllers\RadioProgramController@store
     */
    public function ラジオ番組を追加できる()
    {
        $response = $this->postJson('api/radio_programs', ['name' => 'テスト番組', 'email' => 'test@example.com']);

        $response->assertStatus(201);
    }
}
