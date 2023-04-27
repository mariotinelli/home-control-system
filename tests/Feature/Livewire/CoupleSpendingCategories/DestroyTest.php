<?php

namespace Tests\Feature\Livewire\CoupleSpendingCategories;

use App\Http\Livewire\CoupleSpendingCategories;
use App\Models\{CoupleSpendingCategory, User};

use function Pest\Laravel\{actingAs, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->category = CoupleSpendingCategory::factory()->create();

    actingAs($this->user);
});

it('should be able to delete a couple spending category', function () {

    // Act
    $lw = livewire(CoupleSpendingCategories\Destroy::class, ['category' => $this->category])
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('couple-spending-category::deleted');

    assertDatabaseMissing('couple_spending_categories', [
        'id' => $this->category->id,
    ]);

});

todo('should not be able to delete a couple spending category if it has expenses');
