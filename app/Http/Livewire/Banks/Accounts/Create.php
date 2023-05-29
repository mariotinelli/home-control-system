<?php

namespace App\Http\Livewire\Banks\Accounts;

use App\Actions;
use App\Http\Livewire\Components\ComponentFilamentForm;
use App\Models\BankAccount;
use Exception;
use Filament\Forms\ComponentContainer;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @property ComponentContainer|View|mixed|null $form
 */
class Create extends ComponentFilamentForm
{
    use AuthorizesRequests;

    protected static ?string $model = BankAccount::class;

    protected static ?string $resourceMenuLabel = 'Contas bancárias';

    protected static ?string $resourceLabel = 'conta bancária';

    protected static ?string $createActionColor = 'success';

    protected static ?string $baseRouteName = 'banks.accounts';

    public function mount(): void
    {
        $this->form->fill();
    }

    public function render(): View|Factory|Application
    {
        $this->authorize('create', [BankAccount::class]);

        return view('livewire.banks.accounts.create');
    }

    protected static function store(array $data): void
    {
        auth()->user()->bankAccounts()->create($data);
    }

    /** @throws Exception */
    protected function getFormSchema(): array
    {
        return Actions\Banks\Accounts\MakeFormSchema::execute();
    }
}
