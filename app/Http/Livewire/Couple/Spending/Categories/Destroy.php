<?php

namespace App\Http\Livewire\Couple\Spending\Categories;

use App\Models\CoupleSpendingCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Destroy extends Component
{
    use AuthorizesRequests;

    public ?CoupleSpendingCategory $category = null;

    public function save(): void
    {
        $this->authorize('delete', $this->category);

        $this->category->delete();

        $this->emit('couple-spending-category::deleted');

    }

    public function render(): View
    {
        return view('livewire.couple.spending.categories.destroy');
    }
}
