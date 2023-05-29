<?php

namespace App\Traits;

use App\Traits\FIlament\Forms\{HasCreateForm, HasEditForm};
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

trait HasFilamentForms
{
    use InteractsWithForms;
    use AuthorizesRequests;
    use HasCreateForm;
    use HasEditForm;

    public $data;

    protected function getFormStatePath(): string
    {
        return 'data';
    }
}
