<?php

namespace Tests\Feature\Livewire\CoupleSpendingCategories;

use App\Http\Livewire\CoupleSpendingCategories;
use App\Models\{CoupleSpending, CoupleSpendingCategory, User};

use function Pest\Laravel\{actingAs, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->user->givePermissionTo(getUserSilverPermissions());

    $this->user->coupleSpendingCategories()->save(
        $this->category = CoupleSpendingCategory::factory()->create()
    );

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
        'id'      => $this->category->id,
        'user_id' => $this->user->id,
    ]);

});

it('should not be able to delete a couple spending category if it has spending', function () {

    // Arrange
    $this->category->spendings()->save(
        CoupleSpending::factory()->create()
    );

    // Act
    $lw = livewire(CoupleSpendingCategories\Destroy::class, ['category' => $this->category])
        ->call('save');

    // Assert
    $lw->assertHasErrors()
        ->assertNotEmitted('couple-spending-category::deleted');

});
