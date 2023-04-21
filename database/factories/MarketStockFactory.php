<?php

namespace Database\Factories;

use App\Models\MarketItem;
use App\Models\MarketStock;
use Illuminate\Database\Eloquent\Factories\Factory;

class MarketStockFactory extends Factory
{
    protected $model = MarketStock::class;

    public function definition(): array
    {
        return [
            'market_item_id' => MarketItem::factory()->create()->id,
            'quantity' => $this->faker->numberBetween(1, 100),
        ];
    }
}
