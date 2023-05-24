<?php

namespace Tests\Feature\Livewire\Couple\Spending;

use App\Http\Livewire\Couple;
use App\Models\{CoupleSpending, CoupleSpendingCategory, User};

use function Pest\Laravel\{actingAs, get};
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

})->group('renderPage');

it('can redirect to login if not authenticated', function () {

    \Auth::logout();

    get(route('couple.spending.index'))
        ->assertRedirect(route('login'));

})->group('renderPage');

it('can render table heading', function () {

    CoupleSpending::factory()->count(1)->create([
        'user_id'                     => $this->user->id,
        'couple_spending_category_id' => $this->category->id,
    ]);

    livewire(Couple\Spending\Index::class)
        ->assertSeeHtml('Gastos');

})->group('canRenderTableHeader');

it('can render create action button', function () {

    CoupleSpending::factory()->count(1)->create([
        'user_id'                     => $this->user->id,
        'couple_spending_category_id' => $this->category->id,
    ]);

    livewire(Couple\Spending\Index::class)
        ->assertTableActionExists('create');

})->group('canRenderTableHeader');

it('can render spending id column in table', function () {

    CoupleSpending::factory()->count(1)->create([
        'user_id'                     => $this->user->id,
        'couple_spending_category_id' => $this->category->id,
    ]);

    livewire(Couple\Spending\Index::class)
        ->assertCanRenderTableColumn('id');

})->group('canRenderTableColumn');

it('can render spending category name column in table', function () {

    CoupleSpending::factory()->count(1)->create([
        'user_id'                     => $this->user->id,
        'couple_spending_category_id' => $this->category->id,
    ]);

    livewire(Couple\Spending\Index::class)
        ->assertCanRenderTableColumn('category.name');

})->group('canRenderTableColumn');

it('can render spending description column in table', function () {

    CoupleSpending::factory()->count(1)->create([
        'user_id'                     => $this->user->id,
        'couple_spending_category_id' => $this->category->id,
    ]);

    livewire(Couple\Spending\Index::class)
        ->assertCanRenderTableColumn('description');

})->group('canRenderTableColumn');

it('can render spending amount column in table', function () {

    CoupleSpending::factory()->count(1)->create([
        'user_id'                     => $this->user->id,
        'couple_spending_category_id' => $this->category->id,
    ]);

    livewire(Couple\Spending\Index::class)
        ->assertCanRenderTableColumn('amount');

})->group('canRenderTableColumn');

it('can render spending amount date in table', function () {

    CoupleSpending::factory()->count(1)->create([
        'user_id'                     => $this->user->id,
        'couple_spending_category_id' => $this->category->id,
    ]);

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
})->group('default');

it('spending are sorted by default in desc order', function () {

    // Arrange
    $spending = CoupleSpending::factory()->count(10)->create([
        'user_id'                     => $this->user->id,
        'couple_spending_category_id' => $this->category->id,
    ]);

    livewire(Couple\Spending\Index::class)
        ->assertCanSeeTableRecords($spending->sortByDesc('id'), inOrder: true);

})->group('default');

it('can sort spending by id', function () {

    // Arrange
    $spending = CoupleSpending::factory()->count(10)->create([
        'user_id'                     => $this->user->id,
        'couple_spending_category_id' => $this->category->id,
    ]);

    livewire(Couple\Spending\Index::class)
        ->sortTable('id')
        ->assertCanSeeTableRecords($spending->sortBy('id'), inOrder: true)
        ->sortTable('id', 'desc')
        ->assertCanSeeTableRecords($spending->sortByDesc('id'), inOrder: true);

})->group('canSortTable');

it('can sort spending by category name', function () {

    // Arrange
    $categories = CoupleSpendingCategory::factory()->count(5)->create([
        'user_id' => $this->user->id,
    ]);

    $spending = CoupleSpending::factory()->count(10)->create([
        'user_id'                     => $this->user->id,
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
    $spending = CoupleSpending::factory()->count(10)->create([
        'user_id'                     => $this->user->id,
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
    $spending = CoupleSpending::factory()->count(10)->create([
        'user_id'                     => $this->user->id,
        'couple_spending_category_id' => $this->category->id,
    ]);

    livewire(Couple\Spending\Index::class)
        ->sortTable('amount')
        ->assertCanSeeTableRecords($spending->sortBy('amount'), inOrder: true)
        ->sortTable('amount', 'desc')
        ->assertCanSeeTableRecords($spending->sortByDesc('amount'), inOrder: true);

})->group('canSortTable');

it('can sort spending by date', function () {

    // Arrange
    $spending = CoupleSpending::factory()->count(10)->create([
        'user_id'                     => $this->user->id,
        'couple_spending_category_id' => $this->category->id,
    ]);

    livewire(Couple\Spending\Index::class)
        ->sortTable('date')
        ->assertCanSeeTableRecords($spending->sortBy('date'), inOrder: true)
        ->sortTable('date', 'desc')
        ->assertCanSeeTableRecords($spending->sortByDesc('date'), inOrder: true);

})->group('canSortTable');

it('can search spending by id', function () {

    // Arrange
    $spending = CoupleSpending::factory()->count(5)->create([
        'user_id'                     => $this->user->id,
        'couple_spending_category_id' => $this->category->id,
        'amount'                      => 3333,
        'date'                        => '2023-03-03',
    ]);

    $search = $spending->first()->id;

    $canSeeSpending = $spending->filter(function ($item) use ($search) {
        return false !== stripos($item->id, $search);
    });

    $cannotSeeSpending = $spending->filter(function ($item) use ($search) {
        return false === stripos($item->id, $search);
    });

    livewire(Couple\Spending\Index::class)
        ->searchTable($search)
        ->assertCanSeeTableRecords($canSeeSpending)
        ->assertCanNotSeeTableRecords($cannotSeeSpending);

})->group('canSearchTable');

it('can search spending by category name', function () {

    // Arrange
    $categories = CoupleSpendingCategory::factory()->count(5)->create([
        'user_id' => $this->user->id,
    ]);

    $spending = CoupleSpending::factory()->count(5)->create([
        'user_id'                     => $this->user->id,
        'couple_spending_category_id' => $categories->random()->id,
        'description'                 => 'description',
    ]);

    $search = $spending->first()->category()->first()->name;

    $canSeeSpending = $spending->filter(function ($item) use ($search) {
        return false !== stripos($item->category->name, $search);
    });

    $cannotSeeSpending = $spending->filter(function ($item) use ($search) {
        return false === stripos($item->category->name, $search);
    });

    livewire(Couple\Spending\Index::class)
        ->searchTable($search)
        ->assertCanSeeTableRecords($canSeeSpending)
        ->assertCanNotSeeTableRecords($cannotSeeSpending);

})->group('canSearchTable');

it('cannot render page if not has permission', function () {
    $this->user->revokePermissionTo('couple_spending_read');

    livewire(Couple\Spending\Index::class)
        ->assertForbidden();

})->group('cannotHasPermission');
