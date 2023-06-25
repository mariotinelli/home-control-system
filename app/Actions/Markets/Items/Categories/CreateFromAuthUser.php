<?php

namespace App\Actions\Markets\Items\Categories;

class CreateFromAuthUser
{
    public static function execute(array $data): void
    {
        auth()
            ->user()
            ->marketItemCategories()
            ->create($data);
    }

}
