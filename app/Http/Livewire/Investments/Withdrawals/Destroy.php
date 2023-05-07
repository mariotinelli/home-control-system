<?php

namespace App\Http\Livewire\Investments\Withdrawals;

use App\Models\InvestmentWithdraw;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Destroy extends Component
{
    use AuthorizesRequests;

    public ?InvestmentWithdraw $investmentWithdraw = null;

    public function save(): void
    {
        $this->authorize('delete', $this->investmentWithdraw);

        $this->investmentWithdraw->delete();

        $this->emit('investment::withdraw::deleted');
    }

    public function render(): View
    {
        return view('livewire.investments.withdrawals.destroy');
    }
}
