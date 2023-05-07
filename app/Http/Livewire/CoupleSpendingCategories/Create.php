<?php

namespace App\Http\Livewire\CoupleSpendingCategories;

use App\Models\CoupleSpendingCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

    public ?CoupleSpendingCategory $category = null;

    public function getRules(): array
    {
        return [
            'category.name' => ['required', 'string', 'unique:couple_spending_categories,name', 'max:255'],
        ];
    }

    public function save(): void
    {
        $this->authorize('create', CoupleSpendingCategory::class);

        $this->validate();

        $this->category->save();

        $this->emit('couple-spending-category::created');

    }

    public function mount(): void
    {
        $this->category = new CoupleSpendingCategory();
    }

    public function render(): View
    {
        return view('livewire.couple-spending-categories.create');
    }
}
