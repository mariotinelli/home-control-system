<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    /**
     * @throws \Exception
     */
    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getTableQuery()->join('model_has_roles as mhr', 'mhr.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'mhr.role_id')
            ->select('users.*', 'roles.id as roles_id');
    }
}
