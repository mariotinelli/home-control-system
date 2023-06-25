<?php

namespace App\Http\Livewire\Filament\CreditCardResource;

use App\Actions\Banks;
use App\Http\Livewire\Components\ComponentFilamentForm;
use App\Models\CreditCard;
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

    protected static ?string $model = CreditCard::class;

    protected static ?string $resourcePluralName = 'Cartões de Crédito';

    protected static ?string $resourceName = 'cartão de crédito';

    protected static ?string $baseRouteName = 'banks.credit-cards';

    public function mount(): void
    {
        $this->form->fill();
    }

    public function render(): View
    {
        $this->authorize('create', [CreditCard::class]);

        return view('livewire.filament.credit-card-resource.create', [
            'resourceName' => static::$resourceName,
            'route'        => static::$baseRouteName . '.index',
        ]);
    }

    /** @throws Exception */
    protected function getFormSchema(): array
    {
        return Banks\CreditCards\MakeFormSchema::execute();
    }

    public static function beforeCreate(array $state): array
    {
        $state['user_id']         = auth()->id();
        $state['remaining_limit'] = $state['limit'];

        return $state;
    }
}
