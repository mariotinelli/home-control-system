<?php

namespace App\Http\Livewire\Banks\Accounts;

use App\Actions;
use App\Http\Livewire\Components\ComponentFilamentForm;
use App\Models\BankAccount;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Edit extends ComponentFilamentForm
{
    use AuthorizesRequests;

    protected static ?string $model = BankAccount::class;

    protected static ?string $resourceMenuLabel = 'Contas bancárias';

    protected static ?string $resourceLabel = 'conta bancária';

    protected static ?string $createActionColor = 'success';

    protected static ?string $baseRouteName = 'banks.accounts';

    public BankAccount $record;

    public function mount(BankAccount $record): void
    {
        $this->record = $record;

        $this->form->fill([
            'bank_name'     => $record->bank_name,
            'type'          => $record->type,
            'number'        => $record->number,
            'digit'         => $record->digit,
            'agency_number' => $record->agency_number,
            'agency_digit'  => $record->agency_digit,
            'balance'       => $record->balance,
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
