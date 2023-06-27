<?php
//
//use App\Http\Livewire\{Couple, Filament};
//use App\Models\{CoupleSpending, CoupleSpendingCategory, User};
//use Carbon\Carbon;
//use Filament\{Tables};
//
//use function Pest\Laravel\{actingAs, assertDatabaseHas, assertModelMissing, get};
//use function Pest\Livewire\livewire;
//
//beforeEach(function () {
//
//    $this->user = User::factory()->create([
//        'email' => 'teste@email.com',
//    ]);
//
//    $this->user->coupleSpendingCategories()->save(
//        $this->category = CoupleSpendingCategory::factory()->makeOne()
//    );
//
//    $this->user->givePermissionTo('couple_spending_read');
//
//    $this->user->givePermissionTo('couple_spending_create');
//
//    $this->user->givePermissionTo('couple_spending_update');
//
//    $this->user->givePermissionTo('couple_spending_delete');
//
//    actingAs($this->user);
//
//});
//
///* ###################################################################### */
///* RENDER PAGE */
///* ###################################################################### */
//it('can render page', function () {
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->assertSuccessful();
//
//})->group('renderPage');
//
//it('can redirect to login if not authenticated', function () {
//
//    \Auth::logout();
//
//    get(route('couple.spending.index'))
//        ->assertRedirect(route('login'));
//
//})->group('renderPage');
//
///* ###################################################################### */
///* CAN RENDER TABLE HEADER */
///* ###################################################################### */
//it('can render table heading', function () {
//
//    CoupleSpending::factory()->count(1)->create([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $this->category->id,
//    ]);
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->assertSeeHtml('Gastos');
//
//})->group('canRenderTableHeader');
//
//it('can render create action button', function () {
//
//    CoupleSpending::factory()->count(1)->create([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $this->category->id,
//    ]);
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->assertTableActionExists('create');
//
//})->group('canRenderTableHeader');
//
///* ###################################################################### */
///* CAN RENDER TABLE COLUMNS */
///* ###################################################################### */
//it('can render spending id column in table', function () {
//
//    CoupleSpending::factory()->count(1)->create([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $this->category->id,
//    ]);
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->assertCanRenderTableColumn('id');
//
//})->group('canRenderTableColumns');
//
//it('can render spending category name column in table', function () {
//
//    CoupleSpending::factory()->count(1)->create([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $this->category->id,
//    ]);
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->assertCanRenderTableColumn('category.name');
//
//})->group('canRenderTableColumns');
//
//it('can render spending description column in table', function () {
//
//    CoupleSpending::factory()->count(1)->create([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $this->category->id,
//    ]);
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->assertCanRenderTableColumn('description');
//
//})->group('canRenderTableColumns');
//
//it('can render spending amount column in table', function () {
//
//    CoupleSpending::factory()->count(1)->create([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $this->category->id,
//    ]);
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->assertCanRenderTableColumn('amount');
//
//})->group('canRenderTableColumns');
//
//it('can render spending date in table', function () {
//
//    CoupleSpending::factory()->count(1)->create([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $this->category->id,
//    ]);
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->assertCanRenderTableColumn('date');
//
//})->group('canRenderTableColumns');
//
///* ###################################################################### */
///* RENDER DEFAULT */
///* ###################################################################### */
//it('can display all my spending in table', function () {
//
//    // Arrange
//    $mySpending = CoupleSpending::factory()->count(10)->create([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $this->category->id,
//    ]);
//
//    $otherSpending = CoupleSpending::factory()->count(10)->create();
//
//    // Act
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->assertCanSeeTableRecords($mySpending)
//        ->assertCountTableRecords(CoupleSpending::where('user_id', $this->user->id)->count())
//        ->assertCanNotSeeTableRecords($otherSpending);
//
//})->group('renderDefault');
//
//it('spending are sorted by default in desc order', function () {
//
//    // Arrange
//    $spending = CoupleSpending::factory()->count(10)->create([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $this->category->id,
//    ]);
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->assertCanSeeTableRecords($spending->sortByDesc('id'), inOrder: true);
//
//})->group('renderDefault');
//
///* ###################################################################### */
///* SORT COLUMN TABLE */
///* ###################################################################### */
//it('can sort spending by id', function () {
//
//    // Arrange
//    $spending = CoupleSpending::factory()->count(10)->create([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $this->category->id,
//    ]);
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->sortTable('id')
//        ->assertCanSeeTableRecords($spending->sortBy('id'), inOrder: true)
//        ->sortTable('id', 'desc')
//        ->assertCanSeeTableRecords($spending->sortByDesc('id'), inOrder: true);
//
//})->group('canSortTable');
//
//it('can sort spending by category name', function () {
//
//    // Arrange
//    $categories = CoupleSpendingCategory::factory()->count(5)->create([
//        'user_id' => $this->user->id,
//    ]);
//
//    $spending = CoupleSpending::factory()->count(10)->create([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $categories->random()->id,
//    ]);
//
//    // Act
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->sortTable('category.name')
//        ->assertCanSeeTableRecords($spending->sortBy('category.name'), inOrder: true)
//        ->sortTable('category.name', 'desc')
//        ->assertCanSeeTableRecords($spending->sortByDesc('category.name'), inOrder: true);
//
//})->group('canSortTable');
//
//it('can sort spending by description', function () {
//
//    // Arrange
//    $spending = CoupleSpending::factory()->count(10)->create([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $this->category->id,
//    ]);
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->sortTable('description')
//        ->assertCanSeeTableRecords($spending->sortBy('description'), inOrder: true)
//        ->sortTable('description', 'desc')
//        ->assertCanSeeTableRecords($spending->sortByDesc('description'), inOrder: true);
//
//})->group('canSortTable');
//
//it('can sort spending by amount', function () {
//
//    // Arrange
//    $spending = CoupleSpending::factory()->count(10)->create([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $this->category->id,
//    ]);
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->sortTable('amount')
//        ->assertCanSeeTableRecords($spending->sortBy('amount'), inOrder: true)
//        ->sortTable('amount', 'desc')
//        ->assertCanSeeTableRecords($spending->sortByDesc('amount'), inOrder: true);
//
//})->group('canSortTable');
//
//it('can sort spending by date', function () {
//
//    // Arrange
//    $spending = CoupleSpending::factory()->count(10)->create([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $this->category->id,
//    ]);
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->sortTable('date')
//        ->assertCanSeeTableRecords($spending->sortBy('date'), inOrder: true)
//        ->sortTable('date', 'desc')
//        ->assertCanSeeTableRecords($spending->sortByDesc('date'), inOrder: true);
//
//})->group('canSortTable');
//
///* ###################################################################### */
///* HEADER SEARCH TABLE */
///* ###################################################################### */
//it('can search spending by id', function () {
//
//    // Arrange
//    $spending = CoupleSpending::factory()->count(5)->create([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $this->category->id,
//        'amount'                      => 3333,
//        'date'                        => '2023-03-03',
//    ]);
//
//    $search = $spending->first()->id;
//
//    $canSeeSpending = $spending->filter(function ($item) use ($search) {
//        return false !== stripos($item->id, $search);
//    });
//
//    $cannotSeeSpending = $spending->filter(function ($item) use ($search) {
//        return false === stripos($item->id, $search);
//    });
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->searchTable($search)
//        ->assertCanSeeTableRecords($canSeeSpending)
//        ->assertCanNotSeeTableRecords($cannotSeeSpending);
//
//})->group('canHeaderSearchTable');
//
//it('can search spending by category name', function () {
//
//    // Arrange
//    $categories = CoupleSpendingCategory::factory()->count(5)->create([
//        'user_id' => $this->user->id,
//    ]);
//
//    $spending = CoupleSpending::factory()->count(5)->create([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $categories->random()->id,
//        'description'                 => 'description',
//    ]);
//
//    $search = $spending->first()->category()->first()->name;
//
//    $canSeeSpending = $spending->filter(function ($item) use ($search) {
//        return false !== stripos($item->category->name, $search);
//    });
//
//    $cannotSeeSpending = $spending->filter(function ($item) use ($search) {
//        return false === stripos($item->category->name, $search);
//    });
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->searchTable($search)
//        ->assertCanSeeTableRecords($canSeeSpending)
//        ->assertCanNotSeeTableRecords($cannotSeeSpending);
//
//})->group('canHeaderSearchTable');
//
//it('can search spending by description', function () {
//
//    // Arrange
//    $category = CoupleSpendingCategory::factory()->createOne([
//        'user_id' => $this->user->id,
//        'name'    => 'couple_spending_category_id',
//    ]);
//
//    $spending = CoupleSpending::factory()->count(5)->create([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $category->id,
//    ]);
//
//    $search = $spending->first()->description;
//
//    $canSeeSpending = $spending->filter(function ($item) use ($search) {
//        return false !== stripos($item->description, $search);
//    });
//
//    $cannotSeeSpending = $spending->filter(function ($item) use ($search) {
//        return false === stripos($item->description, $search);
//    });
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->searchTable($search)
//        ->assertCanSeeTableRecords($canSeeSpending)
//        ->assertCanNotSeeTableRecords($cannotSeeSpending);
//
//})->group('canHeaderSearchTable');
//
//it('can search spending by amount', function () {
//
//    // Arrange
//    $spending = CoupleSpending::factory()->count(5)->create([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $this->category->id,
//    ]);
//
//    $search = $spending->first()->amount;
//
//    $canSeeSpending = $spending->filter(function ($item) use ($search) {
//        return false !== stripos($item->amount, $search);
//    });
//
//    $cannotSeeSpending = $spending->filter(function ($item) use ($search) {
//        return false === stripos($item->amount, $search);
//    });
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->searchTable($search)
//        ->assertCanSeeTableRecords($canSeeSpending)
//        ->assertCanNotSeeTableRecords($cannotSeeSpending);
//
//})->group('canHeaderSearchTable');
//
//it('can search spending by date in format d/m/Y', function () {
//
//    // Arrange
//    $spending = CoupleSpending::factory()->count(5)->create([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $this->category->id,
//    ]);
//
//    $search = Carbon::parse($spending->first()->date)->format('d/m/Y');
//
//    $canSeeSpending = $spending->filter(function ($item) use ($search) {
//        return false !== stripos(Carbon::parse($item->date)->format('d/m/Y'), $search);
//    });
//
//    $cannotSeeSpending = $spending->filter(function ($item) use ($search) {
//        return false === stripos(Carbon::parse($item->date)->format('d/m/Y'), $search);
//    });
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->searchTable($search)
//        ->assertCanSeeTableRecords($canSeeSpending)
//        ->assertCanNotSeeTableRecords($cannotSeeSpending);
//
//})->group('canHeaderSearchTable');
//
///* ###################################################################### */
///* RENDER FORM */
///* ###################################################################### */
//it('has a form', function () {
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->assertFormExists();
//
//})->group('renderForm');
//
///* ###################################################################### */
///* RENDER FORM FIELDS */
///* ###################################################################### */
//it('has a category field', function () {
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->assertFormFieldExists('couple_spending_category_id');
//
//})->group('renderFormFields');
//
//it('has a description field', function () {
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->assertFormFieldExists('description');
//
//})->group('renderFormFields');
//
//it('has a amount field', function () {
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->assertFormFieldExists('amount');
//
//})->group('renderFormFields');
//
//it('has a date field', function () {
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->assertFormFieldExists('date');
//
//})->group('renderFormFields');
//
///* ###################################################################### */
///* VALIDATE FORM FIELDS - CREATING */
///* ###################################################################### */
//it('can validate category in creating', function () {
//    // Arrange
//    CoupleSpendingCategory::factory()->count(5)->create([
//        'user_id' => $this->user->id,
//    ]);
//
//    // Required
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->callTableAction(Tables\Actions\CreateAction::class, data: [
//            'couple_spending_category_id' => null,
//        ])
//        ->assertHasTableActionErrors(['couple_spending_category_id' => ['required']]);
//
//    // Exists
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->callTableAction(Tables\Actions\CreateAction::class, data: [
//            'couple_spending_category_id' => CoupleSpendingCategory::count() + 1,
//        ])
//        ->assertHasTableActionErrors(['couple_spending_category_id' => ['exists']]);
//
//    // Belongs to user
//    $categoryNotOwner = CoupleSpendingCategory::factory()->createOne();
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->callTableAction(Tables\Actions\CreateAction::class, data: [
//            'couple_spending_category_id' => $categoryNotOwner->id,
//        ])
//        ->assertForbidden();
//
//})->group('creatingDataValidation');
//
//it('can validate description in creating', function () {
//
//    // Required
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->callTableAction(Tables\Actions\CreateAction::class, data: [
//            'description' => null,
//        ])
//        ->assertHasTableActionErrors(['description' => ['required']]);
//
//    // String
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->callTableAction(Tables\Actions\CreateAction::class, data: [
//            'description' => 123,
//        ])
//        ->assertHasTableActionErrors(['description' => ['string']]);
//
//    // Min 3
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->callTableAction(Tables\Actions\CreateAction::class, data: [
//            'description' => \Str::random(2),
//        ])
//        ->assertHasTableActionErrors(['description' => ['min']]);
//
//    // Max 255
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->callTableAction(Tables\Actions\CreateAction::class, data: [
//            'description' => \Str::random(256),
//        ])
//        ->assertHasTableActionErrors(['description' => ['max']]);
//
//})->group('creatingDataValidation');
//
//it('can validate amount in creating', function () {
//
//    // Required
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->callTableAction(Tables\Actions\CreateAction::class, data: [
//            'amount' => null,
//        ])
//        ->assertHasTableActionErrors(['amount' => ['required']]);
//
//})->group('creatingDataValidation');
//
//it('can validate date in creating', function () {
//
//    // Required
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->callTableAction(Tables\Actions\CreateAction::class, data: [
//            'date' => null,
//        ])
//        ->assertHasTableActionErrors(['date' => ['required']]);
//
//    // Date
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->callTableAction(Tables\Actions\CreateAction::class, data: [
//            'date' => 'abc',
//        ])
//        ->assertHasTableActionErrors(['date' => ['date']]);
//
//})->group('creatingDataValidation');
//
///* ###################################################################### */
///* VALIDATE FORM FIELDS - UPDATING */
///* ###################################################################### */
//it('can validate category in updating', function () {
//    // Arrange
//    $spending = CoupleSpending::factory()->createOne([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $this->category->id,
//    ]);
//
//    CoupleSpendingCategory::factory()->count(5)->create([
//        'user_id' => $this->user->id,
//    ]);
//
//    // Required
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->callTableAction(Tables\Actions\EditAction::class, $spending, data: [
//            'couple_spending_category_id' => null,
//        ])
//        ->assertHasTableActionErrors(['couple_spending_category_id' => ['required']]);
//
//    // Exists
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->callTableAction(Tables\Actions\EditAction::class, $spending, data: [
//            'couple_spending_category_id' => CoupleSpendingCategory::count() + 1,
//        ])
//        ->assertHasTableActionErrors(['couple_spending_category_id' => ['exists']]);
//
//    // Belongs to user
//    $categoryNotOwner = CoupleSpendingCategory::factory()->createOne();
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->callTableAction(Tables\Actions\EditAction::class, $spending, data: [
//            'couple_spending_category_id' => $categoryNotOwner->id,
//        ])
//        ->assertForbidden();
//
//})->group('updatingDataValidation');
//
//it('can validate description in updating', function () {
//    // Arrange
//    $spending = CoupleSpending::factory()->createOne([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $this->category->id,
//    ]);
//
//    // Required
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->callTableAction(Tables\Actions\EditAction::class, $spending, data: [
//            'description' => null,
//        ])
//        ->assertHasTableActionErrors(['description' => ['required']]);
//
//    // String
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->callTableAction(Tables\Actions\EditAction::class, $spending, data: [
//            'description' => 123,
//        ])
//        ->assertHasTableActionErrors(['description' => ['string']]);
//
//    // Min 3
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->callTableAction(Tables\Actions\EditAction::class, $spending, data: [
//            'description' => \Str::random(2),
//        ])
//        ->assertHasTableActionErrors(['description' => ['min']]);
//
//    // Max 255
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->callTableAction(Tables\Actions\EditAction::class, $spending, data: [
//            'description' => \Str::random(256),
//        ])
//        ->assertHasTableActionErrors(['description' => ['max']]);
//
//})->group('updatingDataValidation');
//
//it('can validate amount in updating', function () {
//    // Arrange
//    $spending = CoupleSpending::factory()->createOne([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $this->category->id,
//    ]);
//
//    // Required
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->callTableAction(Tables\Actions\EditAction::class, $spending, data: [
//            'amount' => null,
//        ])
//        ->assertHasTableActionErrors(['amount' => ['required']]);
//
//})->group('updatingDataValidation');
//
//it('can validate date in updating', function () {
//    // Arrange
//    $spending = CoupleSpending::factory()->createOne([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $this->category->id,
//    ]);
//
//    // Required
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->callTableAction(Tables\Actions\EditAction::class, $spending, data: [
//            'date' => null,
//        ])
//        ->assertHasTableActionErrors(['date' => ['required']]);
//
//    // Date
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->callTableAction(Tables\Actions\EditAction::class, $spending, data: [
//            'date' => 'abc',
//        ])
//        ->assertHasTableActionErrors(['date' => ['date']]);
//
//})->group('updatingDataValidation');
//
///* ###################################################################### */
///* TABLE ACTIONS CRUD */
///* ###################################################################### */
//it('can render all table row actions', function () {
//
//    CoupleSpending::factory()->count(1)->create([
//        'user_id' => $this->user->id,
//    ]);
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->assertTableActionExists(Tables\Actions\EditAction::class);
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->assertTableActionExists(Tables\Actions\DeleteAction::class);
//
//})->group('tableActionsCrud');
//
//it('can display correctly spending information in edit action', function () {
//
//    // Arrange
//    $spending = CoupleSpending::factory()->createOne([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $this->category->id,
//    ]);
//
//    // Act
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->mountTableAction(Tables\Actions\EditAction::class, $spending)
//        ->assertTableActionDataSet([
//            'couple_spending_category_id' => $spending->category->id,
//            'description'                 => $spending->description,
//            'amount'                      => number_format($spending->amount, 2, ',', '.'), // 1.000,00
//            //            'date' => $spending->date . ' ' . now()->format('H:i:s'), Bug in filament
//        ]);
//
//})->group('tableActionsCrud');
//
//it('can create spending', function () {
//    // Arrange
//    $spending = CoupleSpending::factory()->makeOne([
//        'user_id' => $this->user->id,
//    ]);
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->callTableAction(Tables\Actions\CreateAction::class, data: [
//            'couple_spending_category_id' => $this->category->id,
//            'description'                 => $spending->description,
//            'amount'                      => $spending->amount,
//            'date'                        => $spending->date,
//        ])
//        ->assertHasNoTableActionErrors();
//
//    assertDatabaseHas('couple_spendings', [
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $this->category->id,
//        'description'                 => $spending->description,
//        'amount'                      => $spending->amount,
//        'date'                        => $spending->date,
//    ]);
//
//})->group('tableActionsCrud');
//
//it('can edit spending', function () {
//    // Arrange
//    $spending = CoupleSpending::factory()->createOne([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $this->category->id,
//    ]);
//
//    $newCategory = CoupleSpendingCategory::factory()->createOne([
//        'user_id' => $this->user->id,
//    ]);
//
//    $newData = CoupleSpending::factory()->makeOne([
//        'user_id' => $this->user->id,
//    ]);
//
//    // Act
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->callTableAction(Tables\Actions\EditAction::class, $spending, data: [
//            'couple_spending_category_id' => $newCategory->id,
//            'description'                 => $newData->description,
//            'amount'                      => $newData->amount,
//            'date'                        => $newData->date,
//        ])
//        ->assertHasNoTableActionErrors();
//
//    assertDatabaseHas('couple_spendings', [
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $newCategory->id,
//        'description'                 => $newData->description,
//        'amount'                      => $newData->amount,
//        'date'                        => $newData->date,
//    ]);
//
//})->group('tableActionsCrud');
//
//it('can delete categories', function () {
//
//    $spending = CoupleSpending::factory()->createOne([
//        'user_id'                     => $this->user->id,
//        'couple_spending_category_id' => $this->category->id,
//    ]);
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->callTableAction(Tables\Actions\DeleteAction::class, $spending);
//
//    assertModelMissing($spending);
//
//})->group('tableActionsCrud');
//
///* ###################################################################### */
///* CANNOT HAS PERMISSION */
///* ###################################################################### */
//it('cannot render page if not has permission', function () {
//    $this->user->revokePermissionTo('couple_spending_read');
//
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->assertForbidden();
//
//})->group('cannotHasPermission');
//
//it('cannot render create action button if not has permission', function () {
//    // Arrange
//    $this->user->revokePermissionTo('couple_spending_create');
//
//    // Act
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->assertDontSeeHtml('Criar gasto');
//
//})->group('cannotHasPermission');
//
//it('can disable edit action button if not has permission', function () {
//
//    // Arrange
//    $this->user->coupleSpendings()->save(
//        $spending = CoupleSpending::factory()->makeOne([
//            'couple_spending_category_id' => $this->category->id,
//        ])
//    );
//
//    $this->user->revokePermissionTo('couple_spending_update');
//
//    // Act
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->assertTableActionDisabled(Tables\Actions\EditAction::class, $spending);
//
//})->group('cannotHasPermission');
//
//it('can disable delete action button if not has permission', function () {
//
//    // Arrange
//    $this->user->coupleSpendings()->save(
//        $spending = CoupleSpending::factory()->makeOne([
//            'couple_spending_category_id' => $this->category->id,
//        ])
//    );
//
//    $this->user->revokePermissionTo('couple_spending_delete');
//
//    // Act
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->assertTableActionDisabled(Tables\Actions\DeleteAction::class, $spending);
//
//})->group('cannotHasPermission');
//
///* ###################################################################### */
///* CANNOT OWNER */
///* ###################################################################### */
//it('can disable edit action button if not is owner', function () {
//
//    // Arrange
//    $spending = CoupleSpending::factory()->create();
//
//    // Act
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->assertTableActionDisabled(Tables\Actions\EditAction::class, $spending);
//
//})->group('cannotOwner');
//
//it('can disable delete action button if not is owner', function () {
//
//    // Arrange
//    $spending = CoupleSpending::factory()->create();
//
//    // Act
//    livewire(Filament\CoupleSpendingResource\Index::class)
//        ->assertTableActionDisabled(Tables\Actions\DeleteAction::class, $spending);
//
//})->group('cannotOwner');
