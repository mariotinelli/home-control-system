<?php

namespace Tests\Feature\Livewire\Couple\Spending;

use App\Http\Livewire\Couple;
use App\Models\{CoupleSpendingCategory, User};

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->coupleSpendingCategories()->save(
        $this->category = CoupleSpendingCategory::factory()->makeOne()
    );

    $this->user->givePermissionTo('couple_spending_read');

    $this->user->givePermissionTo('couple_spending_create');

    $this->user->givePermissionTo('couple_spending_update');

    $this->user->givePermissionTo('couple_spending_delete');

    actingAs($this->user);

});

it('can render page', function () {

    livewire(Couple\Spending\Index::class)
        ->assertSuccessful();

})->group('canRenderPage');
