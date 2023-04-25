<?php

namespace App\Http\Livewire\Investments;

use App\Models\Investment;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Destroy extends Component
{
    public ?Investment $investment = null;

    public function save(): void
    {

        if ($this->investment->entries()->exists() || $this->investment->withdrawals()->exists()) {

            $this->investment->delete();

            $this->emit('investment::deleted');

            return;
        }

        $this->investment->forceDelete();

        $this->emit('investment::deleted');
    }

    public function render(): View
    {
        return view('livewire.investments.destroy');
    }
}
