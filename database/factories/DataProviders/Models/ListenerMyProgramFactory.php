<?php

namespace Database\Factories\DataProviders\Models;

use App\DataProviders\Models\ListenerMyProgram;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class ListenerMyProgramFactory extends Factory
{
    protected $model = ListenerMyProgram::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->title,
            'email' => $this->faker->unique()->safeEmail(),
        ];
    }
}
