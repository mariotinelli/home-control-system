<?php

namespace Database\Factories;

use App\Models\Goal;
use App\Models\GoalWithdraw;
use Illuminate\Database\Eloquent\Factories\Factory;

class GoalWithdrawFactory extends Factory
{
    protected $model = GoalWithdraw::class;

    public function definition(): array
    {
        return [
            'goal_id' => Goal::factory(),
            'amount' => $this->faker->numberBetween(1, 1000),
            'date' => $this->faker->date(),
        ];
    }
}
