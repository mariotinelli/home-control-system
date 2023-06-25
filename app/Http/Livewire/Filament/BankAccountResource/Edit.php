<?php

namespace App\Http\Livewire\Filament\BankAccountResource;

use App\Actions;
use App\Http\Livewire\Components\ComponentFilamentForm;
use App\Models\BankAccount;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Edit extends ComponentFilamentForm
{
    use AuthorizesRequests;

    protected static ?string $model = BankAccount::class;

    protected static ?string $resourcePluralName = 'Contas Bancárias';

    protected static ?string $resourceName = 'conta bancária';

    protected static ?string $baseRouteName = 'banks.accounts';

    public ?BankAccount $record = null;

    public function mount(): void
    {
        $this->form->fill([
            'bank_name'     => $this->record->bank_name,
            'type'          => $this->record->type->value,
            'number'        => $this->record->number,
            'digit'         => $this->record->digit,
            'agency_number' => $this->record->agency_number,
            'agency_digit'  => $this->record->agency_digit,
            'balance'       => $this->record->balance,
        ]);
    }

    public function render(): View
    {
        $this->authorize('update', $this->record);

        return view('livewire.filament.bank-account-resource.edit', [
            'resourceName' => static::$resourceName,
            'route'        => static::$baseRouteName . '.index',
        ]);
    }

    /** @throws Exception */
    protected function getFormSchema(): array
    {
        return Actions\Banks\Accounts\MakeFormSchema::execute(
            record: $this->record,
        );
    }

}
