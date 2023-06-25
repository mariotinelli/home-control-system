<?php

use App\Http\Livewire\{Couple, Filament};
use App\Models\{MarketItem, MarketItemCategory, User};
use Filament\{Tables};

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertModelMissing, get};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->marketItemCategories()->save(
        $this->category = MarketItemCategory::factory()->makeOne()
    );

    $this->user->givePermissionTo('market_item_read');

    $this->user->givePermissionTo('market_item_create');

    $this->user->givePermissionTo('market_item_update');

    $this->user->givePermissionTo('market_item_delete');

    actingAs($this->user);

});

/* ###################################################################### */
/* RENDER PAGE */
/* ###################################################################### */
it('can render page', function () {

    livewire(Filament\MarketItemResource\Index::class)
        ->assertSuccessful();

})->group('renderPage');

it('can redirect to login if not authenticated', function () {

    \Auth::logout();

    get(route('markets.items.index'))
        ->assertRedirect(route('login'));

})->group('renderPage');

/* ###################################################################### */
/* CAN RENDER TABLE HEADER */
/* ###################################################################### */
it('can render table heading', function () {

    MarketItem::factory()->count(1)->create([
        'user_id'                 => $this->user->id,
        'market_item_category_id' => $this->category->id,
    ]);

    livewire(Filament\MarketItemResource\Index::class)
        ->assertSeeHtml('Itens de Mercado');

})->group('canRenderTableHeader');

it('can render create action button', function () {

    MarketItem::factory()->count(1)->create([
        'user_id'                 => $this->user->id,
        'market_item_category_id' => $this->category->id,
    ]);

    livewire(Filament\MarketItemResource\Index::class)
        ->assertTableActionExists('create');

})->group('canRenderTableHeader');

/* ###################################################################### */
/* CAN RENDER TABLE COLUMNS */
/* ###################################################################### */
it('can render market item id column in table', function () {

    MarketItem::factory()->count(1)->create([
        'user_id'                 => $this->user->id,
        'market_item_category_id' => $this->category->id,
    ]);

    livewire(Filament\MarketItemResource\Index::class)
        ->assertCanRenderTableColumn('id');

})->group('canRenderTableColumns');

it('can render market item name column in table', function () {

    MarketItem::factory()->count(1)->create([
        'user_id'                 => $this->user->id,
        'market_item_category_id' => $this->category->id,
    ]);

    livewire(Filament\MarketItemResource\Index::class)
        ->assertCanRenderTableColumn('name');

})->group('canRenderTableColumns');

it('can render market item category name column in table', function () {

    MarketItem::factory()->count(1)->create([
        'user_id'                 => $this->user->id,
        'market_item_category_id' => $this->category->id,
    ]);

    livewire(Filament\MarketItemResource\Index::class)
        ->assertCanRenderTableColumn('category.name');

})->group('canRenderTableColumns');

it('can render market item type weight column in table', function () {

    MarketItem::factory()->count(1)->create([
        'user_id'                 => $this->user->id,
        'market_item_category_id' => $this->category->id,
    ]);

    livewire(Filament\MarketItemResource\Index::class)
        ->assertCanRenderTableColumn('type_weight');

})->group('canRenderTableColumns');

it('can render market item weight column in table', function () {

    MarketItem::factory()->count(1)->create([
        'user_id'                 => $this->user->id,
        'market_item_category_id' => $this->category->id,
    ]);

    livewire(Filament\MarketItemResource\Index::class)
        ->assertCanRenderTableColumn('weight');

})->group('canRenderTableColumns');

/* ###################################################################### */
/* RENDER DEFAULT */
/* ###################################################################### */
it('can display all my market items in table', function () {

    // Arrange
    $myMarketItems = MarketItem::factory()->count(5)->create([
        'user_id'                 => $this->user->id,
        'market_item_category_id' => $this->category->id,
    ]);

    $otherMarketItems = MarketItem::factory()->count(5)->create();

    // Act
    livewire(Filament\MarketItemResource\Index::class)
        ->assertCanSeeTableRecords($myMarketItems)
        ->assertCountTableRecords(MarketItem::where('user_id', $this->user->id)->count())
        ->assertCanNotSeeTableRecords($otherMarketItems);

})->group('renderDefault');

it('market items are sorted by default in desc order', function () {

    // Arrange
    $marketItem = MarketItem::factory()->count(10)->create([
        'user_id'                 => $this->user->id,
        'market_item_category_id' => $this->category->id,
    ]);

    livewire(Filament\MarketItemResource\Index::class)
        ->assertCanSeeTableRecords($marketItem->sortByDesc('id'), inOrder: true);

})->group('renderDefault');

/* ###################################################################### */
/* SORT COLUMN TABLE */
/* ###################################################################### */
it('can sort market item by id', function () {

    // Arrange
    $marketItem = MarketItem::factory()->count(10)->create([
        'user_id'                 => $this->user->id,
        'market_item_category_id' => $this->category->id,
    ]);

    livewire(Filament\MarketItemResource\Index::class)
        ->sortTable('id')
        ->assertCanSeeTableRecords($marketItem->sortBy('id'), inOrder: true)
        ->sortTable('id', 'desc')
        ->assertCanSeeTableRecords($marketItem->sortByDesc('id'), inOrder: true);

})->group('canSortTable');

it('can sort market item by name', function () {

    // Arrange
    $marketItem = MarketItem::factory()->count(10)->create([
        'user_id'                 => $this->user->id,
        'market_item_category_id' => $this->category->id,
    ]);

    livewire(Filament\MarketItemResource\Index::class)
        ->sortTable('name')
        ->assertCanSeeTableRecords($marketItem->sortBy('name'), inOrder: true)
        ->sortTable('name', 'desc')
        ->assertCanSeeTableRecords($marketItem->sortByDesc('name'), inOrder: true);

})->group('canSortTable');

it('can sort market item by category name', function () {

    // Arrange
    $categories = MarketItemCategory::factory()->count(2)->create([
        'user_id' => $this->user->id,
    ]);

    $marketItem = MarketItem::factory()->count(5)->create([
        'user_id'                 => $this->user->id,
        'market_item_category_id' => $categories->random()->id,
    ]);

    // Act
    livewire(Filament\MarketItemResource\Index::class)
        ->sortTable('category.name')
        ->assertCanSeeTableRecords($marketItem->sortBy('category.name'), inOrder: true)
        ->sortTable('category.name', 'desc')
        ->assertCanSeeTableRecords($marketItem->sortByDesc('category.name'), inOrder: true);

})->group('canSortTable');

it('can sort market item by type weight', function () {

    // Arrange
    $marketItem = MarketItem::factory()->count(10)->create([
        'user_id'                 => $this->user->id,
        'market_item_category_id' => $this->category->id,
    ]);

    livewire(Filament\MarketItemResource\Index::class)
        ->sortTable('type_weight')
        ->assertCanSeeTableRecords($marketItem->sortBy('type_weight'), inOrder: true)
        ->sortTable('type_weight', 'desc')
        ->assertCanSeeTableRecords($marketItem->sortByDesc('type_weight'), inOrder: true);

})->group('canSortTable');

it('can sort market item by weight', function () {

    // Arrange
    $marketItem = MarketItem::factory()->count(10)->create([
        'user_id'                 => $this->user->id,
        'market_item_category_id' => $this->category->id,
    ]);

    livewire(Filament\MarketItemResource\Index::class)
        ->sortTable('weight')
        ->assertCanSeeTableRecords($marketItem->sortBy('weight'), inOrder: true)
        ->sortTable('weight', 'desc')
        ->assertCanSeeTableRecords($marketItem->sortByDesc('weight'), inOrder: true);

})->group('canSortTable');

/* ###################################################################### */
/* HEADER SEARCH TABLE */
/* ###################################################################### */
it('can search market item by id', function () {

    // Arrange
    $marketItem = MarketItem::factory()->count(5)->create([
        'user_id'                 => $this->user->id,
        'market_item_category_id' => $this->category->id,
        'weight'                  => 3333,
    ]);

    $search = $marketItem->first()->id;

    $canSeeMarketItems = $marketItem->filter(function ($item) use ($search) {
        return false !== stripos($item->id, $search);
    });

    $cannotSeeMarketItems = $marketItem->filter(function ($item) use ($search) {
        return false === stripos($item->id, $search);
    });

    livewire(Filament\MarketItemResource\Index::class)
        ->searchTable($search)
        ->assertCanSeeTableRecords($canSeeMarketItems)
        ->assertCanNotSeeTableRecords($cannotSeeMarketItems);

})->group('canHeaderSearchTable');

it('can search market item by name', function () {

    // Arrange
    $marketItem = MarketItem::factory()->count(5)->create([
        'user_id'                 => $this->user->id,
        'market_item_category_id' => $this->category->id,
        'weight'                  => 3333,
    ]);

    $search = $marketItem->first()->name;

    $canSeeMarketItems = $marketItem->filter(function ($item) use ($search) {
        return false !== stripos($item->name, $search);
    });

    $cannotSeeMarketItems = $marketItem->filter(function ($item) use ($search) {
        return false === stripos($item->name, $search);
    });

    livewire(Filament\MarketItemResource\Index::class)
        ->searchTable($search)
        ->assertCanSeeTableRecords($canSeeMarketItems)
        ->assertCanNotSeeTableRecords($cannotSeeMarketItems);

})->group('canHeaderSearchTable');

it('can search market item by category name', function () {

    // Arrange
    $categories = MarketItemCategory::factory()->count(5)->create([
        'user_id' => $this->user->id,
    ]);

    $marketItem = MarketItem::factory()->count(5)->create([
        'user_id'                 => $this->user->id,
        'market_item_category_id' => $categories->random()->id,
    ]);

    $search = $marketItem->first()->category()->first()->name;

    $canSeeMarketItems = $marketItem->filter(function ($item) use ($search) {
        return false !== stripos($item->category->name, $search);
    });

    $cannotSeeMarketItems = $marketItem->filter(function ($item) use ($search) {
        return false === stripos($item->category->name, $search);
    });

    livewire(Filament\MarketItemResource\Index::class)
        ->searchTable($search)
        ->assertCanSeeTableRecords($canSeeMarketItems)
        ->assertCanNotSeeTableRecords($cannotSeeMarketItems);

})->group('canHeaderSearchTable');

it('can search market item by type weight', function () {

    // Arrange
    $category = MarketItemCategory::factory()->createOne([
        'user_id' => $this->user->id,
        'name'    => 'market_item_category_id',
    ]);

    $marketItem = MarketItem::factory()->count(5)->create([
        'user_id'                 => $this->user->id,
        'market_item_category_id' => $category->id,
    ]);

    $search = $marketItem->first()->weight;

    $canSeeMarketItems = $marketItem->filter(function ($item) use ($search) {
        return false !== stripos($item->weight, $search);
    });

    $cannotSeeMarketItems = $marketItem->filter(function ($item) use ($search) {
        return false === stripos($item->weight, $search);
    });

    livewire(Filament\MarketItemResource\Index::class)
        ->searchTable($search)
        ->assertCanSeeTableRecords($canSeeMarketItems)
        ->assertCanNotSeeTableRecords($cannotSeeMarketItems);

})->group('canHeaderSearchTable');

it('can search market item by weight', function () {

    // Arrange
    $marketItem = MarketItem::factory()->count(5)->create([
        'user_id'                 => $this->user->id,
        'market_item_category_id' => $this->category->id,
    ]);

    $search = $marketItem->first()->weight;

    $canSeeMarketItems = $marketItem->filter(function ($item) use ($search) {
        return false !== stripos($item->weight, $search);
    });

    $cannotSeeMarketItems = $marketItem->filter(function ($item) use ($search) {
        return false === stripos($item->weight, $search);
    });

    livewire(Filament\MarketItemResource\Index::class)
        ->searchTable($search)
        ->assertCanSeeTableRecords($canSeeMarketItems)
        ->assertCanNotSeeTableRecords($cannotSeeMarketItems);

})->group('canHeaderSearchTable');

/* ###################################################################### */
/* RENDER FORM */
/* ###################################################################### */
it('has a form', function () {

    livewire(Filament\MarketItemResource\Index::class)
        ->assertFormExists();

})->group('renderForm');

/* ###################################################################### */
/* RENDER FORM FIELDS */
/* ###################################################################### */
it('has a name field', function () {

    livewire(Filament\MarketItemResource\Index::class)
        ->assertFormFieldExists('name');

})->group('renderFormFields');

it('has a category field', function () {

    livewire(Filament\MarketItemResource\Index::class)
        ->assertFormFieldExists('market_item_category_id');

})->group('renderFormFields');

it('has a type weight field', function () {

    livewire(Filament\MarketItemResource\Index::class)
        ->assertFormFieldExists('type_weight');

})->group('renderFormFields');

it('has a weight field', function () {

    livewire(Filament\MarketItemResource\Index::class)
        ->assertFormFieldExists('weight');

})->group('renderFormFields');

/* ###################################################################### */
/* VALIDATE FORM FIELDS - CREATING */
/* ###################################################################### */

it('can validate name in creating', function () {

    // Required
    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\CreateAction::class, data: [
            'name' => null,
        ])
        ->assertHasTableActionErrors(['name' => ['required']]);

    // String
    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\CreateAction::class, data: [
            'name' => 12,
        ])
        ->assertHasTableActionErrors(['name' => ['string']]);

    // Min 3
    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\CreateAction::class, data: [
            'name' => 'aa',
        ])
        ->assertHasTableActionErrors(['name' => ['min']]);

    // Max 150
    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\CreateAction::class, data: [
            'name' => str_repeat('a', 151),
        ])
        ->assertHasTableActionErrors(['name' => ['max']]);

    // Rule unique is only for owner market item
    $marketItem = MarketItem::factory()->create([
        'user_id' => $this->user->id,
    ]);

    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\CreateAction::class, data: [
            'name' => $marketItem->name,
        ])
        ->assertHasTableActionErrors(['name' => ['unique']]);

    // Ignore rule unique only for not market owner
    $marketItem = MarketItem::factory()->create([
        'user_id' => User::factory()->create()->id,
    ]);

    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\CreateAction::class, data: [
            'name' => $marketItem->name,
        ])
        ->assertHasNoTableActionErrors(['name' => ['unique']]);

})->group('creatingDataValidation');

it('can validate category in creating', function () {
    // Arrange
    MarketItemCategory::factory()->count(5)->create([
        'user_id' => $this->user->id,
    ]);

    // Required
    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\CreateAction::class, data: [
            'market_item_category_id' => null,
        ])
        ->assertHasTableActionErrors(['market_item_category_id' => ['required']]);

    // Exists
    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\CreateAction::class, data: [
            'market_item_category_id' => MarketItemCategory::count() + 1,
        ])
        ->assertHasTableActionErrors(['market_item_category_id' => ['exists']]);

    // Belongs to user
    $categoryNotOwner = MarketItemCategory::factory()->createOne();

    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\CreateAction::class, data: [
            'market_item_category_id' => $categoryNotOwner->id,
        ])
        ->assertForbidden();

})->group('creatingDataValidation');

it('can validate type_weight in creating', function () {

    // Required
    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\CreateAction::class, data: [
            'type_weight' => null,
        ])
        ->assertHasTableActionErrors(['type_weight' => ['required']]);

    // String
    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\CreateAction::class, data: [
            'type_weight' => 123,
        ])
        ->assertHasTableActionErrors(['type_weight' => ['string']]);

    // In
    $typeWeightNotIn = 'not-in';

    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\CreateAction::class, data: [
            'type_weight' => $typeWeightNotIn,
        ])
        ->assertHasTableActionErrors(['type_weight' => ['in']]);

})->group('creatingDataValidation');

it('can validate weight in creating', function () {

    // Required
    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\CreateAction::class, data: [
            'weight' => null,
        ])
        ->assertHasTableActionErrors(['weight' => ['required']]);

})->group('creatingDataValidation');

/* ###################################################################### */
/* VALIDATE FORM FIELDS - UPDATING */
/* ###################################################################### */
it('can validate name in updating', function () {
    // Arrange
    $marketItem = MarketItem::factory()->create([
        'user_id' => $this->user->id,
    ]);

    // Required
    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\EditAction::class, $marketItem, data: [
            'name' => null,
        ])
        ->assertHasTableActionErrors(['name' => ['required']]);

    // String
    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\EditAction::class, $marketItem, data: [
            'name' => 12,
        ])
        ->assertHasTableActionErrors(['name' => ['string']]);

    // Min 3
    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\EditAction::class, $marketItem, data: [
            'name' => 'aa',
        ])
        ->assertHasTableActionErrors(['name' => ['min']]);

    // Max 150
    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\EditAction::class, $marketItem, data: [
            'name' => str_repeat('a', 151),
        ])
        ->assertHasTableActionErrors(['name' => ['max']]);

    // Rule unique is only for owner market item
    $marketItem2 = MarketItem::factory()->create([
        'user_id' => $this->user->id,
    ]);

    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\EditAction::class, $marketItem, data: [
            'name' => $marketItem2->name,
        ])
        ->assertHasTableActionErrors(['name' => ['unique']]);

    // Ignore unique rule for current market item
    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\EditAction::class, $marketItem, data: [
            'name' => $marketItem->name,
        ])
        ->assertHasNoTableActionErrors(['name' => ['unique']]);

    // Ignore rule unique for not market item owner
    $marketItem3 = MarketItem::factory()->create([
        'user_id' => User::factory()->create()->id,
    ]);

    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\CreateAction::class, data: [
            'name' => $marketItem3->name,
        ])
        ->assertHasNoTableActionErrors(['name' => ['unique']]);

})->group('updatingDataValidation');

it('can validate category in updating', function () {
    // Arrange
    $marketItem = MarketItem::factory()->createOne([
        'user_id'                 => $this->user->id,
        'market_item_category_id' => $this->category->id,
    ]);

    MarketItemCategory::factory()->count(5)->create([
        'user_id' => $this->user->id,
    ]);

    // Required
    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\EditAction::class, $marketItem, data: [
            'market_item_category_id' => null,
        ])
        ->assertHasTableActionErrors(['market_item_category_id' => ['required']]);

    // Exists
    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\EditAction::class, $marketItem, data: [
            'market_item_category_id' => MarketItemCategory::count() + 1,
        ])
        ->assertHasTableActionErrors(['market_item_category_id' => ['exists']]);

    // Belongs to user
    $categoryNotOwner = MarketItemCategory::factory()->createOne();

    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\EditAction::class, $marketItem, data: [
            'market_item_category_id' => $categoryNotOwner->id,
        ])
        ->assertForbidden();

})->group('updatingDataValidation');

it('can validate type_weight in updating', function () {
    // Arrange
    $marketItem = MarketItem::factory()->createOne([
        'user_id'                 => $this->user->id,
        'market_item_category_id' => $this->category->id,
    ]);

    // Required
    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\EditAction::class, $marketItem, data: [
            'type_weight' => null,
        ])
        ->assertHasTableActionErrors(['type_weight' => ['required']]);

    // String
    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\EditAction::class, $marketItem, data: [
            'type_weight' => 123,
        ])
        ->assertHasTableActionErrors(['type_weight' => ['string']]);

    // In
    $typeWeightNotIn = 'not-in';

    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\EditAction::class, $marketItem, [
            'type_weight' => $typeWeightNotIn,
        ])
        ->assertHasTableActionErrors(['type_weight' => ['in']]);

})->group('updatingDataValidation');

it('can validate weight in updating', function () {
    // Arrange
    $marketItem = MarketItem::factory()->createOne([
        'user_id'                 => $this->user->id,
        'market_item_category_id' => $this->category->id,
    ]);

    // Required
    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\EditAction::class, $marketItem, data: [
            'weight' => null,
        ])
        ->assertHasTableActionErrors(['weight' => ['required']]);

})->group('updatingDataValidation');

/* ###################################################################### */
/* TABLE ACTIONS CRUD */
/* ###################################################################### */
it('can render all table row actions', function () {

    MarketItem::factory()->count(1)->create([
        'user_id' => $this->user->id,
    ]);

    livewire(Filament\MarketItemResource\Index::class)
        ->assertTableActionExists(Tables\Actions\EditAction::class);

    livewire(Filament\MarketItemResource\Index::class)
        ->assertTableActionExists(Tables\Actions\DeleteAction::class);

})->group('tableActionsCrud');

it('can display correctly market item information in edit action', function () {

    // Arrange
    $marketItem = MarketItem::factory()->createOne([
        'user_id'                 => $this->user->id,
        'market_item_category_id' => $this->category->id,
    ]);

    // Act
    livewire(Filament\MarketItemResource\Index::class)
        ->mountTableAction(Tables\Actions\EditAction::class, $marketItem)
        ->assertTableActionDataSet([
            'name'                    => $marketItem->name,
            'market_item_category_id' => $marketItem->category->id,
            'type_weight'             => $marketItem->type_weight,
            'weight'                  => number_format($marketItem->weight, 2, ',', '.'), // 1.000,00
        ]);

})->group('tableActionsCrud');

it('can create market items', function () {
    // Arrange
    $marketItem = MarketItem::factory()->makeOne([
        'user_id' => $this->user->id,
    ]);

    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\CreateAction::class, data: [
            'name'                    => $marketItem->name,
            'market_item_category_id' => $this->category->id,
            'type_weight'             => $marketItem->type_weight,
            'weight'                  => $marketItem->weight,
        ])
        ->assertHasNoTableActionErrors();

    assertDatabaseHas('market_items', [
        'user_id'                 => $this->user->id,
        'name'                    => $marketItem->name,
        'market_item_category_id' => $this->category->id,
        'type_weight'             => $marketItem->type_weight,
        'weight'                  => $marketItem->weight,
    ]);

})->group('tableActionsCrud');

it('can edit market items', function () {
    // Arrange
    $marketItem = MarketItem::factory()->createOne([
        'user_id'                 => $this->user->id,
        'market_item_category_id' => $this->category->id,
    ]);

    $newCategory = MarketItemCategory::factory()->createOne([
        'user_id' => $this->user->id,
    ]);

    $newData = MarketItem::factory()->makeOne([
        'user_id' => $this->user->id,
    ]);

    // Act
    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\EditAction::class, $marketItem, data: [
            'name'                    => $newData->name,
            'market_item_category_id' => $newCategory->id,
            'type_weight'             => $newData->type_weight,
            'weight'                  => $newData->weight,
        ])
        ->assertHasNoTableActionErrors();

    assertDatabaseHas('market_items', [
        'user_id'                 => $this->user->id,
        'name'                    => $newData->name,
        'market_item_category_id' => $newCategory->id,
        'type_weight'             => $newData->type_weight,
        'weight'                  => $newData->weight,
    ]);

})->group('tableActionsCrud');

it('can delete market items', function () {

    $marketItem = MarketItem::factory()->createOne([
        'user_id'                 => $this->user->id,
        'market_item_category_id' => $this->category->id,
    ]);

    livewire(Filament\MarketItemResource\Index::class)
        ->callTableAction(Tables\Actions\DeleteAction::class, $marketItem);

    assertModelMissing($marketItem);

})->group('tableActionsCrud');

/* ###################################################################### */
/* CANNOT HAS PERMISSION */
/* ###################################################################### */
it('cannot render page if not has permission', function () {
    $this->user->revokePermissionTo('market_item_read');

    livewire(Filament\MarketItemResource\Index::class)
        ->assertForbidden();

})->group('cannotHasPermission');

it('cannot render create action button if not has permission', function () {
    // Arrange
    $this->user->revokePermissionTo('market_item_create');

    // Act
    livewire(Filament\MarketItemResource\Index::class)
        ->assertDontSeeHtml('Criar gasto');

})->group('cannotHasPermission');

it('can disable edit action button if not has permission', function () {

    // Arrange
    $this->user->coupleSpendings()->save(
        $marketItem = MarketItem::factory()->makeOne([
            'market_item_category_id' => $this->category->id,
        ])
    );

    $this->user->revokePermissionTo('market_item_update');

    // Act
    livewire(Filament\MarketItemResource\Index::class)
        ->assertTableActionDisabled(Tables\Actions\EditAction::class, $marketItem);

})->group('cannotHasPermission');

it('can disable delete action button if not has permission', function () {

    // Arrange
    $this->user->coupleSpendings()->save(
        $marketItem = MarketItem::factory()->makeOne([
            'market_item_category_id' => $this->category->id,
        ])
    );

    $this->user->revokePermissionTo('market_item_delete');

    // Act
    livewire(Filament\MarketItemResource\Index::class)
        ->assertTableActionDisabled(Tables\Actions\DeleteAction::class, $marketItem);

})->group('cannotHasPermission');

/* ###################################################################### */
/* CANNOT OWNER */
/* ###################################################################### */
it('can disable edit action button if not is owner', function () {

    // Arrange
    $marketItem = MarketItem::factory()->create();

    // Act
    livewire(Filament\MarketItemResource\Index::class)
        ->assertTableActionDisabled(Tables\Actions\EditAction::class, $marketItem);

})->group('cannotOwner');

it('can disable delete action button if not is owner', function () {

    // Arrange
    $marketItem = MarketItem::factory()->create();

    // Act
    livewire(Filament\MarketItemResource\Index::class)
        ->assertTableActionDisabled(Tables\Actions\DeleteAction::class, $marketItem);

})->group('cannotOwner');
