<?php

namespace App\Http\Livewire\Markets\Stock;

use App\Models\MarketStock;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Destroy extends Component
{
    use AuthorizesRequests;

    public ?MarketStock $marketStock = null;

    public function save(): void
    {
        $this->authorize('market_stock_delete');

        \DB::beginTransaction();

        try {

            $this->marketStock->loadCount(['withdrawals', 'entries']);

            if ($this->marketStock->withdrawals_count > 0 || $this->marketStock->entries_count > 0) {

                $this->marketStock->delete();

                $this->emit('market-stock::deleted');

                return;
            }

            $this->authorize('forceDelete', $this->marketStock);

            $this->marketStock->forceDelete();

            $this->emit('market-stock::deleted');

        } catch (\Exception $e) {

            \DB::rollBack();

        }

    }

    public function render(): View
    {
        return view('livewire.markets.stocks.destroy');
    }
}
