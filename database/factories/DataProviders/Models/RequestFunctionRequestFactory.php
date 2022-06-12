<?php

namespace Database\Factories\DataProviders\Models;

use App\DataProviders\Models\RequestFunctionRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class RequestFunctionRequestFactory extends Factory
{
    protected $model = RequestFunctionRequest::class;

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
            'is_open' => $this->faker->boolean
        ];
    }
}
