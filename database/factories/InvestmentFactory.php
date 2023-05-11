<?php

namespace Database\Factories;

use App\Models\{Investment, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class InvestmentFactory extends Factory
{
    protected $model = Investment::class;

    public function definition(): array
    {
        return [
            'user_id'     => User::factory(),
            'name'        => $this->faker->sentence,
            'description' => $this->faker->text,
            'owner'       => $this->faker->name,
            'start_date'  => $this->faker->date(),
        ];
    }
}
