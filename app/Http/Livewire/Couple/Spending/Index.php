<?php

namespace App\Http\Livewire\Couple\Spending;

use App\Http\Livewire\ComponentWithFilamentModal;
use App\Models\{CoupleSpending};
use Filament\Forms\Components\{Select, TextInput};
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
        auth()
            ->user()
            ->coupleSpendingCategories()
            ->create($data);
    }

    protected function getFormSchema(): array
    {
        return [

            Select::make('category_id'),

            TextInput::make('description'),

            TextInput::make('amount'),

            TextInput::make('name')
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
        return CoupleSpending::query()
            ->where('user_id', auth()->id());
    }

    protected function getTableColumns(): array
    {
        return [

            TextColumn::make('id')
                ->label('ID')
                ->sortable()
                ->searchable(),

            TextColumn::make('category.name')
                ->label('Categoria')
                ->sortable()
                ->searchable(),

            TextColumn::make('description')
                ->label('Descrição')
                ->sortable()
                ->searchable(),

            TextColumn::make('amount')
                ->label('Valor')
                ->money('BRL')
                ->sortable()
                ->searchable(),

            TextColumn::make('date')
                ->label('Data')
                ->date('d/m/Y')
                ->sortable()
                ->searchable(query: function (Builder $query, string $search): Builder {
                    return $query
                        ->whereRaw("DATE_FORMAT(date, '%d/%m/%Y') LIKE ?", ["%$search%"]);
                }),

        ];
    }
}
