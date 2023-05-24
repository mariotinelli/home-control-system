<?php

namespace App\Http\Livewire\Markets\Items\Categories;

use App\Models\MarketItemCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Update extends Component
{
    use AuthorizesRequests;

    public ?MarketItemCategory $marketItemCategory = null;

    public function rules(): array
    {
        return [
            'marketItemCategory.name' => ['required', 'string', 'max:100'],
        ];
    }

    public function save(): void
    {
        $this->authorize('update', $this->marketItemCategory);

        $this->validate();

        $this->marketItemCategory->save();

        $this->emit('market-item-category::updated');
    }

    public function render(): View
    {
        return view('livewire.markets.items.categories.update');
    }
}
