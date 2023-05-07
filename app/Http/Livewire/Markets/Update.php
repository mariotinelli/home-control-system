<?php

namespace App\Http\Livewire\Markets;

use App\Models\Market;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Update extends Component
{
    use AuthorizesRequests;

    public ?Market $market = null;

    public function rules(): array
    {
        return [
            'market.name' => ['required', 'string', Rule::unique('markets', 'name')->ignore($this->market->id), 'max:255'],
        ];
    }

    public function save(): void
    {
        $this->authorize('update', $this->market);

        $this->validate();

        $this->market->save();

        $this->emit('market::updated');
    }

    public function render(): View
    {
        return view('livewire.markets.update');
    }
}
