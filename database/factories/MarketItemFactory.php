<?php

namespace Database\Factories;

use App\Enums\TypeOfWeightEnum;
use App\Models\MarketItem;
use App\Models\MarketItemCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class MarketItemFactory extends Factory
{
    protected $model = MarketItem::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'market_item_category_id' => MarketItemCategory::factory(),
            'type_weight' => $this->faker->randomElement(TypeOfWeightEnum::getValues()),
            'weight' => $this->faker->numberBetween(1, 100),
            'price' => $this->faker->numberBetween(1, 100),
        ];
    }
}
