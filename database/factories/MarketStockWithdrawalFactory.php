<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MarketStockWithdrawal>
 */
class MarketStockWithdrawalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'market_stock_id' => \App\Models\MarketStock::factory(),
            'market_id'       => \App\Models\Market::factory(),
            'quantity'        => $this->faker->numberBetween(1, 100),
            'price'           => $this->faker->numberBetween(1, 100),
        ];
    }
}
