<?php

namespace App\Traits;

use Closure;
use Exception;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Contracts\Support\Htmlable;

trait HasFilamentModalTables
{
    use InteractsWithTable;

    private static bool $createMethodExists = true;

    protected function getDefaultTableSortColumn(): ?string
    {
        return static::$defaultSortColumn;
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return static::$defaultSortDirection;
    }

    protected function getTableRecordActionUsing(): ?Closure
    {
        return fn (): string => 'edit';
    }

    protected function getTableHeading(): string|Htmlable|Closure|null
    {
        return view('components.app.filament.resources.table.heading', ['title' => static::$resourceMenuLabel]);
    }

    /** @throws Exception */
    protected function getTableHeaderActions(): array
    {
        return [
            Tables\Actions\CreateAction::make('create')
                ->tooltip('Criar ' . static::$resourceLabel)
                ->action(fn (array $data) => self::submitCreate($data))
                ->icon('heroicon-s-plus')
                ->label('Criar ' . static::$resourceLabel)
                ->color(static::$createActionColor ?? 'primary')
                ->modalHeading(view('components.app.filament.resources.modal.heading', ['title' => 'Criar ' . static::$resourceLabel]))
                ->form($this->getFormSchema()),
        ];
    }

    /** @throws Exception */
    protected function getTableActions(): array
    {
        return [

            Tables\Actions\EditAction::make()
                ->button()
                ->tooltip('Editar ' . static::$resourceLabel)
                ->icon('heroicon-s-pencil-alt')
                ->modalHeading(view('components.app.filament.resources.modal.heading', ['title' => 'Editar ' . static::$resourceLabel]))
                ->form($this->getFormSchema())
                ->successNotification(
                    Notification::make()
                        ->title(static::$resourceMenuLabel)
                        ->body(static::$successUpdateNotification)
                        ->success()
                ),

            Tables\Actions\DeleteAction::make()
                ->button()
                ->modalHeading('Deletar ' . static::$resourceLabel)
                ->successNotification(
                    Notification::make()
                        ->title(static::$resourceMenuLabel)
                        ->body(static::$successDeleteNotification)
                        ->success()
                )->tooltip('Deletar ' . static::$resourceLabel),

        ];
    }

    private static function submitCreate(array $data): void
    {
        static::create($data);

        if (self::$createMethodExists) {
            Notification::make()
                ->title(static::$resourceMenuLabel)
                ->body(static::$successCreateNotification)
                ->success()
                ->send();
        }
    }

    protected static function create(array $data): void
    {
        self::$createMethodExists = false;

        Notification::make()
            ->body('É necessário criar o método create.')
            ->warning()
            ->send();
    }

}
