<?php

namespace App\Rules;

use App\Models\MarketStock;
use App\Models\MarketStockWithdrawal;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class GreaterThanQuantityMarketStockRule implements ValidationRule
{

    public function __construct(
        protected MarketStock           $marketStock,
        protected MarketStockWithdrawal $marketStockWithdraw,
    )
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if ($this->marketStock->id == $this->marketStockWithdraw->market_stock_id) {

            $this->marketStock->quantity += $this->marketStockWithdraw->getOriginal('quantity');

            if ($value > $this->marketStock->quantity) {
                $fail('The quantity field must be greater than that market stock');
            }

        } else {

            $this->marketStock = MarketStock::find($this->marketStockWithdraw->market_stock_id);

            if ($value > $this->marketStock->quantity) {
                $fail('The quantity field must be greater than that market stock');
            }

        }


    }
}
