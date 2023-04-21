<?php

namespace App\Http\Livewire\MarketStock\Withdrawals;

use App\Models\MarketStock;
use App\Models\MarketStockWithdrawal;
use App\Rules\GreaterThanQuantityMarketStockRule;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Update extends Component
{
    public ?MarketStock $marketStock = null;

    public ?MarketStockWithdrawal $marketStockWithdraw = null;

    public function rules(): array
    {
        return [
            'marketStockWithdraw.market_stock_id' => ['required', 'integer', 'exists:market_stocks,id'],
            'marketStockWithdraw.market_id' => ['required', 'integer', 'exists:markets,id'],
            'marketStockWithdraw.price' => ['required', 'numeric', 'min:1'],
            'marketStockWithdraw.quantity' => [
                'required',
                'integer',
                'min:1',
                new GreaterThanQuantityMarketStockRule(
                    marketStock: $this->marketStock,
                    marketStockWithdraw: $this->marketStockWithdraw
                )
            ],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->marketStock->increment('quantity', $this->marketStockWithdraw->getOriginal('quantity'));

        if ($this->marketStock->id != $this->marketStockWithdraw->market_stock_id) {

            $this->marketStock = MarketStock::find($this->marketStockWithdraw->market_stock_id);

        }

        $this->marketStock->decrement('quantity', $this->marketStockWithdraw->quantity);

        $this->marketStockWithdraw->save();

        $this->emit('market-stock::withdrawal::updated');

    }

    public function render(): View
    {
        return view('livewire.market-stock.withdrawals.update');
    }
}
