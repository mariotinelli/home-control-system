<?php

namespace Database\Factories;

use App\Enums\TypeOfWeightEnum;
use App\Models\{MarketItem, MarketItemCategory, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class MarketItemFactory extends Factory
{
    protected $model = MarketItem::class;

    public function definition(): array
    {
        return [
            'user_id'                 => User::factory(),
            'name'                    => $this->faker->name,
            'market_item_category_id' => MarketItemCategory::factory(),
            'type_weight'             => $this->faker->randomElement(TypeOfWeightEnum::getValues()),
            'weight'                  => $this->faker->numberBetween(1, 100),
        ];
    }
}
