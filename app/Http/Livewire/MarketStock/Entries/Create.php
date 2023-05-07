<?php

namespace App\Http\Livewire\MarketStock\Entries;

use App\Models\{MarketStock, MarketStockEntry};
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

    public ?MarketStock $marketStock = null;

    public ?MarketStockEntry $marketStockEntry = null;

    public function rules(): array
    {
        return [
            'marketStockEntry.market_stock_id' => ['required', 'integer', 'exists:market_stocks,id'],
            'marketStockEntry.market_id'       => ['required', 'integer', 'exists:markets,id'],
            'marketStockEntry.price'           => ['required', 'numeric', 'min:1'],
            'marketStockEntry.quantity'        => ['required', 'integer', 'min:1'],
        ];
    }

    public function save(): void
    {
        $this->authorize('create', MarketStockEntry::class);

        $this->validate();

        \DB::beginTransaction();

        try {

            $this->marketStock->increment('quantity', $this->marketStockEntry->quantity);

            $this->marketStockEntry->save();

            $this->emit('market-stock::entry::created');

        } catch (\Exception $e) {

            \DB::rollBack();

        }

    }

    public function mount(): void
    {
        $this->marketStockEntry = new MarketStockEntry();
    }

    public function render(): View
    {
        return view('livewire.market-stock.entries.create');
    }
}
