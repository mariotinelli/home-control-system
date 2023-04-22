<?php

namespace Database\Factories;

use App\Models\Goal;
use Illuminate\Database\Eloquent\Factories\Factory;

class GoalFactory extends Factory
{
    protected $model = Goal::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence,
            'to_reach' => $this->faker->numberBetween(1, 1000),
            'owner' => $this->faker->name,
            'description' => $this->faker->text,
        ];
    }
}
