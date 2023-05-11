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

    $this->user->givePermissionTo(getUserSilverPermissions());

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
        'user_id' => $this->user->id,
        'id'      => $this->coupleSpending->id,
    ]);

});
