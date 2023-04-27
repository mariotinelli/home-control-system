<?php

namespace Database\Factories;

use App\Models\CoupleSpendingCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoupleSpendingCategoryFactory extends Factory
{
    protected $model = CoupleSpendingCategory::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(5),
        ];
    }
}
