<?php

namespace App\Http\Livewire\Investments\Entries;

use App\Models\{Investment, InvestmentEntry};
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Create extends Component
{
    public ?Investment $investment = null;

    public ?InvestmentEntry $investmentEntry = null;

    public function rules(): array
    {
        return [
            'investmentEntry.amount' => ['required', 'numeric', 'min:1', 'max:1000'],
            'investmentEntry.date'   => ['required', 'date'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        \DB::beginTransaction();

        try {

            $this->investmentEntry->investment_id = $this->investment->id;

            $this->investmentEntry->save();

            $this->emit('investment::entry::created');

        } catch (\Exception $e) {

            \DB::rollBack();

        }

    }

    public function mount(): void
    {
        $this->investmentEntry = new InvestmentEntry();
    }

    public function render(): View
    {
        return view('livewire.investments.entries.create');
    }
}
