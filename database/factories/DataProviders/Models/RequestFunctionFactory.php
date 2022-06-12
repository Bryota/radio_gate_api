<?php

namespace Database\Factories\DataProviders\Models;

use App\DataProviders\Models\RequestFunction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class RequestFunctionFactory extends Factory
{
    protected $model = RequestFunction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->title,
            'detail' => $this->faker->text,
            'point' => mt_rand(0, 50),
            'is_open' => $this->faker->boolean
        ];
    }
}
