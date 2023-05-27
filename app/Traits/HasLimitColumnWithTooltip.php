<?php

namespace App\Traits;

use Filament\Tables\Columns\TextColumn;

trait HasLimitColumnWithTooltip
{
    public function closureTooltip(TextColumn $column): ?string
    {
        $state = $column->getState();

        if (strlen($state) <= $column->getLimit()) {
            return null;
        }

        return $state;
    }

}
