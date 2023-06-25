<?php

namespace Livewire\Banks\Accounts;

use App\Http\Livewire\Filament;
use App\Models\{CreditCard, User};
use Filament\Pages\Actions\{CreateAction, EditAction};
use Filament\Tables;

use function Pest\Laravel\{actingAs, assertDatabaseMissing, assertModelMissing, get};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo('credit_card_read');

    $this->user->givePermissionTo('credit_card_create');

    $this->user->givePermissionTo('credit_card_update');

    $this->user->givePermissionTo('credit_card_delete');

    actingAs($this->user);

});

/* ###################################################################### */
/* RENDER PAGE */
/* ###################################################################### */
it('can render page', function () {

    livewire(Filament\CreditCardResource\Index::class)
        ->assertSuccessful();

})->group('renderPage');

it('can redirect to login if not authenticated', function () {

    \Auth::logout();

    get(route('banks.credit-cards.index'))
        ->assertRedirect(route('login'));

})->group('renderPage');

/* ###################################################################### */
/* CAN RENDER TABLE HEADER */
/* ###################################################################### */
it('can render table heading', function () {

    livewire(Filament\CreditCardResource\Index::class)
        ->assertSeeHtml('Cartões de Crédito');

})->group('canRenderTableHeader');

it('can render create action button', function () {

    livewire(Filament\CreditCardResource\Index::class)
        ->assertTableActionExists('create');

})->group('canRenderTableHeader');

/* ###################################################################### */
/* CAN RENDER TABLE COLUMNS */
/* ###################################################################### */
it('can render credit card id column in table', function () {

    livewire(Filament\CreditCardResource\Index::class)
        ->assertCanRenderTableColumn('id');

})->group('canRenderTableColumns');

it('can render credit card bank column in table', function () {

    livewire(Filament\CreditCardResource\Index::class)
        ->assertCanRenderTableColumn('bank');

})->group('canRenderTableColumns');

it('can render credit card number column in table', function () {

    livewire(Filament\CreditCardResource\Index::class)
        ->assertCanRenderTableColumn('number');

})->group('canRenderTableColumns');

it('can render credit card expiration column in table', function () {

    livewire(Filament\CreditCardResource\Index::class)
        ->assertCanRenderTableColumn('expiration');

})->group('canRenderTableColumns');

it('can render credit card cvv in table', function () {

    livewire(Filament\CreditCardResource\Index::class)
        ->assertCanRenderTableColumn('cvv');

})->group('canRenderTableColumns');

it('can render credit card formatted limit in table', function () {

    livewire(Filament\CreditCardResource\Index::class)
        ->assertCanRenderTableColumn('formatted_limit');

})->group('canRenderTableColumns');

it('can render credit card formatted remaining limit in table', function () {

    livewire(Filament\CreditCardResource\Index::class)
        ->assertCanRenderTableColumn('formatted_remaining_limit');

})->group('canRenderTableColumns');

/* ###################################################################### */
/* RENDER DEFAULT */
/* ###################################################################### */
it('can display only my credit cards in table', function () {

    // Arrange
    $this->user->creditCards()->saveMany(
        $myCreditCards = CreditCard::factory()->count(10)->make()
    );

    $otherCreditCards = CreditCard::factory()->count(10)->create();

    // Act
    livewire(Filament\CreditCardResource\Index::class)
        ->assertCanSeeTableRecords($myCreditCards)
        ->assertCountTableRecords(CreditCard::whereUserId($this->user->id)->count())
        ->assertCanNotSeeTableRecords($otherCreditCards);

})->group('renderDefault');

it('credit cards are sorted by default in desc order', function () {

    // Arrange
    $this->user->creditCards()->saveMany(
        $creditCards = CreditCard::factory()->count(10)->make()
    );

    // Act
    livewire(Filament\CreditCardResource\Index::class)
        ->assertCanSeeTableRecords($creditCards->sortByDesc('id'), inOrder: true);

})->group('renderDefault');

it('credit card number display in format 0000 0000 0000 0000', function () {

    // Arrange
    $this->user->creditCards()->save(
        $creditCard = CreditCard::factory()->makeOne()
    );

    $formattedNumber = str($creditCard->number)->replaceMatches('/(\d{4})(\d{4})(\d{4})(\d{4})/', '$1 $2 $3 $4');

    // Act
    livewire(Filament\CreditCardResource\Index::class)
        ->assertTableColumnFormattedStateSet('number', $formattedNumber, record: $creditCard);

})->group('renderDefault');

it('credit card expiration display in format 00/00', function () {

    // Arrange
    $this->user->creditCards()->save(
        $creditCard = CreditCard::factory()->makeOne()
    );

    $formattedExpiration = str($creditCard->expiration)->replaceMatches('/(\d{2})(\d{2})/', '$1/$2');

    // Act
    livewire(Filament\CreditCardResource\Index::class)
        ->assertTableColumnFormattedStateSet('expiration', $formattedExpiration, record: $creditCard);

})->group('renderDefault');

it('credit card limit display in format R$ 9.999,99', function () {

    // Arrange
    $this->user->creditCards()->save(
        $creditCard = CreditCard::factory()->makeOne()
    );

    $formattedLimit = "R$ " . number_format($creditCard->limit, 2, ',', '.');

    // Act
    livewire(Filament\CreditCardResource\Index::class)
        ->assertTableColumnFormattedStateSet('formatted_limit', $formattedLimit, record: $creditCard);

})->group('renderDefault');

it('credit card remaining limit display in format R$ 9.999,99', function () {

    // Arrange
    $this->user->creditCards()->save(
        $creditCard = CreditCard::factory()->makeOne()
    );

    $formattedRemainingLimit = "R$ " . number_format($creditCard->remaining_limit, 2, ',', '.');

    // Act
    livewire(Filament\CreditCardResource\Index::class)
        ->assertTableColumnFormattedStateSet('formatted_remaining_limit', $formattedRemainingLimit, record: $creditCard);

})->group('renderDefault');

/* ###################################################################### */
/* SORT COLUMN TABLE */
/* ###################################################################### */
it('can sort spending by id', function () {

    // Arrange
    $this->user->creditCards()->saveMany(
        $creditCards = CreditCard::factory()->count(3)->make()
    );

    livewire(Filament\CreditCardResource\Index::class)
        ->sortTable('id')
        ->assertCanSeeTableRecords($creditCards->sortBy('id'), inOrder: true)
        ->sortTable('id', 'desc')
        ->assertCanSeeTableRecords($creditCards->sortByDesc('id'), inOrder: true);

})->group('canSortTable');

it('can sort spending by bank', function () {

    // Arrange
    $this->user->creditCards()->saveMany(
        $creditCards = CreditCard::factory()->count(5)->make()
    );

    livewire(Filament\CreditCardResource\Index::class)
        ->sortTable('bank')
        ->assertCanSeeTableRecords($creditCards->sortBy('bank'), inOrder: true)
        ->sortTable('bank', 'desc')
        ->assertCanSeeTableRecords($creditCards->sortByDesc('bank'), inOrder: true);

})->group('canSortTable');

it('can sort spending by number', function () {

    // Arrange
    $this->user->creditCards()->saveMany(
        CreditCard::factory()->count(5)->make()
    );

    $creditCardsAsc = CreditCard::whereUserId($this->user->id)
        ->orderBy('number')
        ->get();

    $creditCardsDesc = CreditCard::whereUserId($this->user->id)
        ->orderByDesc('number')
        ->get();

    // Act
    livewire(Filament\CreditCardResource\Index::class)
        ->sortTable('number')
        ->assertCanSeeTableRecords($creditCardsAsc, inOrder: true)
        ->sortTable('number', 'desc')
        ->assertCanSeeTableRecords($creditCardsDesc, inOrder: true);

})->group('canSortTable');

it('can sort spending by expiration', function () {

    // Arrange
    $this->user->creditCards()->saveMany(
        CreditCard::factory()->count(5)->make()
    );

    $creditCardsAsc = CreditCard::whereUserId($this->user->id)
        ->orderBy('expiration')
        ->get();

    $creditCardsDesc = CreditCard::whereUserId($this->user->id)
        ->orderByDesc('expiration')
        ->get();

    // Act
    livewire(Filament\CreditCardResource\Index::class)
        ->sortTable('expiration')
        ->assertCanSeeTableRecords($creditCardsAsc, inOrder: true)
        ->sortTable('expiration', 'desc')
        ->assertCanSeeTableRecords($creditCardsDesc, inOrder: true);

})->group('canSortTable');

it('can sort spending by cvv', function () {

    // Arrange
    $this->user->creditCards()->saveMany(
        CreditCard::factory()->count(5)->make()
    );

    $creditCardsAsc = CreditCard::whereUserId($this->user->id)
        ->orderBy('cvv')
        ->get();

    $creditCardsDesc = CreditCard::whereUserId($this->user->id)
        ->orderByDesc('cvv')
        ->get();

    // Act
    livewire(Filament\CreditCardResource\Index::class)
        ->sortTable('cvv')
        ->assertCanSeeTableRecords($creditCardsAsc, inOrder: true)
        ->sortTable('cvv', 'desc')
        ->assertCanSeeTableRecords($creditCardsDesc, inOrder: true);

})->group('canSortTable');

it('can sort spending by formatted limit', function () {

    // Arrange
    $this->user->creditCards()->saveMany(
        $creditCards = CreditCard::factory()->count(5)->make()
    );

    // Act
    livewire(Filament\CreditCardResource\Index::class)
        ->sortTable('formatted_limit')
        ->assertCanSeeTableRecords($creditCards->sortBy('limit'), inOrder: true)
        ->sortTable('formatted_limit', 'desc')
        ->assertCanSeeTableRecords($creditCards->sortByDesc('limit'), inOrder: true);

})->group('canSortTable');

it('can sort spending by formatted remaining limit', function () {

    // Arrange
    $this->user->creditCards()->saveMany(
        $creditCards = CreditCard::factory()->count(5)->make()
    );

    // Act
    livewire(Filament\CreditCardResource\Index::class)
        ->sortTable('formatted_remaining_limit')
        ->assertCanSeeTableRecords($creditCards->sortBy('remaining_limit'), inOrder: true)
        ->sortTable('formatted_remaining_limit', 'desc')
        ->assertCanSeeTableRecords($creditCards->sortByDesc('remaining_limit'), inOrder: true);

})->group('canSortTable');

/* ###################################################################### */
/* HEADER SEARCH TABLE */
/* ###################################################################### */
it('can search credit cards from header search', function () {

    // Arrange
    $this->user->creditCards()->saveMany(
        CreditCard::factory()->count(5)->make()
    );

    $fakeNumber = fake()->numberBetween(1, 5);

    $search = $fakeNumber % 2 == 0
        ? $fakeNumber
        : fake()->sentence(1);

    $canSeeRecord = CreditCard::whereUserId($this->user->id)->where('id', 'LIKE', "%{$search}%")
        ->orWhere('bank', 'LIKE', "%{$search}%")
        ->orWhere('number', 'LIKE', "%{$search}%")
        ->orWhere('expiration', 'LIKE', "%{$search}%")
        ->orWhere('cvv', 'LIKE', "%{$search}%")
        ->orWhere('limit', 'LIKE', "%{$search}%")
        ->orWhere('remaining_limit', 'LIKE', "%{$search}%")
        ->get();

    $cannotSeeRecord = CreditCard::whereUserId($this->user->id)->where('id', 'NOT LIKE', "%{$search}%")
        ->where('bank', 'NOT LIKE', "%{$search}%")
        ->where('number', 'NOT LIKE', "%{$search}%")
        ->where('expiration', 'NOT LIKE', "%{$search}%")
        ->where('cvv', 'NOT LIKE', "%{$search}%")
        ->where('limit', 'NOT LIKE', "%{$search}%")
        ->where('remaining_limit', 'NOT LIKE', "%{$search}%")
        ->get();

    // Act
    livewire(Filament\CreditCardResource\Index::class)
        ->searchTable($search)
        ->assertCanSeeTableRecords($canSeeRecord)
        ->assertCanNotSeeTableRecords($cannotSeeRecord);

})->group('canHeaderSearchTable');

/* ###################################################################### */
/* TABLE ACTIONS CRUD */
/* ###################################################################### */
it('can render all table row actions', function () {

    $this->user->creditCards()->saveMany(
        CreditCard::factory()->count(5)->make()
    );

    livewire(Filament\CreditCardResource\Index::class)
        ->assertTableActionExists(Tables\Actions\EditAction::class);

    livewire(Filament\CreditCardResource\Index::class)
        ->assertTableActionExists(Tables\Actions\DeleteAction::class);

})->group('tableActionsCrud');

it('can redirect to create page on click create button', function () {

    livewire(Filament\CreditCardResource\Index::class)
        ->assertTableActionHasUrl(CreateAction::class, route('banks.credit-cards.create'));

})->group('tableActionsCrud');

it('can redirect to edit page on click edit button', function () {

    $creditCard = CreditCard::factory()->create([
        'user_id' => $this->user->id,
    ]);

    livewire(Filament\CreditCardResource\Index::class)
        ->assertTableActionHasUrl(EditAction::class, route('banks.credit-cards.edit', $creditCard), record: $creditCard);

})->group('tableActionsCrud');

it('can delete credit cards', function () {

    $creditCard = CreditCard::factory()->createOne([
        'user_id' => $this->user->id,
    ]);

    livewire(Filament\CreditCardResource\Index::class)
        ->callTableAction(Tables\Actions\DeleteAction::class, $creditCard);

    assertModelMissing($creditCard);

})->group('tableActionsCrud');

it('can delete credit cards and delete all spendings', function () {
    // Arrange
    $creditCard = CreditCard::factory()->createOne([
        'user_id' => $this->user->id,
    ]);

    $creditCard->spendings()->createMany([
        [
            'description' => 'Teste 1',
            'amount'      => 100,
        ],
        [
            'description' => 'Teste 2',
            'amount'      => 100,
        ],
    ]);

    expect($creditCard->spendings()->count())->toBe(2);

    // Act
    livewire(Filament\CreditCardResource\Index::class)
        ->callTableAction(Tables\Actions\DeleteAction::class, $creditCard);

    // Assert
    assertModelMissing($creditCard);

    assertDatabaseMissing('spendings', [
        'credit_card_id' => $creditCard->id,
    ]);

});

/* ###################################################################### */
/* CANNOT HAS PERMISSION */
/* ###################################################################### */
it('cannot render page if not has permission', function () {

    // Arrange
    $this->user->revokePermissionTo('credit_card_read');

    // Act
    livewire(Filament\CreditCardResource\Index::class)
        ->assertForbidden();

})->group('cannotHasPermission');

it('cannot render create action button if not has permission', function () {

    // Arrange
    $this->user->revokePermissionTo('credit_card_create');

    // Act
    livewire(Filament\CreditCardResource\Index::class)
        ->assertDontSeeHtml('Criar cartão de crédito');

})->group('cannotHasPermission');

it('can disable edit action button if not has permission', function () {

    // Arrange
    $this->user->creditCards()->save(
        $creditCard = CreditCard::factory()->makeOne()
    );

    $this->user->revokePermissionTo('credit_card_update');

    // Act
    livewire(Filament\CreditCardResource\Index::class)
        ->assertTableActionDisabled(Tables\Actions\EditAction::class, $creditCard);

})->group('cannotHasPermission');

it('can disable delete action button if not has permission', function () {

    // Arrange
    $this->user->creditCards()->save(
        $creditCard = CreditCard::factory()->makeOne()
    );

    $this->user->revokePermissionTo('credit_card_delete');

    // Act
    livewire(Filament\CreditCardResource\Index::class)
        ->assertTableActionDisabled(Tables\Actions\DeleteAction::class, $creditCard);

})->group('cannotHasPermission');
