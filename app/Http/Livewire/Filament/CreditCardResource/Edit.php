<?php

namespace App\Http\Livewire\Filament\CreditCardResource;

use App\Actions\Banks;
use App\Http\Livewire\Components\ComponentFilamentForm;
use App\Models\CreditCard;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Edit extends ComponentFilamentForm
{
    use AuthorizesRequests;

    protected static ?string $model = CreditCard::class;

    protected static ?string $resourcePluralName = 'Cartões de Crédito';

    protected static ?string $resourceName = 'cartão de crédito';

    protected static ?string $baseRouteName = 'banks.credit-cards';

    public ?CreditCard $record = null;

    public function mount(): void
    {
        $this->form->fill([
            'bank'       => $this->record->bank,
            'number'     => $this->record->number,
            'expiration' => $this->record->expiration,
            'cvv'        => $this->record->cvv,
            'limit'      => $this->record->limit,
        ]);
    }

    public function render(): View
    {
        $this->authorize('update', $this->record);

        return view('livewire.filament.credit-card-resource.edit', [
            'resourceName' => static::$resourceName,
            'route'        => static::$baseRouteName . '.index',
        ]);
    }

    /** @throws Exception */
    protected function getFormSchema(): array
    {
        return Banks\CreditCards\MakeFormSchema::execute();
    }

    public static function beforeUpdate(array $state, Model $record): array
    {
        /** @var CreditCard $record */
        $state['remaining_limit'] = $state['limit'] - $record->spendings()->sum('amount');

        return $state;
    }

}
