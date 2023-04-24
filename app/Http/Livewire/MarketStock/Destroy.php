<?php

namespace App\Http\Livewire\MarketStock;

use App\Models\MarketStock;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Destroy extends Component
{
    public ?MarketStock $marketStock = null;

    public function save(): void
    {
        \DB::beginTransaction();

        try {

            $this->marketStock->loadCount(['withdrawals', 'entries']);

            if ($this->marketStock->withdrawals_count > 0 || $this->marketStock->entries_count > 0) {

                $this->marketStock->delete();

                $this->emit('market-stock::deleted');

                return;
            }

            $this->marketStock->forceDelete();

            $this->emit('market-stock::deleted');

        } catch (\Exception $e) {

            \DB::rollBack();

        }

    }

    public function render(): View
    {
        return view('livewire.market-stock.destroy');
    }
}
