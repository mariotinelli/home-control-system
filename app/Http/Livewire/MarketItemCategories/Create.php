<?php

namespace App\Http\Livewire\MarketItemCategories;

use App\Models\MarketItemCategory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Create extends Component
{
    public ?MarketItemCategory $marketItemCategory = null;

    public function rules(): array
    {
        return [
            'marketItemCategory.name' => ['required', 'string', 'max:100'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->marketItemCategory->save();

        $this->emit('market-item-category::created');
    }

    public function mount(): void
    {
        $this->marketItemCategory = new MarketItemCategory();
    }

    public function render(): View
    {
        return view('livewire.market-item-categories.create');
    }
}
