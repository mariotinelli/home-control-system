<?php

namespace Database\Factories;

use App\Models\{MarketItemCategory, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class MarketItemCategoryFactory extends Factory
{
    protected $model = MarketItemCategory::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name'    => $this->faker->name,
        ];
    }
}
