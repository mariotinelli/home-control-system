<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers\PermissionsRelationManager;
use App\Models\Role;
use Filament\Resources\{Form, Resource, Table};
use Filament\{Forms, Tables};

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $modelLabel = 'função';

    protected static ?string $pluralModelLabel = 'funções';

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?string $navigationGroup = 'Gerenciamento de Acesso';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Grid::make(1)
                    ->schema([

                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->unique(ignoreRecord: true)
                            ->string()
                            ->required()
                            ->maxLength(255),

                    ]),

            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PermissionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit'   => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
