<?php

namespace Database\Factories\DataProviders\Models;

use App\DataProviders\Models\Listener;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class ListenerFactory extends Factory
{
    protected $model = Listener::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'last_name' => $this->faker->lastName,
            'first_name' => $this->faker->firstName,
            'last_name_kana' => $this->faker->lastKanaName,
            'first_name_kana' => $this->faker->firstKanaName,
            'radio_name' => 'ハイキングベアー',
            'post_code' => $this->faker->postcode,
            'prefecture' => $this->faker->prefecture,
            'city' => $this->faker->city,
            'house_number' => $this->faker->streetAddress,
            'tel' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'email_verified_at' => now(),
            'password' => Hash::make('password123')
        ];
    }
}
