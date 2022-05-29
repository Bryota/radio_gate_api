<?php

namespace Database\Factories\DataProviders\Models;

use App\DataProviders\Models\RadioStation;
use App\DataProviders\Models\RadioProgram;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class RadioProgramFactory extends Factory
{
    protected $model = RadioProgram::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'radio_station_id' => function () {
                return $this->radioStation()->id;
            },
            'name' => $this->faker->title,
            'email' => $this->faker->unique()->safeEmail(),
        ];
    }

    private function radioStation()
    {
        return RadioStation::factory()->create();
    }
}
