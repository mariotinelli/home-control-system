<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\{Pages, RelationManagers};
use App\Models\User;
use Filament\Resources\{Form, Resource, Table};
use Filament\Tables\Filters\SelectFilter;
use Filament\{Forms, Tables};
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'usuário';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label('E-mail')
                    ->unique(ignoreRecord: true)
                    ->email()
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('password')
                    ->label('Senha')
                    ->password()
                    ->required()
                    ->maxLength(255),
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('roles')
                    ->name('Função')
                    ->formatStateUsing(fn ($record): string => $record->load('roles')->roles->first()->name)
                    ->colors([
                        'danger'    => static fn ($state, $record) => $record->hasRole('Administrador'),
                        'warning'   => static fn ($state, $record) => $record->hasRole('Usuário Ouro'),
                        'secondary' => static fn ($state, $record) => $record->hasRole('Usuário Prata'),
                        'success'   => static fn ($state, $record) => $record->hasRole('Usuário'),
                    ])
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy('roles.id', $direction);
                    }),

            ])
            ->defaultSort('roles.id')
            ->filters([
                SelectFilter::make('roles')->relationship('roles', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
