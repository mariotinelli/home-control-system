<?php

namespace Livewire\Couple\Spending\Categories;

use App\Http\Livewire\Couple;
use App\Models\{CoupleSpendingCategory, User};
use Filament\Tables;

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertModelMissing, get};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo('couple_spending_category_read');

    $this->user->givePermissionTo('couple_spending_category_create');

    $this->user->givePermissionTo('couple_spending_category_update');

    $this->user->givePermissionTo('couple_spending_category_delete');

    actingAs($this->user);

});

todo('criar testes para permissões, autenticação e owner');

it('can render page', function () {

    livewire(Couple\Spending\Categories\Index::class)
        ->assertSuccessful();

})->group('canRenderPage');

it('can redirect to login if not authenticated', function () {

    \Auth::logout();

    get(route('couple.spending.categories.index'))
        ->assertRedirect(route('login'));

})->group('canRenderPage');

it('can display all categories in table', function () {
    $categories = CoupleSpendingCategory::factory()->count(10)->create();

    livewire(Couple\Spending\Categories\Index::class)
        ->assertCanSeeTableRecords($categories)
        ->assertCountTableRecords($categories->count());
});

it('can render table heading', function () {

    CoupleSpendingCategory::factory()->count(1)->create();

    livewire(Couple\Spending\Categories\Index::class)
        ->assertSeeHtml('Categorias de Gastos');

})->group('canRenderTableHeader');

it('can render create action button', function () {

    CoupleSpendingCategory::factory()->count(1)->create();

    livewire(Couple\Spending\Categories\Index::class)
        ->assertTableActionExists('create');

})->group('canRenderTableHeader');

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
    // Arrange
    $category = CoupleSpendingCategory::factory()->createOne([
        'user_id' => $this->user->id,
    ]);

    livewire(Couple\Spending\Categories\Index::class)
        ->callTableAction(Tables\Actions\EditAction::class, $category, data: [
            'name' => $name = fake()->words(asText: true),
        ])
        ->assertHasNoTableActionErrors();

    expect($category->refresh())
        ->name->toBe($name);

})->group('tableActions');

it('can delete categories', function () {

    $category = CoupleSpendingCategory::factory()->createOne([
        'user_id' => $this->user->id,
    ]);

    livewire(Couple\Spending\Categories\Index::class)
        ->callTableAction(Tables\Actions\DeleteAction::class, $category);

    assertModelMissing($category);

})->group('tableActions');

it('can render all table row actions', function () {

    CoupleSpendingCategory::factory()->count(1)->create([
        'user_id' => $this->user->id,
    ]);

    livewire(Couple\Spending\Categories\Index::class)
        ->assertTableActionExists(Tables\Actions\EditAction::class);

    livewire(Couple\Spending\Categories\Index::class)
        ->assertTableActionExists(Tables\Actions\DeleteAction::class);

})->group('tableActions');

it('can display correctly category information in edit action', function () {
    $category = CoupleSpendingCategory::factory()->create([
        'user_id' => $this->user->id,
    ]);

    livewire(Couple\Spending\Categories\Index::class)
        ->callTableAction(Tables\Actions\EditAction::class, $category)
        ->assertTableActionDataSet([
            'title' => $category->title,
        ]);
})->group('tableActions');

it('can validate category name in creating', function () {

    // Required
    livewire(Couple\Spending\Categories\Index::class)
        ->callTableAction(Tables\Actions\CreateAction::class, data: [
            'name' => null,
        ])
        ->assertHasTableActionErrors(['name' => ['required']]);

    // String
    livewire(Couple\Spending\Categories\Index::class)
        ->callTableAction(Tables\Actions\CreateAction::class, data: [
            'name' => 12,
        ])
        ->assertHasTableActionErrors(['name' => ['string']]);

    // Min
    livewire(Couple\Spending\Categories\Index::class)
        ->callTableAction(Tables\Actions\CreateAction::class, data: [
            'name' => 'a',
        ])
        ->assertHasTableActionErrors(['name' => ['min']]);

    // Max
    livewire(Couple\Spending\Categories\Index::class)
        ->callTableAction(Tables\Actions\CreateAction::class, data: [
            'name' => str_repeat('a', 256),
        ])
        ->assertHasTableActionErrors(['name' => ['max']]);

    // Unique
    $category = CoupleSpendingCategory::factory()->create([
        'user_id' => $this->user->id,
    ]);

    livewire(Couple\Spending\Categories\Index::class)
        ->callTableAction(Tables\Actions\CreateAction::class, data: [
            'name' => $category->name,
        ])
        ->assertHasTableActionErrors(['name' => ['unique']]);

})->group('creatingDataValidation');

it('can validate category name in updating', function () {
    // Arrange
    $category = CoupleSpendingCategory::factory()->create([
        'user_id' => $this->user->id,
    ]);

    // Required
    livewire(Couple\Spending\Categories\Index::class)
        ->callTableAction(Tables\Actions\EditAction::class, $category, data: [
            'name' => null,
        ])
        ->assertHasTableActionErrors(['name' => ['required']]);

    // String
    livewire(Couple\Spending\Categories\Index::class)
        ->callTableAction(Tables\Actions\EditAction::class, $category, data: [
            'name' => 12,
        ])
        ->assertHasTableActionErrors(['name' => ['string']]);

    // Min
    livewire(Couple\Spending\Categories\Index::class)
        ->callTableAction(Tables\Actions\EditAction::class, $category, data: [
            'name' => 'a',
        ])
        ->assertHasTableActionErrors(['name' => ['min']]);

    // Max
    livewire(Couple\Spending\Categories\Index::class)
        ->callTableAction(Tables\Actions\EditAction::class, $category, data: [
            'name' => str_repeat('a', 256),
        ])
        ->assertHasTableActionErrors(['name' => ['max']]);

    // Unique
    $category2 = CoupleSpendingCategory::factory()->create();

    livewire(Couple\Spending\Categories\Index::class)
        ->callTableAction(Tables\Actions\EditAction::class, $category, data: [
            'name' => $category2->name,
        ])
        ->assertHasTableActionErrors(['name' => ['unique']]);

    // Ignore unique rule for current category
    livewire(Couple\Spending\Categories\Index::class)
        ->callTableAction(Tables\Actions\EditAction::class, $category, data: [
            'name' => $category->name,
        ])
        ->assertHasNoTableActionErrors(['name' => ['unique']]);

})->group('updatingDataValidation');

it('cannot render page if not has permission', function () {

    $this->user->revokePermissionTo('couple_spending_category_read');

    livewire(Couple\Spending\Categories\Index::class)
        ->assertForbidden();

})->group('cannotHasPermission');

it('cannot render create action button if not has permission', function () {
    // Arrange
    $this->user->revokePermissionTo('couple_spending_category_create');

    CoupleSpendingCategory::factory()->count(1)->create();

    // Act
    livewire(Couple\Spending\Categories\Index::class)
        ->assertDontSeeHtml('Criar categoria');

})->group('cannotHasPermission');

it('can disable edit action button if not has permission', function () {
    // Arrange
    $this->user->revokePermissionTo('couple_spending_category_update');

    CoupleSpendingCategory::factory()->count(1)->create();

    // Act
    livewire(Couple\Spending\Categories\Index::class)
        ->assertTableActionDisabled(Tables\Actions\EditAction::class);

})->group('cannotHasPermission');

it('can disable delete action button if not has permission', function () {
    // Arrange
    $this->user->revokePermissionTo('couple_spending_category_update');

    CoupleSpendingCategory::factory()->count(1)->create();

    // Act
    livewire(Couple\Spending\Categories\Index::class)
        ->assertTableActionDisabled(Tables\Actions\DeleteAction::class);

})->group('cannotHasPermission');
