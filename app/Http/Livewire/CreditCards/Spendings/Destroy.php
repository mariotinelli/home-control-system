<?php

namespace App\Http\Livewire\CreditCards\Spendings;

use App\Models\Spending;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Pipeline;
use Livewire\Component;

class Destroy extends Component
{
    use AuthorizesRequests;

    public ?Spending $spending = null;

    public function destroy(): void
    {
        $this->authorize('delete', $this->spending);

        Pipeline::send($this->spending)
            ->through([
                (new \App\Pipes\Spending\ReverseRemainingLimit($this->spending->amount, $this->spending->creditCard)),
                (new \App\Pipes\Spending\UpdateCreditCard($this->spending->creditCard)),
                \App\Pipes\Spending\DeleteSpending::class,
                (new \App\Pipes\Spending\EmitSpendingDeleted($this)),
            ])
            ->thenReturn();
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.credit-cards.spendings.destroy');
    }
}
