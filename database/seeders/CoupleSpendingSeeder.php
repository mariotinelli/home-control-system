<?php

namespace Database\Seeders;

use App\Models\{CoupleSpending, CoupleSpendingCategory, CoupleSpendingPlace};
use Illuminate\Database\Seeder;

class CoupleSpendingSeeder extends Seeder
{
    public function run(): void
    {

        $categories = CoupleSpendingCategory::factory()->count(5)->create([
            'user_id' => 3,
        ]);

        $places = CoupleSpendingPlace::factory()->count(5)->create([
            'user_id' => 3,
        ]);

        for ($i = 0; $i < 50; $i++) {
            CoupleSpending::factory()->create([
                'user_id'                     => 3,
                'couple_spending_category_id' => $categories->random()->id,
                'couple_spending_place_id'    => $places->random()->id,
            ]);
        }

    }
}
