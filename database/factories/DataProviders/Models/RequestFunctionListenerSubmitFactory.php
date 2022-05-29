<?php

namespace Database\Factories\DataProviders\Models;

use App\DataProviders\Models\RequestFunctionListenerSubmit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class RequestFunctionListenerSubmitFactory extends Factory
{
    protected $model = RequestFunctionListenerSubmit::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'point' => mt_rand(1, 100)
        ];
    }
}
