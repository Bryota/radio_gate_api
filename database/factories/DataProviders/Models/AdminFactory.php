<?php

namespace Database\Factories\DataProviders\Models;

use App\DataProviders\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class AdminFactory extends Factory
{
    protected $model = Admin::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'email' => $this->faker->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password123')
        ];
    }
}
