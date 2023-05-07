<?php

namespace App\Http\Livewire\Investments;

use App\Models\Investment;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

    public ?Investment $investment = null;

    public function rules(): array
    {
        return [
            'investment.name'        => ['required', 'string', 'max:255', Rule::unique('investments', 'name')],
            'investment.description' => ['required', 'string', 'max:255'],
            'investment.owner'       => ['required', 'string', 'max:255'],
            'investment.start_date'  => ['required', 'date'],
        ];
    }

    public function save(): void
    {
        $this->authorize('create', Investment::class);

        $this->validate();

        $this->investment->save();

        $this->emit('investment::created');
    }

    public function mount(): void
    {
        $this->investment = new Investment();
    }

    public function render(): View
    {
        return view('livewire.investments.create');
    }
}
