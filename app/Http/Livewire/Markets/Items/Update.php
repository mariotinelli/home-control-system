<?php

namespace App\Http\Livewire\Markets\Items;

use App\Enums\TypeOfWeightEnum;
use App\Models\MarketItem;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Update extends Component
{
    use AuthorizesRequests;

    public ?MarketItem $marketItem = null;

    public function rules(): array
    {
        return [
            'marketItem.name' => ['required', 'string', 'max:150', 'unique:market_items,name,' . $this->marketItem->id . ',id,market_item_category_id,' . $this->marketItem->market_item_category_id],
            'marketItem.market_item_category_id' => ['required', 'integer', 'exists:market_item_categories,id'],
            'marketItem.type_weight' => ['required', 'string', 'in:' . implode(',', TypeOfWeightEnum::getValues())],
            'marketItem.weight' => ['required', 'numeric', 'min:1', 'max:100000'],
        ];
    }

    public function save(): void
    {
        $this->authorize('update', $this->marketItem);

        $this->validate();

        $this->marketItem->save();

        $this->emit('market-item::updated');
    }

    public function render(): View
    {
        return view('livewire.markets.items.update');
    }
}
