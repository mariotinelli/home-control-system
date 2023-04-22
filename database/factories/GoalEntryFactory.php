<?php

namespace Database\Factories;

use App\Models\Goal;
use App\Models\GoalEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

class GoalEntryFactory extends Factory
{
    protected $model = GoalEntry::class;

    public function definition(): array
    {
        return [
            'goal_id' => Goal::factory(),
            'amount' => $this->faker->numberBetween(1, 1000),
            'date' => $this->faker->date(),
        ];
    }
}
