<?php

namespace App\Http\Livewire\Filament\BankAccountResource;

use App\Actions;
use App\Http\Livewire\Components\ComponentFilamentForm;
use App\Models\BankAccount;
use Exception;
use Filament\Forms\ComponentContainer;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @property ComponentContainer|View|mixed|null $form
 */
class Create extends ComponentFilamentForm
{
    use AuthorizesRequests;

    protected static ?string $model = BankAccount::class;

    protected static ?string $resourcePluralName = 'Contas Bancárias';

    protected static ?string $resourceName = 'conta bancária';

    protected static ?string $baseRouteName = 'banks.accounts';

    public function mount(): void
    {
        $this->form->fill();
    }

    public function render(): View
    {
        $this->authorize('create', [BankAccount::class]);

        return view('livewire.filament.bank-account-resource.create', [
            'resourceName' => static::$resourceName,
            'route'        => static::$baseRouteName . '.index',
        ]);
    }

    /** @throws Exception */
    protected function getFormSchema(): array
    {
        return Actions\Banks\Accounts\MakeFormSchema::execute();
    }

    public static function beforeCreate(array $state): array
    {
        $state['user_id'] = auth()->id();

        return $state;
    }

}
