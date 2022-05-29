<?php

namespace Database\Factories\DataProviders\Models;

use App\DataProviders\Models\MyProgramCorner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class MyProgramCornerFactory extends Factory
{
    protected $model = MyProgramCorner::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->title,
        ];
    }
}
