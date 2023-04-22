<?php

namespace App\Http\Livewire\Investments\Entries;

use App\Models\Investment;
use App\Models\InvestmentEntry;
use DB;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Update extends Component
{

    use AuthorizesRequests;

    public ?Investment $investment = null;

    public ?InvestmentEntry $investmentEntry = null;

    public function rules(): array
    {
        return [
            'investmentEntry.amount' => ['required', 'numeric', 'min:1', 'max:1000'],
            'investmentEntry.date' => ['required', 'date'],
        ];
    }

    public function save(): void
    {
        $this->authorize('update', [$this->investmentEntry, $this->investment]);

        $this->validate();

        DB::beginTransaction();

        try {

            $this->investmentEntry->investment_id = $this->investment->id;

            $this->investmentEntry->save();

            $this->emit('investment::entry::updated');

        } catch (Exception $e) {

            DB::rollBack();

        }

    }


    public function render(): View
    {
        return view('livewire.investments.entries.update');
    }
}
