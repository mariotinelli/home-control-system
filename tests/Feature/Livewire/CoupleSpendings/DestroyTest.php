<?php

namespace Tests\Feature\Livewire\CoupleSpendings;

use App\Http\Livewire\CoupleSpendings;
use App\Models\{CoupleSpending, CoupleSpendingCategory, User};

use function Pest\Laravel\{actingAs, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    $this->category = CoupleSpendingCategory::factory()->create();

    $this->coupleSpending = CoupleSpending::factory()->create([
        'couple_spending_category_id' => $this->category->id,
    ]);

    actingAs($this->user);

});

it('should be able to delete couple spending', function () {

    $lw = livewire(CoupleSpendings\Destroy::class, ['coupleSpending' => $this->coupleSpending])
        ->call('save');

    // Assert
    $lw->assertEmitted('couple-spending::deleted');

    assertDatabaseMissing('couple_spendings', [
        'id' => $this->coupleSpending->id,
    ]);

});
