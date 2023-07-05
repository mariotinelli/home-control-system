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
class Edit extends Component implements HasForms
{
    use InteractsWithForms;

    public CoupleSpending $coupleSpending;

    public $data;

    public function mount(): void
    {
        $this->form->fill([
            'couple_spending_category_id' => $this->coupleSpending->couple_spending_category_id,
            'couple_spending_place_id'    => $this->coupleSpending->couple_spending_place_id,
            'description'                 => $this->coupleSpending->description,
            'amount'                      => $this->coupleSpending->amount,
            'date'                        => $this->coupleSpending->date,
        ]);
    }

    public function render(): View
    {
        return view('livewire.couple.spending.edit');
    }

    public function update(): void
    {
        $this->coupleSpending->update($this->form->getState());

        $this->emit('couple::spending::updated');

        Notification::make()
            ->success()
            ->body('Gasto atualizado com sucesso.')
            ->send();
    }

    protected function getFormSchema(): array
    {
        return Couple\Spending\MakeFormSchema::execute();
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    protected function getFormModel(): Model|string|null
    {
        return $this->coupleSpending;
    }

}
