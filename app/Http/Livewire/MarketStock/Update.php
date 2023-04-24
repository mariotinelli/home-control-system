<?php

namespace App\Http\Livewire\MarketStock;

use App\Models\MarketStock;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Update extends Component
{
    use AuthorizesRequests;

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
        $this->authorize('update', $this->marketStock);

        $this->validate();

        $this->marketStock->save();

        $this->emit('market-stock::updated');
    }

    public function render(): View
    {
        return view('livewire.market-stock.update');
    }
}
