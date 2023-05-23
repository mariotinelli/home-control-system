<?php

namespace App\Http\Livewire\Couple\Spending\Categories;

use App\Http\Livewire\ComponentWithFilamentModal;
use App\Models\CoupleSpendingCategory;
use Filament\{Forms, Tables};
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends ComponentWithFilamentModal
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
        auth()
            ->user()
            ->coupleSpendingCategories()
            ->create($data);
    }

    protected function getFormSchema(): array
    {
        return [

            Forms\Components\TextInput::make('name')
                ->label('Nome')
                ->placeholder('Digite o nome da categoria')
                ->string()
                ->required()
                ->unique('couple_spending_categories', 'name', ignoreRecord: true)
                ->minLength(3)
                ->maxLength(255)
                ->validationAttribute('nome')
                ->autofocus(),
        ];
    }

    protected function getTableQuery(): Builder|Relation
    {
        return CoupleSpendingCategory::query();
    }

    protected function getTableColumns(): array
    {
        return [

            Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('name')
                ->label('Nome')
                ->sortable()
                ->searchable()
                ->limit(50)
                ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                    $state = $column->getState();

                    if (strlen($state) <= $column->getLimit()) {
                        return null;
                    }

                    return $state;
                }),

        ];
    }

}
