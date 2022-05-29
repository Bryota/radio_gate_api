<?php

namespace Database\Factories\DataProviders\Models;

use App\DataProviders\Models\ListenerMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class ListenerMessageFactory extends Factory
{
    protected $model = ListenerMessage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'listener_id' => mt_rand(1, 10),
            'content' => $this->faker->text,
            'radio_name' => $this->faker->title,
            'posted_at' => $this->faker->dateTime
        ];
    }
}
