<?php

namespace Tests\Feature\Livewire\Couple\Spending;

use App\Http\Livewire\Couple;
use App\Models\{CoupleSpending, User};

use function Pest\Laravel\{actingAs, get};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->coupleSpendingCategories()->save(
        $this->category = CoupleSpending::factory()->makeOne()
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

})->group('renderPage');

it('can redirect to login if not authenticated', function () {

    \Auth::logout();

    get(route('couple.spending.index'))
        ->assertRedirect(route('login'));

})->group('renderPage');

it('can render spending id column in table', function () {

    CoupleSpending::factory()->count(1)->create();

    livewire(Couple\Spending\Index::class)
        ->assertCanRenderTableColumn('id');

})->group('canRenderTableColumn');

it('can render spending category_id column in table', function () {

    CoupleSpending::factory()->count(1)->create();

    livewire(Couple\Spending\Index::class)
        ->assertCanRenderTableColumn('category_id');

})->group('canRenderTableColumn');

it('can render spending description column in table', function () {

    CoupleSpending::factory()->count(1)->create();

    livewire(Couple\Spending\Index::class)
        ->assertCanRenderTableColumn('description');

})->group('canRenderTableColumn');

it('can render spending amount column in table', function () {

    CoupleSpending::factory()->count(1)->create();

    livewire(Couple\Spending\Index::class)
        ->assertCanRenderTableColumn('amount');

})->group('canRenderTableColumn');

it('can render spending amount date in table', function () {

    CoupleSpending::factory()->count(1)->create();

    livewire(Couple\Spending\Index::class)
        ->assertCanRenderTableColumn('date');

})->group('canRenderTableColumn');

it('can display all spending in table', function () {
    // Arrange
    $spending = CoupleSpending::factory()->count(10)->create([
        'user_id'                     => $this->user->id,
        'couple_spending_category_id' => $this->category->id,
    ]);

    // Act
    livewire(Couple\Spending\Index::class)
        ->assertCanSeeTableRecords($spending)
        ->assertCountTableRecords(CoupleSpending::count());
});

it('cannot render page if not has permission', function () {
    $this->user->revokePermissionTo('couple_spending_read');

    livewire(Couple\Spending\Index::class)
        ->assertForbidden();

})->group('cannotHasPermission');
