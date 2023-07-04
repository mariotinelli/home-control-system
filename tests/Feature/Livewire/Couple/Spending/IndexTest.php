<?php

use App\Http\Livewire\Couple;
use App\Models\{CoupleSpending, CoupleSpendingCategory, CoupleSpendingPlace, User};
use Filament\Tables;

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertModelMissing, get};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->category = CoupleSpendingCategory::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->place = CoupleSpendingPlace::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->user->givePermissionTo('couple_spending_read');
    $this->user->givePermissionTo('couple_spending_create');
    $this->user->givePermissionTo('couple_spending_update');
    $this->user->givePermissionTo('couple_spending_delete');

    actingAs($this->user);

});

/* ###################################################################### */
/* RENDER PAGE */
/* ###################################################################### */
it('can render page', function () {

    get(route('couple.spending.index'))
        ->assertSuccessful();

})->group('renderPage');

it('can render livewire component', function () {

    get(route('couple.spending.index'))
        ->assertSeeLivewire(Couple\Spending\Index::class);

})->group('renderPage');

/* ###################################################################### */
/* RENDER VALUES */
/* ###################################################################### */
it('can get place with more spending', function () {

    // Arrange
    $places = CoupleSpendingPlace::factory()->count(3)->create([
        'user_id' => $this->user->id,
    ]);

    foreach ($places as $place) {
        $place->spendings()->save(CoupleSpending::factory()->make([
            'user_id' => $this->user->id,
            'amount'  => 100,
        ]));
    }

    $places->last()->spendings()->save(
        CoupleSpending::factory()->make([
            'user_id' => $this->user->id,
            'amount'  => 1000,
        ])
    );

    livewire(Couple\Spending\Index::class)
        ->assertSet('placeWithMoreSpending.couple_spending_place_id', $places->last()->id)
        ->assertSet('placeWithMoreSpending.total', 1100);

})->group('renderValues');

it('can get category with more spending', function () {

    // Arrange
    $categories = CoupleSpendingCategory::factory()->count(3)->create([
        'user_id' => $this->user->id,
    ]);

    foreach ($categories as $category) {
        $category->spendings()->save(CoupleSpending::factory()->make([
            'user_id' => $this->user->id,
            'amount'  => 100,
        ]));
    }

    $categories->last()->spendings()->save(
        CoupleSpending::factory()->make([
            'user_id' => $this->user->id,
            'amount'  => 1000,
        ])
    );

    livewire(Couple\Spending\Index::class)
        ->assertSet('categoryWithMoreSpending.couple_spending_category_id', $categories->last()->id)
        ->assertSet('categoryWithMoreSpending.total', 1100);

})->group('renderValues');

/* ###################################################################### */
/* UNAUTHENTICATED */
/* ###################################################################### */
it('can redirect to login if not authenticated', function () {

    Auth::logout();

    get(route('couple.spending.index'))
        ->assertRedirect(route('login'));

})->group('unauthenticated');

/* ###################################################################### */
/* CAN RENDER PAGE COMPONENTS */
/* ###################################################################### */
it('can render page header', function () {

    livewire(Couple\Spending\Index::class)
        ->assertSeeHtml('Gastos');

})->group('canRenderPageComponents');

it('can render the creation livewire component', function () {

    livewire(Couple\Spending\Index::class)
        ->assertSeeLivewire(Couple\Spending\Create::class);

})->group('canRenderPageComponents');

/* ###################################################################### */
/* CAN RENDER CHART COMPONENTS */
/* ###################################################################### */
it('can render chart ByMonthByCategory', function () {

    livewire(Couple\Spending\Index::class)
        ->assertSeeLivewire(Couple\Spending\Charts\ByMonthByCategory::class);

})->group('canRenderChartComponents');

it('can render chart ByMonthByPlace', function () {

    livewire(Couple\Spending\Index::class)
        ->assertSeeLivewire(Couple\Spending\Charts\ByMonthByPlace::class);

})->group('canRenderChartComponents');

it('can render chart TotalMonth', function () {

    livewire(Couple\Spending\Index::class)
        ->assertSeeLivewire(Couple\Spending\Charts\TotalMonth::class);

})->group('canRenderChartComponents');

/* ###################################################################### */
/* CAN RENDER TABLE HEADER */
/* ###################################################################### */
it('can render table heading', function () {

    livewire(Couple\Spending\Index::class)
        ->assertSeeHtml('Últimos Gastos');

})->group('canRenderTableHeader');

/* ###################################################################### */
/* CAN RENDER TABLE COLUMNS */
/* ###################################################################### */
it('can render place column in table', function () {

    livewire(Couple\Spending\Index::class)
        ->assertCanRenderTableColumn('place.name');

})->group('canRenderTableColumns');

it('can render category column in table', function () {

    livewire(Couple\Spending\Index::class)
        ->assertCanRenderTableColumn('category.name');

})->group('canRenderTableColumns');

it('can render amount column in table', function () {

    livewire(Couple\Spending\Index::class)
        ->assertCanRenderTableColumn('amount');

})->group('canRenderTableColumns');

it('can render date column in table', function () {

    livewire(Couple\Spending\Index::class)
        ->assertCanRenderTableColumn('date');

})->group('canRenderTableColumns');

/* ###################################################################### */
/* RENDER DEFAULT */
/* ###################################################################### */
it('can display all my spending in table', function () {

    // Arrange
    $mySpending = CoupleSpending::factory()->count(4)->create([
        'user_id'                     => $this->user->id,
        'couple_spending_category_id' => $this->category->id,
    ]);

    $otherSpending = CoupleSpending::factory()->count(4)->create();

    // Act
    livewire(Couple\Spending\Index::class)
        ->assertCanSeeTableRecords($mySpending)
        ->assertCountTableRecords(CoupleSpending::where('user_id', $this->user->id)->count())
        ->assertCanNotSeeTableRecords($otherSpending);

})->group('renderDefault');

it('spending are sorted by default for date in desc order', function () {

    // Arrange
    $spending = CoupleSpending::factory()->count(5)->create([
        'user_id' => $this->user->id,
    ]);

    livewire(Couple\Spending\Index::class)
        ->assertCanSeeTableRecords($spending->sortByDesc('date'), inOrder: true);

})->group('renderDefault');

/* ###################################################################### */
/* SORT COLUMN TABLE */
/* ###################################################################### */
it('can sort spending by place name', function () {

    // Arrange
    $places = CoupleSpendingPlace::factory()->count(5)->create([
        'user_id' => $this->user->id,
    ]);

    $spending = CoupleSpending::factory()->count(5)->create([
        'user_id'                     => $this->user->id,
        'couple_spending_place_id'    => $places->random()->id,
        'couple_spending_category_id' => $this->category->id,
    ]);

    // Act
    livewire(Couple\Spending\Index::class)
        ->sortTable('place.name')
        ->assertCanSeeTableRecords($spending->sortBy('place.name'), inOrder: true)
        ->sortTable('place.name', 'desc')
        ->assertCanSeeTableRecords($spending->sortByDesc('place.name'), inOrder: true);

})->group('canSortTable');

it('can sort spending by category name', function () {

    // Arrange
    $categories = CoupleSpendingCategory::factory()->count(5)->create([
        'user_id' => $this->user->id,
    ]);

    $spending = CoupleSpending::factory()->count(5)->create([
        'user_id'                     => $this->user->id,
        'couple_spending_place_id'    => $this->place->id,
        'couple_spending_category_id' => $categories->random()->id,
    ]);

    // Act
    livewire(Couple\Spending\Index::class)
        ->sortTable('category.name')
        ->assertCanSeeTableRecords($spending->sortBy('category.name'), inOrder: true)
        ->sortTable('category.name', 'desc')
        ->assertCanSeeTableRecords($spending->sortByDesc('category.name'), inOrder: true);

})->group('canSortTable');

it('can sort spending by description', function () {

    // Arrange
    $spending = CoupleSpending::factory()->count(5)->create([
        'user_id'                     => $this->user->id,
        'couple_spending_place_id'    => $this->place->id,
        'couple_spending_category_id' => $this->category->id,
    ]);

    livewire(Couple\Spending\Index::class)
        ->sortTable('description')
        ->assertCanSeeTableRecords($spending->sortBy('description'), inOrder: true)
        ->sortTable('description', 'desc')
        ->assertCanSeeTableRecords($spending->sortByDesc('description'), inOrder: true);

})->group('canSortTable');

it('can sort spending by amount', function () {

    // Arrange
    $spending = CoupleSpending::factory()->count(5)->create([
        'user_id'                     => $this->user->id,
        'couple_spending_place_id'    => $this->place->id,
        'couple_spending_category_id' => $this->category->id,
    ]);

    livewire(Couple\Spending\Index::class)
        ->sortTable('amount')
        ->assertCanSeeTableRecords($spending->sortBy('amount', SORT_NUMERIC), inOrder: true)
        ->sortTable('amount', 'desc')
        ->assertCanSeeTableRecords($spending->sortByDesc('amount', SORT_NUMERIC), inOrder: true);

})->group('canSortTable');

it('can sort spending by date', function () {

    // Arrange
    $spending = CoupleSpending::factory()->count(5)->create([
        'user_id'                     => $this->user->id,
        'couple_spending_place_id'    => $this->place->id,
        'couple_spending_category_id' => $this->category->id,
    ]);

    livewire(Couple\Spending\Index::class)
        ->sortTable('date')
        ->assertCanSeeTableRecords($spending->sortBy('date'), inOrder: true)
        ->sortTable('date', 'desc')
        ->assertCanSeeTableRecords($spending->sortByDesc('date'), inOrder: true);

})->group('canSortTable');

/* ###################################################################### */
/* TABLE ACTIONS CRUD */
/* ###################################################################### */
it('can render all table row actions', function () {

    livewire(Couple\Spending\Index::class)
        ->assertTableActionExists(Tables\Actions\EditAction::class);

    livewire(Couple\Spending\Index::class)
        ->assertTableActionExists(Tables\Actions\DeleteAction::class);

})->group('tableActionsCrud');

it('can display correctly spending information in edit action', function () {

    // Arrange
    $spending = CoupleSpending::factory()->createOne([
        'user_id' => $this->user->id,
    ]);

    // Act
    livewire(Couple\Spending\Index::class)
        ->mountTableAction(Tables\Actions\EditAction::class, $spending)
        ->assertTableActionDataSet([
            'couple_spending_category_id' => $spending->category->id,
            'description'                 => $spending->description,
            'amount'                      => $spending->amount, // 1.000,00
            'date'                        => $spending->date,
        ]);

})->group('tableActionsCrud');

it('can edit spending', function () {

    // Arrange
    $spending = CoupleSpending::factory()->createOne([
        'user_id' => $this->user->id,
    ]);

    $newData = CoupleSpending::factory()->makeOne();

    // Act
    livewire(Couple\Spending\Index::class)
        ->callTableAction(Tables\Actions\EditAction::class, $spending, data: [
            'couple_spending_category_id' => $this->category->id,
            'couple_spending_place_id'    => $this->place->id,
            'description'                 => $newData->description,
            'amount'                      => $newData->amount,
            'date'                        => $newData->date,
        ])
        ->assertNotified(
            \Filament\Notifications\Notification::make()->body('Gasto atualizado com sucesso.')->success()
        )
        ->assertHasNoTableActionErrors();

    assertDatabaseHas('couple_spendings', [
        'user_id'                     => $this->user->id,
        'couple_spending_place_id'    => $this->place->id,
        'couple_spending_category_id' => $this->category->id,
        'description'                 => $newData->description,
        'amount'                      => $newData->amount,
        'date'                        => $newData->date,
    ]);

})->group('tableActionsCrud');

it('can delete categories', function () {

    $spending = CoupleSpending::factory()->createOne([
        'user_id' => $this->user->id,
    ]);

    livewire(Couple\Spending\Index::class)
        ->callTableAction(Tables\Actions\DeleteAction::class, $spending)
        ->assertNotified(
            \Filament\Notifications\Notification::make()->body('Gasto deletado com sucesso.')->success()
        );

    assertModelMissing($spending);

})->group('tableActionsCrud');

/* ###################################################################### */
/* CANNOT HAS PERMISSION */
/* ###################################################################### */
it('cannot render page if not has permission', function () {
    $this->user->revokePermissionTo('couple_spending_read');

    livewire(Couple\Spending\Index::class)
        ->assertForbidden();

})->group('cannotHasPermission');

it('cannot render the creation livewire component if not has permission', function () {
    // Arrange
    $this->user->revokePermissionTo('couple_spending_create');

    // Act
    livewire(Couple\Spending\Index::class)
        ->assertDontSeeLivewire(Couple\Spending\Create::class);

})->group('cannotHasPermission');

it('can disable edit action button if not has permission', function () {

    // Arrange
    $spending = CoupleSpending::factory()->createOne([
        'user_id' => $this->user->id,
    ]);

    $this->user->revokePermissionTo('couple_spending_update');

    // Act
    livewire(Couple\Spending\Index::class)
        ->assertTableActionDisabled(Tables\Actions\EditAction::class, $spending);

})->group('cannotHasPermission');

it('can disable delete action button if not has permission', function () {

    // Arrange
    $spending = CoupleSpending::factory()->createOne([
        'user_id' => $this->user->id,
    ]);

    $this->user->revokePermissionTo('couple_spending_delete');

    // Act
    livewire(Couple\Spending\Index::class)
        ->assertTableActionDisabled(Tables\Actions\DeleteAction::class, $spending);

})->group('cannotHasPermission');
