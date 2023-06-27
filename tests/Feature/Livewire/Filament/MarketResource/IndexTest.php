<?php
//
//namespace Livewire\Couple\Spending\Categories;
//
//use App\Http\Livewire\Filament;
//use App\Models\{Market, User};
//use Filament\Tables;
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
//    $this->user->givePermissionTo('market_read');
//
//    $this->user->givePermissionTo('market_create');
//
//    $this->user->givePermissionTo('market_update');
//
//    $this->user->givePermissionTo('market_delete');
//
//    actingAs($this->user);
//
//});
//
//it('can render page', function () {
//
//    livewire(Filament\MarketResource\Index::class)
//        ->assertSuccessful();
//
//})->group('canRenderPage');
//
//it('can redirect to login if not authenticated', function () {
//
//    \Auth::logout();
//
//    get(route('markets.index'))
//        ->assertRedirect(route('login'));
//
//})->group('canRenderPage');
//
//it('can display all my markets in the table', function () {
//
//    // Arrange
//    $myMarkets = Market::factory()->count(10)->create([
//        'user_id' => $this->user->id,
//    ]);
//
//    $otherMarkets = Market::factory()->count(10)->create();
//
//    livewire(Filament\MarketResource\Index::class)
//        ->assertCanSeeTableRecords($myMarkets)
//        ->assertCountTableRecords(Market::where('user_id', $this->user->id)->count())
//        ->assertCanNotSeeTableRecords($otherMarkets);
//
//});
//
//it('can render table heading', function () {
//
//    Market::factory()->count(1)->create();
//
//    livewire(Filament\MarketResource\Index::class)
//        ->assertSeeHtml('Mercados');
//
//})->group('canRenderTableHeader');
//
//it('can render create action button', function () {
//
//    Market::factory()->count(1)->create();
//
//    livewire(Filament\MarketResource\Index::class)
//        ->assertTableActionExists('create');
//
//})->group('canRenderTableHeader');
//
//it('can render category id table column', function () {
//
//    Market::factory()->count(1)->create();
//
//    livewire(Filament\MarketResource\Index::class)
//        ->assertCanRenderTableColumn('id');
//
//})->group('canRenderTableColumn');
//
//it('can render name table column', function () {
//
//    Market::factory()->count(1)->create();
//
//    livewire(Filament\MarketResource\Index::class)
//        ->assertCanRenderTableColumn('name');
//
//})->group('canRenderTableColumn');
//
//it('markets are sorted by default in desc order', function () {
//
//    $markets = Market::factory()->count(10)->create([
//        'user_id' => $this->user->id,
//    ]);
//
//    livewire(Filament\MarketResource\Index::class)
//        ->assertCanSeeTableRecords($markets->sortByDesc('id'), inOrder: true);
//
//});
//
//it('can sort markets by id', function () {
//
//    $markets = Market::factory()->count(10)->create([
//        'user_id' => $this->user->id,
//    ]);
//
//    livewire(Filament\MarketResource\Index::class)
//        ->sortTable('id')
//        ->assertCanSeeTableRecords($markets->sortBy('id'), inOrder: true)
//        ->sortTable('id', 'desc')
//        ->assertCanSeeTableRecords($markets->sortByDesc('id'), inOrder: true);
//
//})->group('canSortTable');
//
//it('can sort markets by name', function () {
//
//    $markets = Market::factory()->count(5)->create([
//        'user_id' => $this->user->id,
//    ]);
//
//    livewire(Filament\MarketResource\Index::class)
//        ->sortTable('name')
//        ->assertCanSeeTableRecords($markets->sortBy('name'), inOrder: true)
//        ->sortTable('name', 'desc')
//        ->assertCanSeeTableRecords($markets->sortByDesc('name'), inOrder: true);
//
//})->group('canSortTable');
//
//it('can search markets by id', function () {
//    $markets = Market::factory()->count(10)->create([
//        'user_id' => $this->user->id,
//    ]);
//
//    $id = $markets->first()->id;
//
//    $canSeeMarkets = $markets->filter(function ($item) use ($id) {
//        return false !== stripos($item->id, $id);
//    });
//
//    $cannotSeeMarkets = $markets->filter(function ($item) use ($id) {
//        return false === stripos($item->id, $id);
//    });
//
//    livewire(Filament\MarketResource\Index::class)
//        ->searchTable($id)
//        ->assertCanSeeTableRecords($canSeeMarkets)
//        ->assertCanNotSeeTableRecords($cannotSeeMarkets);
//})->group('canSearchTable');
//
//it('can search markets by name', function () {
//
//    $markets = Market::factory()->count(10)->create([
//        'user_id' => $this->user->id,
//    ]);
//
//    $name = $markets->first()->name;
//
//    $canSeeMarkets = $markets->filter(function ($item) use ($name) {
//        return false !== stripos($item->name, $name);
//    });
//
//    $cannotSeeMarkets = $markets->filter(function ($item) use ($name) {
//        return false === stripos($item->name, $name);
//    });
//
//    livewire(Filament\MarketResource\Index::class)
//        ->searchTable($name)
//        ->assertCanSeeTableRecords($canSeeMarkets)
//        ->assertCanNotSeeTableRecords($cannotSeeMarkets);
//
//})->group('canSearchTable');
//
//it('has a form', function () {
//
//    livewire(Filament\MarketResource\Index::class)
//        ->assertFormExists();
//
//})->group('renderForm');
//
//it('has a name field', function () {
//
//    livewire(Filament\MarketResource\Index::class)
//        ->assertFormFieldExists('name');
//
//})->group('renderFormFields');
//
//it('can validate name in creating', function () {
//
//    // Required
//    livewire(Filament\MarketResource\Index::class)
//        ->callTableAction(Tables\Actions\CreateAction::class, data: [
//            'name' => null,
//        ])
//        ->assertHasTableActionErrors(['name' => ['required']]);
//
//    // String
//    livewire(Filament\MarketResource\Index::class)
//        ->callTableAction(Tables\Actions\CreateAction::class, data: [
//            'name' => 12,
//        ])
//        ->assertHasTableActionErrors(['name' => ['string']]);
//
//    // Min 3
//    livewire(Filament\MarketResource\Index::class)
//        ->callTableAction(Tables\Actions\CreateAction::class, data: [
//            'name' => 'aa',
//        ])
//        ->assertHasTableActionErrors(['name' => ['min']]);
//
//    // Max 255
//    livewire(Filament\MarketResource\Index::class)
//        ->callTableAction(Tables\Actions\CreateAction::class, data: [
//            'name' => str_repeat('a', 256),
//        ])
//        ->assertHasTableActionErrors(['name' => ['max']]);
//
//    // Rule unique is only for owner market
//    $market = Market::factory()->create([
//        'user_id' => $this->user->id,
//    ]);
//
//    livewire(Filament\MarketResource\Index::class)
//        ->callTableAction(Tables\Actions\CreateAction::class, data: [
//            'name' => $market->name,
//        ])
//        ->assertHasTableActionErrors(['name' => ['unique']]);
//
//    // Ignore rule unique only for not market owner
//    $market = Market::factory()->create([
//        'user_id' => User::factory()->create()->id,
//    ]);
//
//    livewire(Filament\MarketResource\Index::class)
//        ->callTableAction(Tables\Actions\CreateAction::class, data: [
//            'name' => $market->name,
//        ])
//        ->assertHasNoTableActionErrors(['name' => ['unique']]);
//
//})->group('creatingDataValidation');
//
//it('can validate name in updating', function () {
//    // Arrange
//    $market = Market::factory()->create([
//        'user_id' => $this->user->id,
//    ]);
//
//    // Required
//    livewire(Filament\MarketResource\Index::class)
//        ->callTableAction(Tables\Actions\EditAction::class, $market, data: [
//            'name' => null,
//        ])
//        ->assertHasTableActionErrors(['name' => ['required']]);
//
//    // String
//    livewire(Filament\MarketResource\Index::class)
//        ->callTableAction(Tables\Actions\EditAction::class, $market, data: [
//            'name' => 12,
//        ])
//        ->assertHasTableActionErrors(['name' => ['string']]);
//
//    // Min 3
//    livewire(Filament\MarketResource\Index::class)
//        ->callTableAction(Tables\Actions\EditAction::class, $market, data: [
//            'name' => 'aa',
//        ])
//        ->assertHasTableActionErrors(['name' => ['min']]);
//
//    // Max 255
//    livewire(Filament\MarketResource\Index::class)
//        ->callTableAction(Tables\Actions\EditAction::class, $market, data: [
//            'name' => str_repeat('a', 256),
//        ])
//        ->assertHasTableActionErrors(['name' => ['max']]);
//
//    // Rule unique is only for owner market
//    $market2 = Market::factory()->create([
//        'user_id' => $this->user->id,
//    ]);
//
//    livewire(Filament\MarketResource\Index::class)
//        ->callTableAction(Tables\Actions\EditAction::class, $market, data: [
//            'name' => $market2->name,
//        ])
//        ->assertHasTableActionErrors(['name' => ['unique']]);
//
//    // Ignore unique rule for current market
//    livewire(Filament\MarketResource\Index::class)
//        ->callTableAction(Tables\Actions\EditAction::class, $market, data: [
//            'name' => $market->name,
//        ])
//        ->assertHasNoTableActionErrors(['name' => ['unique']]);
//
//    // Ignore rule unique for not market owner
//    $market3 = Market::factory()->create([
//        'user_id' => User::factory()->create()->id,
//    ]);
//
//    livewire(Filament\MarketResource\Index::class)
//        ->callTableAction(Tables\Actions\CreateAction::class, data: [
//            'name' => $market3->name,
//        ])
//        ->assertHasNoTableActionErrors(['name' => ['unique']]);
//
//})->group('updatingDataValidation');
//
//it('can create markets', function () {
//
//    livewire(Filament\MarketResource\Index::class)
//        ->callTableAction(Tables\Actions\CreateAction::class, data: [
//            'name' => $name = fake()->words(asText: true),
//        ])
//        ->assertHasNoTableActionErrors();
//
//    assertDatabaseHas('markets', [
//        'user_id' => $this->user->id,
//        'name'    => $name,
//    ]);
//
//})->group('tableActions');
//
//it('can edit markets', function () {
//    // Arrange
//    $market = Market::factory()->createOne([
//        'user_id' => $this->user->id,
//    ]);
//
//    livewire(Filament\MarketResource\Index::class)
//        ->callTableAction(Tables\Actions\EditAction::class, $market, data: [
//            'name' => $name = fake()->words(asText: true),
//        ])
//        ->assertHasNoTableActionErrors();
//
//    expect($market->refresh())
//        ->name->toBe($name);
//
//})->group('tableActions');
//
//it('can delete markets', function () {
//
//    $market = Market::factory()->createOne([
//        'user_id' => $this->user->id,
//    ]);
//
//    livewire(Filament\MarketResource\Index::class)
//        ->callTableAction(Tables\Actions\DeleteAction::class, $market);
//
//    assertModelMissing($market);
//
//})->group('tableActions');
//
//it('can render all table row actions', function () {
//
//    Market::factory()->count(1)->create([
//        'user_id' => $this->user->id,
//    ]);
//
//    livewire(Filament\MarketResource\Index::class)
//        ->assertTableActionExists(Tables\Actions\EditAction::class);
//
//    livewire(Filament\MarketResource\Index::class)
//        ->assertTableActionExists(Tables\Actions\DeleteAction::class);
//
//})->group('tableActions');
//
//it('can display correctly market information in edit action', function () {
//    $market = Market::factory()->create([
//        'user_id' => $this->user->id,
//    ]);
//
//    livewire(Filament\MarketResource\Index::class)
//        ->mountTableAction(Tables\Actions\EditAction::class, $market)
//        ->assertTableActionDataSet([
//            'name' => $market->name,
//        ]);
//})->group('tableActions');
//
//it('cannot render page if not has permission', function () {
//
//    $this->user->revokePermissionTo('market_read');
//
//    livewire(Filament\MarketResource\Index::class)
//        ->assertForbidden();
//
//})->group('cannotHasPermission');
//
//it('cannot render create action button if not has permission', function () {
//    // Arrange
//    $this->user->revokePermissionTo('market_create');
//
//    Market::factory()->count(1)->create();
//
//    // Act
//    livewire(Filament\MarketResource\Index::class)
//        ->assertDontSeeHtml('Criar mercado');
//
//})->group('cannotHasPermission');
//
//it('can disable edit action button if not has permission', function () {
//    // Arrange
//    $market = Market::factory()->create([
//        'user_id' => $this->user->id,
//    ]);
//
//    $this->user->revokePermissionTo('market_update');
//
//    // Act
//    livewire(Filament\MarketResource\Index::class)
//        ->assertTableActionDisabled(Tables\Actions\EditAction::class, $market);
//
//})->group('cannotHasPermission');
//
//it('can disable delete action button if not has permission', function () {
//    // Arrange
//    $market = Market::factory()->create([
//        'user_id' => $this->user->id,
//    ]);
//
//    $this->user->revokePermissionTo('market_delete');
//
//    // Act
//    livewire(Filament\MarketResource\Index::class)
//        ->assertTableActionDisabled(Tables\Actions\DeleteAction::class, $market);
//
//})->group('cannotHasPermission');
//
//it('can disable edit action button if not is owner', function () {
//
//    // Arrange
//    $market = Market::factory()->create();
//
//    // Act
//    livewire(Filament\MarketResource\Index::class)
//        ->assertTableActionDisabled(Tables\Actions\EditAction::class, $market);
//
//})->group('cannotOwner');
//
//it('can disable delete action button if not is owner', function () {
//
//    // Arrange
//    $market = Market::factory()->create();
//
//    // Act
//    livewire(Filament\MarketResource\Index::class)
//        ->assertTableActionDisabled(Tables\Actions\DeleteAction::class, $market);
//
//})->group('cannotOwner');
