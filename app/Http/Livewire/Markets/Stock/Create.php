<?php

namespace App\Http\Livewire\Markets\Stock;

use App\Models\MarketStock;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

    public ?MarketStock $marketStock = null;

    public function rules(): array
    {
        return [
            'marketStock.market_item_id' => ['required', 'exists:market_items,id', 'integer', 'unique:market_stocks,market_item_id'],
            'marketStock.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function save(): void
    {
        $this->authorize('create', MarketStock::class);

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
        return view('livewire.markets.stocks.create');
    }
}
