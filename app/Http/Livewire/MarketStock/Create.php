<?php

namespace App\Http\Livewire\MarketStock;

use App\Models\MarketStock;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Create extends Component
{
    public ?MarketStock $marketStock = null;

    public function rules(): array
    {
        return [
            'marketStock.market_item_id' => ['required', 'exists:market_items,id', 'integer', 'unique:market_stocks,market_item_id'],
            'marketStock.quantity'       => ['required', 'integer', 'min:1'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->marketStock->save();

        $this->emit('market-stock::created');
    }

    public function mount(): void
    {
        $this->marketStock = new MarketStock();
    }

    public function render(): View
    {
        return view('livewire.market-stock.create');
    }
}
