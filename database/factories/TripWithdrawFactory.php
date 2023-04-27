<?php

namespace Database\Factories;

use App\Models\{Trip, TripWithdraw};
use Illuminate\Database\Eloquent\Factories\Factory;

class TripWithdrawFactory extends Factory
{
    protected $model = TripWithdraw::class;

    public function definition(): array
    {
        return [
            'trip_id'     => Trip::factory(),
            'amount'      => $this->faker->randomFloat(2, 1, 1000),
            'description' => $this->faker->sentence,
            'date'        => $this->faker->date(),
        ];
    }
}
