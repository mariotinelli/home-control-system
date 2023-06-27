<?php

namespace App\Http\Livewire\Couple\Spending;

use App\Actions\Couple;
use App\Models\CoupleSpending;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

/**
 * @property ComponentContainer|View|mixed|null $form
 */
class Create extends Component implements HasForms
{
    use InteractsWithForms;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function render(): View
    {
        return view('livewire.couple.spending.create');
    }

    public function store(): void
    {
        $data = $this->form->getState();

        auth()->user()->coupleSpendings()->create($data);

        $this->emit('couple::spending::created');

        Notification::make()
            ->success()
            ->body('Gasto criado com sucesso.')
            ->send();
    }

    protected function getFormSchema(): array
    {
        return Couple\Spending\MakeFormSchema::execute();
    }

    protected function getFormModel(): Model|string|null
    {
        return CoupleSpending::class;
    }
}
