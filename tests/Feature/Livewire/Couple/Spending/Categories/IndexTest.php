<?php

namespace Livewire\Couple\Spending\Categories;

use App\Http\Livewire\Couple;
use App\Models\{CoupleSpendingCategory, User};

use function Pest\Laravel\{actingAs};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo('couple_spending_category_create');

    actingAs($this->user);

});

it('can render page', function () {
    livewire(Couple\Spending\Categories\Index::class)
        ->assertSuccessful();
});

it('can display all categories in table', function () {
    $categories = CoupleSpendingCategory::factory()->count(10)->create();

    livewire(Couple\Spending\Categories\Index::class)
        ->assertCanSeeTableRecords($categories)
        ->assertCountTableRecords($categories->count());
});

it('can render category id table column', function () {

    CoupleSpendingCategory::factory()->count(1)->create();

    livewire(Couple\Spending\Categories\Index::class)
        ->assertCanRenderTableColumn('id');

})->group('canRenderTableColumn');

it('can render category name table column', function () {

    CoupleSpendingCategory::factory()->count(1)->create();

    livewire(Couple\Spending\Categories\Index::class)
        ->assertCanRenderTableColumn('name');

})->group('canRenderTableColumn');

it('categories are sorted by default in desc order', function () {
    $categories = CoupleSpendingCategory::factory()->count(10)->create();

    livewire(Couple\Spending\Categories\Index::class)
        ->assertCanSeeTableRecords($categories->sortByDesc('id'), inOrder: true);
});

it('can sort categories by id', function () {
    $categories = CoupleSpendingCategory::factory()->count(10)->create();

    livewire(Couple\Spending\Categories\Index::class)
        ->sortTable('id')
        ->assertCanSeeTableRecords($categories->sortBy('id'), inOrder: true)
        ->sortTable('id', 'desc')
        ->assertCanSeeTableRecords($categories->sortByDesc('id'), inOrder: true);
})->group('canSortTable');

todo('can sort categories by name', function () {
    $categories = CoupleSpendingCategory::factory()->count(10)->create();

    livewire(Couple\Spending\Categories\Index::class)
        ->sortTable('name')
        ->assertCanSeeTableRecords($categories->sortBy('name'), inOrder: true)
        ->sortTable('name', 'desc')
        ->assertCanSeeTableRecords($categories->sortByDesc('name'), inOrder: true);
});
