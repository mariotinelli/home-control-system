<?php

namespace App\Http\Livewire\MarketStock\Entries;

use App\Models\MarketStock;
use App\Models\MarketStockEntry;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Update extends Component
{

    public ?MarketStock $marketStock = null;

    public ?MarketStockEntry $marketStockEntry = null;

    public function rules(): array
    {
        return [
            'marketStockEntry.market_stock_id' => ['required', 'integer', 'exists:market_stocks,id'],
            'marketStockEntry.market_id' => ['required', 'integer', 'exists:markets,id'],
            'marketStockEntry.price' => ['required', 'numeric', 'min:1'],
            'marketStockEntry.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function save(): void
    {
        $this->validate();


        \DB::beginTransaction();

        try {

            $this->marketStock->decrement('quantity', $this->marketStockEntry->getOriginal('quantity'));

            if ($this->marketStockEntry->market_stock_id != $this->marketStockEntry->getOriginal('market_stock_id')) {

                $this->marketStock = MarketStock::find($this->marketStockEntry->market_stock_id);

            }

            $this->marketStock->increment('quantity', $this->marketStockEntry->quantity);

            $this->marketStockEntry->save();

            $this->emit('market-stock::entry::updated');

            \DB::commit();

        } catch (\Exception $e) {
            \DB::rollBack();
        }
    }

    public function render(): View
    {
        return view('livewire.market-stock.entries.update');
    }
}
