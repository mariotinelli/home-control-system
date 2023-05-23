<?php

namespace App\Http\Livewire\Couple\Spending\Categories;

use App\Models\CoupleSpendingCategory;
use Exception;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\{Forms, Tables};
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Component;

/**
 * @property Forms\ComponentContainer|View|mixed|null $form
 */
class Index extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public CoupleSpendingCategory $category;

    public $data;

    public function mount(): void
    {
        $this->category = new CoupleSpendingCategory();

        $this->form->fill([
            'name' => $this->category->name,
        ]);
    }

    public function render(): View
    {
        return view('livewire.couple.spending.categories.index');
    }

    public function submit(): void
    {
        auth()
            ->user()
            ->coupleSpendingCategories()
            ->create($this->form->getState());

        $this->dispatchBrowserEvent('close-modal', ['id' => 'newResourceModal']);

        Notification::make()
            ->title('Categorias')
            ->body('Categoria criada com sucesso!')
            ->success()
            ->send();
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    /** ############### TABLE ############### */

    protected function getTableQuery(): Builder|Relation
    {
        return CoupleSpendingCategory::query();
    }

    protected function getTableColumns(): array
    {
        return [

            Tables\Columns\TextColumn::make('id')
                ->label('ID'),

            Tables\Columns\TextColumn::make('name')
                ->label('Nome'),

        ];
    }

    /**
     * @throws Exception
     */
    protected function getTableActions(): array
    {
        return [

            Tables\Actions\EditAction::make()
                ->modalHeading('Editar categoria')
                ->form($this->getFormSchema())
                ->successNotification(
                    Notification::make()->title('Categorias')
                        ->body('Categoria atualizada com sucesso!')
                        ->success()
                ),

            Tables\Actions\DeleteAction::make()
                ->modalHeading('Deletar categoria')
                ->successNotification(
                    Notification::make()->title('Categorias')
                        ->body('Categoria excluída com sucesso!')
                        ->success()
                ),

        ];
    }

    /** ############### FORM ############### */

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('Nome')
                ->placeholder('Digite o nome da categoria')
                ->string()
                ->required()
                ->unique('couple_spending_categories', 'name')
                ->minLength(3)
                ->maxLength(255)
                ->validationAttribute('nome')
                ->autofocus(),
        ];
    }

}
