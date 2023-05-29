<?php

namespace App\Http\Livewire\Banks\Accounts;

use App\Actions;
use App\Http\Livewire\Components\ComponentFilamentForm;
use App\Models\BankAccount;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/** @property BankAccount $record */
class Edit extends ComponentFilamentForm
{
    use AuthorizesRequests;

    protected static ?string $model = BankAccount::class;

    protected static ?string $resourceMenuLabel = 'Contas bancárias';

    protected static ?string $resourceLabel = 'conta bancária';

    protected static ?string $createActionColor = 'success';

    protected static ?string $baseRouteName = 'banks.accounts';

    public function mount(): void
    {
        $this->form->fill([
            'bank_name'     => $this->record->bank_name,
            'type'          => $this->record->type,
            'number'        => $this->record->number,
            'digit'         => $this->record->digit,
            'agency_number' => $this->record->agency_number,
            'agency_digit'  => $this->record->agency_digit,
            'balance'       => $this->record->balance,
        ]);
    }

    public function render(): View|Factory|Application
    {
        $this->authorize('update', $this->record);

        return view('livewire.banks.accounts.update');
    }

    /** @throws Exception */
    protected function getFormSchema(): array
    {
        return Actions\Banks\Accounts\MakeFormSchema::execute();
    }

    protected static function update(array $data): void
    {
        auth()->user()->bankAccounts()->create($data);
    }

}
