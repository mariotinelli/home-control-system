<?php

namespace Tests\Feature\Livewire\CoupleSpendingCategories;

use App\Http\Livewire\CoupleSpendingCategories;
use App\Models\{CoupleSpending, CoupleSpendingCategory, User};

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

it('should not be able to delete a couple spending category if it has spending', function () {

    // Arrange
    CoupleSpending::factory()->create([
        'couple_spending_category_id' => $this->category->id,
    ]);

    // Act
    $lw = livewire(CoupleSpendingCategories\Destroy::class, ['category' => $this->category])
        ->call('save');

    // Assert
    $lw->assertHasErrors()
        ->assertNotEmitted('couple-spending-category::deleted');

});
