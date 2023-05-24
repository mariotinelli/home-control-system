<?php

namespace App\Http\Livewire\Markets\Stock\Withdrawals;

use App\Models\{MarketStock, MarketStockWithdrawal};
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Destroy extends Component
{
    use AuthorizesRequests;

    public ?MarketStock $marketStock = null;

    public ?MarketStockWithdrawal $marketStockWithdraw = null;

    public function save(): void
    {
        $this->authorize('delete', $this->marketStockWithdraw);

        \DB::beginTransaction();

        try {

            $this->marketStock->increment('quantity', $this->marketStockWithdraw->quantity);

            $this->marketStockWithdraw->delete();

            $this->emit('market-stock::withdraw::deleted');

        } catch (\Exception $e) {
            \DB::rollBack();
        }

    }

    public function render(): View
    {
        return view('livewire.markets.stocks.withdrawals.destroy');
    }
}
