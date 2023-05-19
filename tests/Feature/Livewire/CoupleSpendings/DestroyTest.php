<?php

namespace Tests\Feature\Livewire\CoupleSpendings;

use App\Http\Livewire\CoupleSpendings;
use App\Models\{CoupleSpending, CoupleSpendingCategory, User};

use function Pest\Laravel\{actingAs, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo('couple_spending_delete');

    $this->user->coupleSpendingCategories()->save(
        $this->category = CoupleSpendingCategory::factory()->create()
    );

    $this->user->coupleSpendings()->save(
        $this->coupleSpending = $this->category->spendings()->save(
            CoupleSpending::factory()->create()
        )
    );

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

it('should be not able to delete couple spending if not owner', function () {

    // Arrange
    $notOwner = User::factory()->create();

    $notOwner->givePermissionTo('couple_spending_delete');

    actingAs($notOwner);

    // Act
    livewire(CoupleSpendings\Destroy::class, ['coupleSpending' => $this->coupleSpending])
        ->call('save')
        ->assertForbidden();

});

it('should be not able to delete couple spending if not has permission', function () {

    // Arrange
    $this->user->revokePermissionTo('couple_spending_delete');

    // Act
    livewire(CoupleSpendings\Destroy::class, ['coupleSpending' => $this->coupleSpending])
        ->call('save')
        ->assertForbidden();

});

it('should be not able to delete couple spending if not authenticated', function () {

    // Arrange
    \Auth::logout();

    // Act
    livewire(CoupleSpendings\Destroy::class, ['coupleSpending' => $this->coupleSpending])
        ->call('save')
        ->assertForbidden();

});
