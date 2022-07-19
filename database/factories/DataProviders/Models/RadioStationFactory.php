<?php

namespace Database\Factories\DataProviders\Models;

use App\DataProviders\Models\RadioStation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class RadioStationFactory extends Factory
{
    protected $model = RadioStation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->title,
            'type' => $this->faker->randomElement(['AM', 'FM'])
        ];
    }
}
