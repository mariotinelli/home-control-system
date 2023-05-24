<?php

namespace App\Actions\Couple\Spending\Categories;

class CreateFromAuthUser
{
    public static function execute(array $data): void
    {
        auth()
            ->user()
            ->coupleSpendingCategories()
            ->create($data);
    }

}
