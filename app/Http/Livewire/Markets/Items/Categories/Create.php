<?php

namespace App\Http\Livewire\Markets\Items\Categories;

use App\Models\MarketItemCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Create extends Component
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
        $this->authorize('create', MarketItemCategory::class);

        $this->validate();

        auth()->user()->marketItemCategories()->save($this->marketItemCategory);

        $this->emit('market-item-category::created');
    }

    public function mount(): void
    {
        $this->marketItemCategory = new MarketItemCategory();
    }

    public function render(): View
    {
        return view('livewire.markets.items.categories.create');
    }
}
