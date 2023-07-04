<?php

namespace App\Actions\Couple\Spending\Places;

class CreateFromAuthUser
{
    public static function execute(array $data): void
    {
        auth()
            ->user()
            ->coupleSpendingPlaces()
            ->create($data);
    }

}
