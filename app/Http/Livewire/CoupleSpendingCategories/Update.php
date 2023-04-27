<?php

namespace App\Http\Livewire\CoupleSpendingCategories;

use App\Models\CoupleSpendingCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Update extends Component
{
    public ?CoupleSpendingCategory $category = null;

    public function getRules(): array
    {
        return [
            'category.name' => ['required', 'string', Rule::unique('couple_spending_categories', 'name')->ignore($this->category->id, 'id'), 'max:255'],
        ];
    }

    public function save(): void
    {

        $this->validate();

        $this->category->save();

        $this->emit('couple-spending-category::updated');

    }

    public function render(): View
    {
        return view('livewire.couple-spending-categories.update');
    }
}
