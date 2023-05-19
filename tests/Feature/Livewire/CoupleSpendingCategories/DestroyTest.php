<?php

namespace Tests\Feature\Livewire\CoupleSpendingCategories;

use App\Http\Livewire\CoupleSpendingCategories;
use App\Models\{CoupleSpending, CoupleSpendingCategory, User};

use function Pest\Laravel\{actingAs, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->user->givePermissionTo('couple_spending_category_delete');

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
        'id' => $this->category->id,
    ]);

});

it('should be not able to delete a couple spending category if not owner', function () {

    // Arrange
    $notOwner = User::factory()->create();

    $notOwner->givePermissionTo('couple_spending_category_delete');

    actingAs($notOwner);

    // Act
    livewire(CoupleSpendingCategories\Destroy::class, ['category' => $this->category])
        ->call('save')
        ->assertForbidden();

});

it('should be not able to delete a couple spending category if not has permission', function () {

    // Arrange
    $this->user->revokePermissionTo('couple_spending_category_delete');

    // Act
    livewire(CoupleSpendingCategories\Destroy::class, ['category' => $this->category])
        ->call('save')
        ->assertForbidden();

});

it('should be not able to delete a couple spending category if not authenticated', function () {

    // Arrange
    \Auth::logout();

    // Act
    livewire(CoupleSpendingCategories\Destroy::class, ['category' => $this->category])
        ->call('save')
        ->assertForbidden();

});

it('should be not able to delete a couple spending category if has spending', function () {

    // Arrange
    $this->category->spendings()->save(
        CoupleSpending::factory()->create()
    );

    // Act
    livewire(CoupleSpendingCategories\Destroy::class, ['category' => $this->category])
        ->call('save')
        ->assertForbidden();

});
