<?php

namespace App\Http\Livewire\Markets;

use App\Models\Market;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

    public ?Market $market = null;

    public function rules(): array
    {
        return [
            'market.name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('markets', 'name')->where('user_id', auth()->id()),
            ],
        ];
    }

    public function save(): void
    {
        $this->authorize('create', Market::class);

        $this->validate();

        auth()->user()->markets()->create([
            'name' => $this->market->name,
        ]);

        $this->emit('market::created');
    }

    public function mount(): void
    {
        $this->market = new Market();
    }

    public function render(): View
    {
        return view('livewire.markets.create');
    }
}
