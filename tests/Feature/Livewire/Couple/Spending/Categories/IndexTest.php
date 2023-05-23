<?php

namespace Livewire\Couple\Spending\Categories;

use App\Http\Livewire\Couple;
use App\Models\{CoupleSpendingCategory, User};
use Filament\Tables;

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertModelMissing};
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

it('can sort categories by name', function () {
    $categories = CoupleSpendingCategory::factory()->count(10)->create();

    livewire(Couple\Spending\Categories\Index::class)
        ->sortTable('name')
        ->assertCanSeeTableRecords($categories->sortBy('name'), inOrder: true)
        ->sortTable('name', 'desc')
        ->assertCanSeeTableRecords($categories->sortByDesc('name'), inOrder: true);
})->group('canSortTable');

it('can search categories by id', function () {
    $categories = CoupleSpendingCategory::factory()->count(10)->create();

    $id = $categories->first()->id;

    $canSeeCategories = $categories->filter(function ($item) use ($id) {
        return false !== stripos($item->id, $id);
    });

    $cannotSeeCategories = $categories->filter(function ($item) use ($id) {
        return false === stripos($item->id, $id);
    });

    livewire(Couple\Spending\Categories\Index::class)
        ->searchTable($id)
        ->assertCanSeeTableRecords($canSeeCategories)
        ->assertCanNotSeeTableRecords($cannotSeeCategories);
})->group('canSearchTable');

it('can search categories by name', function () {
    $categories = CoupleSpendingCategory::factory()->count(10)->create();

    $name = $categories->first()->name;

    $canSeeCategories = $categories->filter(function ($item) use ($name) {
        return false !== stripos($item->name, $name);
    });

    $cannotSeeCategories = $categories->filter(function ($item) use ($name) {
        return false === stripos($item->name, $name);
    });

    livewire(Couple\Spending\Categories\Index::class)
        ->searchTable($name)
        ->assertCanSeeTableRecords($canSeeCategories)
        ->assertCanNotSeeTableRecords($cannotSeeCategories);
})->group('canSearchTable');

it('can create categories', function () {

    livewire(Couple\Spending\Categories\Index::class)
        ->callTableAction(Tables\Actions\CreateAction::class, data: [
            'name' => $name = fake()->words(asText: true),
        ])
        ->assertHasNoTableActionErrors();

    assertDatabaseHas('couple_spending_categories', [
        'user_id' => $this->user->id,
        'name'    => $name,
    ]);

})->group('tableActions');

it('can edit categories', function () {
    $category = CoupleSpendingCategory::factory()->create();

    livewire(Couple\Spending\Categories\Index::class)
        ->callTableAction(Tables\Actions\EditAction::class, $category, data: [
            'name' => $name = fake()->words(asText: true),
        ])
        ->assertHasNoTableActionErrors();

    expect($category->refresh())
        ->name->toBe($name);
})->group('tableActions');

it('can delete categories', function () {
    $category = CoupleSpendingCategory::factory()->create();

    livewire(Couple\Spending\Categories\Index::class)
        ->callTableAction(Tables\Actions\DeleteAction::class, $category);

    assertModelMissing($category);
})->group('tableActions');

it('can validate category creating data', function () {

    livewire(Couple\Spending\Categories\Index::class)
        ->callTableAction(Tables\Actions\CreateAction::class, data: [
            'name' => null,
        ])
        ->assertHasTableActionErrors(['name' => ['required']]);

})->group('creatingDataValidation');
