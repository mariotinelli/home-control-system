<?php

namespace App\Http\Livewire\Markets\Stock\Withdrawals;

use App\Models\{MarketStock, MarketStockWithdrawal};
use App\Rules\GreaterThanQuantityMarketStockRule;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Update extends Component
{
    use AuthorizesRequests;

    public ?MarketStock $marketStock = null;

    public ?MarketStockWithdrawal $marketStockWithdraw = null;

    public function rules(): array
    {
        return [
            'marketStockWithdraw.market_stock_id' => ['required', 'integer', 'exists:market_stocks,id'],
            'marketStockWithdraw.market_id'       => ['required', 'integer', 'exists:markets,id'],
            'marketStockWithdraw.price'           => ['required', 'numeric', 'min:1'],
            'marketStockWithdraw.quantity'        => [
                'required',
                'integer',
                'min:1',
                new GreaterThanQuantityMarketStockRule(
                    marketStock: $this->marketStock,
                    marketStockWithdraw: $this->marketStockWithdraw
                ),
            ],
        ];
    }

    public function save(): void
    {
        $this->authorize('update', $this->marketStockWithdraw);

        $this->validate();

        try {

            $this->marketStock->increment('quantity', $this->marketStockWithdraw->getOriginal('quantity'));

            if ($this->marketStock->id != $this->marketStockWithdraw->market_stock_id) {

                $this->marketStock = MarketStock::find($this->marketStockWithdraw->market_stock_id);

            }

            $this->marketStock->decrement('quantity', $this->marketStockWithdraw->quantity);

            $this->marketStockWithdraw->save();

            $this->emit('market-stock::withdrawal::updated');

        } catch (\Exception $e) {

            \DB::rollBack();

        }

    }

    public function render(): View
    {
        return view('livewire.markets.stocks.withdrawals.update');
    }
}
