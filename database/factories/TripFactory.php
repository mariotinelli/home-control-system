<?php

namespace Database\Factories;

use App\Models\{City, Trip};
use Illuminate\Database\Eloquent\Factories\Factory;

class TripFactory extends Factory
{
    protected $model = Trip::class;

    public function definition(): array
    {
        return [
            'city_id'     => $this->faker->numberBetween(1, City::count()),
            'month'       => $this->faker->date('m/Y'),
            'description' => $this->faker->sentence,
            'total_value' => $this->faker->randomFloat(2, 1, 1000),
        ];
    }
}
