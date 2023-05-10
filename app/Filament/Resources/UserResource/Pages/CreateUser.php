<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Events\CreatedNewUser;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        event(new CreatedNewUser($this->record));
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl();
    }
}
