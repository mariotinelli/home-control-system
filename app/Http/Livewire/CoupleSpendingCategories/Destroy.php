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

        if ($this->category->spendings()->count() > 0) {
            $this->addError('category', 'This category has spendings');

            return;
        }

        $this->category->delete();

        $this->emit('couple-spending-category::deleted');

    }

    public function render(): View
    {
        return view('livewire.couple-spending-categories.destroy');
    }
}
