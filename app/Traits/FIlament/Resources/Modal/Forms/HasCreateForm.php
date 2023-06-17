<?php

namespace App\Traits\FIlament\Resources\Modal\Forms;

use Illuminate\Database\Eloquent\Model;

trait HasCreateForm
{
    public static function store(array $data): void
    {

        $data = static::beforeCreate(state: $data);

        $record = static::$model::create($data);

        $record = static::afterCreate(record: $record);

        if (static::$sendNotification) {
            static::getCreateNotification(record: $record);
        };
    }

    public static function beforeCreate(array $state): array
    {
        return $state;
    }

    public static function afterCreate(Model $record): Model
    {
        return $record;
    }

}
