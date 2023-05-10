<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Resources\PermissionResource\RelationManagers\RolesRelationManager;
use App\Models\Permission;
use Exception;
use Filament\Resources\{Form, Resource, Table};
use Filament\{Forms, Tables};

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $modelLabel = 'permissão';

    protected static ?string $pluralModelLabel = 'permissões';

    protected static ?string $navigationIcon = 'heroicon-o-exclamation';

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
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),

            ])
            ->defaultSort('id', 'desc')
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
            RolesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit'   => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
