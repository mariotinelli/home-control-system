<?php

namespace App\Http\Livewire\CoupleSpendingCategories;

use App\Models\CoupleSpendingCategory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Destroy extends Component
{
    public ?CoupleSpendingCategory $category = null;

    public function save(): void
    {

        $this->category->delete();

        $this->emit('couple-spending-category::deleted');

    }

    public function render(): View
    {
        return view('livewire.couple-spending-categories.destroy');
    }
}
