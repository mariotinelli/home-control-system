<?php

namespace App\Http\Livewire\Investments\Withdrawals;

use App\Models\{Investment, InvestmentWithdraw};
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Create extends Component
{
    public ?InvestmentWithdraw $investmentWithdraw = null;

    public ?Investment $investment = null;

    public function rules(): array
    {
        return [
            'investmentWithdraw.amount' => ['required', 'numeric', 'min:1', 'max:1000'],
            'investmentWithdraw.date'   => ['required', 'date'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->investmentWithdraw->investment_id = $this->investment->id;

        $this->investmentWithdraw->save();

        $this->emit('investment::withdraw::created');
    }

    public function mount(): void
    {
        $this->investmentWithdraw = new InvestmentWithdraw();
    }

    public function render(): View
    {
        return view('livewire.investments.withdrawals.create');
    }
}
