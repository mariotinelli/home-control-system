<?php

namespace App\Http\Livewire\MarketStock\Withdrawals;

use App\Models\MarketStock;
use App\Models\MarketStockWithdrawal;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Destroy extends Component
{

    public ?MarketStock $marketStock = null;

    public ?MarketStockWithdrawal $marketStockWithdraw = null;

    public function save(): void
    {

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
        return view('livewire.market-stock.withdrawals.destroy');
    }
}
