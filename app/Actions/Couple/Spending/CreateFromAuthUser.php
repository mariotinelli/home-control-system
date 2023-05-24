<?php

namespace App\Actions\Couple\Spending;

class CreateFromAuthUser
{
    public static function execute(array $data): void
    {
        auth()
            ->user()
            ->coupleSpendings()
            ->create($data);
    }

}
