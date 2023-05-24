<?php

namespace App\Http\Livewire\Couple\Spending;

use App\Actions\Couple;
use App\Http\Livewire\ComponentWithFilamentModal;
use App\Models\{CoupleSpending};
use Exception;
use Filament\Tables\Columns\{TextColumn};
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends ComponentWithFilamentModal
{
    use AuthorizesRequests;

    protected static ?string $model = CoupleSpending::class;

    protected static ?string $resourceMenuLabel = 'Gastos';

    protected static ?string $resourceLabel = 'gasto';

    protected static ?string $createActionColor = 'success';

    public function render(): View
    {
        $this->authorize('viewAny', [CoupleSpending::class]);

        return view('livewire.couple.spending.index');
    }

    protected static function create(array $data): void
    {
        Couple\Spending\CreateFromAuthUser::execute($data);
    }

    /** @throws Exception */
    protected function getFormSchema(): array
    {
        return Couple\Spending\MakeFormSchema::execute();
    }

    protected function getTableQuery(): Builder|Relation
    {
        return CoupleSpending::query()
            ->where('user_id', auth()->id());
    }

    protected function getTableColumns(): array
    {
        return Couple\Spending\MakeTableColumns::execute(
            closureTooltip: fn (TextColumn $column): ?string => $this->closureTooltip($column),
        );
    }
}
