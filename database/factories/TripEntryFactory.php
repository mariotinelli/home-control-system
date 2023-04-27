<?php

namespace Database\Factories;

use App\Models\TripEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

class TripEntryFactory extends Factory
{
    protected $model = TripEntry::class;

    public function definition(): array
    {
        return [
            'trip_id'     => 1,
            'amount'      => $this->faker->numberBetween(1, 1000),
            'description' => $this->faker->sentence,
            'date'        => $this->faker->date(),
        ];
    }
}
