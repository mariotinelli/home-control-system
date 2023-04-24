<?php

namespace Database\Factories;

use App\Models\MarketStockEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

class MarketStockEntryFactory extends Factory
{
    protected $model = MarketStockEntry::class;

    public function definition(): array
    {

        $quantity = $this->faker->numberBetween(1, 100);

        return [
            'market_stock_id' => \App\Models\MarketStock::factory()->create([
                'quantity' => $quantity,
            ]),
            'market_id' => \App\Models\Market::factory(),
            'price'     => $this->faker->numberBetween(1, 100),
            'quantity'  => $quantity,
        ];
    }
}
