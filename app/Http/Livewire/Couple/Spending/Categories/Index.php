<?php

namespace App\Http\Livewire\Couple\Spending\Categories;

use App\Actions\Couple;
use App\Http\Livewire\Components\ComponentFilamentSimple;
use App\Models\CoupleSpendingCategory;
use Filament\{Tables\Columns\TextColumn};
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends ComponentFilamentSimple
{
    use AuthorizesRequests;

    protected static ?string $model = CoupleSpendingCategory::class;

    protected static ?string $resourceMenuLabel = 'Categorias de Gastos';

    protected static ?string $resourceLabel = 'categoria';

    protected static ?string $createActionColor = 'success';

    public function render(): View
    {
        $this->authorize('viewAny', CoupleSpendingCategory::class);

        return view('livewire.couple.spending.categories.index');
    }

    protected static function create(array $data): void
    {
        Couple\Spending\Categories\CreateFromAuthUser::execute($data);
    }

    protected function getFormSchema(): array
    {
        return Couple\Spending\Categories\MakeFormSchema::execute();
    }

    protected function getTableQuery(): Builder|Relation
    {
        return CoupleSpendingCategory::query()
            ->where('user_id', auth()->id());
    }

    protected function getTableColumns(): array
    {
        return Couple\Spending\Categories\MakeTableColumns::execute(
            closureTooltip: fn (TextColumn $column): ?string => $this->closureTooltip($column),
        );
    }

}
