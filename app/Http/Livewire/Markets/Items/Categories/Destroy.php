<?php

namespace App\Http\Livewire\Markets\Items\Categories;

use App\Models\MarketItemCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Destroy extends Component
{
    use AuthorizesRequests;

    public ?MarketItemCategory $marketItemCategory = null;

    public function save(): void
    {
        $this->authorize('delete', $this->marketItemCategory);

        $this->marketItemCategory->delete();

        $this->emit('market-item-category::deleted');
    }

    public function render(): View
    {
        return view('livewire.markets.items.categories.destroy');
    }
}
