<?php

namespace App\Http\Livewire\Banks\CreditCards\Spendings;

use App\Models\Spending;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Destroy extends Component
{
    use AuthorizesRequests;

    public ?Spending $spending = null;

    public function destroy(): void
    {
        $this->authorize('delete', $this->spending);

        $this->spending->creditCard->update([
            'remaining_limit' => $this->spending->creditCard->remaining_limit + $this->spending->amount,
        ]);

        $this->spending->delete();

        $this->emit('credit-card::spending::deleted');
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.banks.credit-cards.spendings.destroy');
    }
}
