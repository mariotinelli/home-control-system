<?php

namespace App\Http\Livewire\CreditCards;

use App\Models\CreditCard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Destroy extends Component
{
    use AuthorizesRequests;

    public ?CreditCard $creditCard = null;

    public function save(): void
    {
        $this->authorize('delete', $this->creditCard);

        $this->creditCard->delete();

        $this->emit('credit-card::destroyed');
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.credit-cards.destroy');
    }
}
