<?php

namespace App\Http\Livewire\Investments\Withdrawals;

use App\Models\InvestmentWithdraw;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Destroy extends Component
{
    public ?InvestmentWithdraw $investmentWithdraw = null;

    public function save(): void
    {
        $this->investmentWithdraw->delete();

        $this->emit('investment::withdraw::deleted');
    }

    public function render(): View
    {
        return view('livewire.investments.withdrawals.destroy');
    }
}
