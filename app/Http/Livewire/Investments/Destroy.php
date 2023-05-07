<?php

namespace App\Http\Livewire\Investments;

use App\Models\Investment;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Destroy extends Component
{
    use AuthorizesRequests;

    public ?Investment $investment = null;

    public function save(): void
    {
        $this->authorize('delete', $this->investment);

        if ($this->investment->entries()->exists() || $this->investment->withdrawals()->exists()) {

            $this->investment->delete();

            $this->emit('investment::deleted');

            return;
        }

        $this->authorize('forceDelete', $this->investment);

        $this->investment->forceDelete();

        $this->emit('investment::deleted');
    }

    public function render(): View
    {
        return view('livewire.investments.destroy');
    }
}
