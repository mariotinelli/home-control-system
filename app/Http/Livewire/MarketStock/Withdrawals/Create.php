<?php

namespace App\Http\Livewire\MarketStock\Withdrawals;

use App\Models\{MarketStock, MarketStockWithdrawal};
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Create extends Component
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
            'marketStockWithdraw.quantity'        => ['required', 'integer', 'min:1', 'max:' . $this->marketStock->quantity],
        ];
    }

    public function messages(): array
    {
        return [
            'marketStockWithdraw.quantity.max' => 'The quantity field must be greater than that market stock: ' . $this->marketStock->quantity . '.',
        ];
    }

    public function save(): void
    {
        $this->authorize('create', MarketStockWithdrawal::class);

        $this->validate();

        \DB::beginTransaction();

        try {

            $this->marketStockWithdraw->save();

            $this->marketStock->decrement('quantity', $this->marketStockWithdraw->quantity);

            $this->emit('market-stock::withdrawal::created');

            \DB::commit();

        } catch (\Exception $e) {

            \DB::rollBack();

        }

    }

    public function mount(): void
    {
        $this->marketStockWithdraw = new MarketStockWithdrawal();
    }

    public function render(): View
    {
        return view('livewire.market-stock.withdrawals.create');
    }
}
