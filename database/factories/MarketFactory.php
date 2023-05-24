<?php

namespace Database\Factories;

use App\Models\{Market, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class MarketFactory extends Factory
{
    protected $model = Market::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name'    => $this->faker->name,
        ];
    }
}
