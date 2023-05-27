<?php

namespace Livewire\Banks\Accounts;

use App\Http\Livewire\Banks;
use App\Models\{BankAccount, User};

use function Pest\Laravel\{actingAs, get};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->bankAccounts()->save(
        $this->bankAccount = BankAccount::factory()->makeOne()
    );

    $this->user->givePermissionTo('bank_account_read');

    $this->user->givePermissionTo('bank_account_create');

    $this->user->givePermissionTo('bank_account_update');

    $this->user->givePermissionTo('bank_account_delete');

    actingAs($this->user);

});

/* ###################################################################### */
/* RENDER PAGE */
/* ###################################################################### */
it('can render page', function () {

    livewire(Banks\Accounts\Index::class)
        ->assertSuccessful();

})->group('renderPage');

it('can redirect to login if not authenticated', function () {

    \Auth::logout();

    get(route('banks.accounts.index'))
        ->assertRedirect(route('login'));

})->group('renderPage');

/* ###################################################################### */
/* CAN RENDER TABLE HEADER */
/* ###################################################################### */
it('can render table heading', function () {

    livewire(Banks\Accounts\Index::class)
        ->assertSeeHtml('Contas Bancárias');

})->group('canRenderTableHeader');

it('can render create action button', function () {

    livewire(Banks\Accounts\Index::class)
        ->assertTableActionExists('create');

})->group('canRenderTableHeader');

/* ###################################################################### */
/* CAN RENDER TABLE COLUMNS */
/* ###################################################################### */
it('can render bank account id column in table', function () {

    livewire(Banks\Accounts\Index::class)
        ->assertCanRenderTableColumn('id');

})->group('canRenderTableColumns');

it('can render bank account name column in table', function () {

    livewire(Banks\Accounts\Index::class)
        ->assertCanRenderTableColumn('bank_name');

})->group('canRenderTableColumns');

it('can render bank account type column in table', function () {

    livewire(Banks\Accounts\Index::class)
        ->assertCanRenderTableColumn('type');

})->group('canRenderTableColumns');

it('can render bank account formatted agency column in table', function () {

    livewire(Banks\Accounts\Index::class)
        ->assertCanRenderTableColumn('formatted_agency');

})->group('canRenderTableColumns');

it('can render bank account formatted number in table', function () {

    livewire(Banks\Accounts\Index::class)
        ->assertCanRenderTableColumn('formatted_number');

})->group('canRenderTableColumns');

it('can render bank account formatted balance in table', function () {

    livewire(Banks\Accounts\Index::class)
        ->assertCanRenderTableColumn('formatted_balance');

})->group('canRenderTableColumns');

/* ###################################################################### */
/* RENDER DEFAULT */
/* ###################################################################### */
it('can display only my bank accounts in table', function () {

    // Arrange
    $this->user->bankAccounts()->saveMany(
        $myBankAccounts = BankAccount::factory()->count(10)->make()
    );

    $otherBankAccounts = BankAccount::factory()->count(10)->create();

    // Act
    livewire(Banks\Accounts\Index::class)
        ->assertCanSeeTableRecords($myBankAccounts)
        ->assertCountTableRecords(BankAccount::whereUserId($this->user->id)->count())
        ->assertCanNotSeeTableRecords($otherBankAccounts);

})->group('renderDefault');

it('bank accounts are sorted by default in desc order', function () {

    // Arrange
    $this->user->bankAccounts()->saveMany(
        $bankAccounts = BankAccount::factory()->count(10)->make()
    );

    // Act
    livewire(Banks\Accounts\Index::class)
        ->assertCanSeeTableRecords($bankAccounts->sortByDesc('id'), inOrder: true);

})->group('renderDefault');

it('bank account number display should be formatted to number-digit', function () {

    // Arrange
    $this->user->bankAccounts()->save(
        $bankAccount = BankAccount::factory()->makeOne()
    );

    $formattedNumber = $bankAccount->number . '-' . $bankAccount->digit;

    // Act
    livewire(Banks\Accounts\Index::class)
        ->assertTableColumnFormattedStateSet('formatted_number', $formattedNumber, record: $bankAccount);

})->group('renderDefault');

it('bank account agency display should be formatted to agency_number-agency_digit', function () {

    // Arrange
    $this->user->bankAccounts()->save(
        $bankAccount = BankAccount::factory()->makeOne([
            'agency_digit' => 0,
        ])
    );

    $formattedAgency = $bankAccount->agency_number . '-' . $bankAccount->agency_digit;

    // Act
    livewire(Banks\Accounts\Index::class)
        ->assertTableColumnFormattedStateSet('formatted_agency', $formattedAgency, record: $bankAccount);

})->group('renderDefault');

it('bank account agency display should be formatted to agency_number when agency_digit is null', function () {

    // Arrange
    $this->user->bankAccounts()->save(
        $bankAccount = BankAccount::factory()->makeOne([
            'agency_digit' => null,
        ])
    );

    $formattedAgency = $bankAccount->agency_number;

    // Act
    livewire(Banks\Accounts\Index::class)
        ->assertTableColumnFormattedStateSet('formatted_agency', $formattedAgency, record: $bankAccount);

})->group('renderDefault');

it('bank account balance display should be formatted to format R$ 9.999,99', function () {

    // Arrange
    $this->user->bankAccounts()->save(
        $bankAccount = BankAccount::factory()->makeOne()
    );

    $formattedBalance = "R$ " . number_format($bankAccount->balance, 2, ',', '.');

    // Act
    livewire(Banks\Accounts\Index::class)
        ->assertTableColumnFormattedStateSet('formatted_balance', $formattedBalance, record: $bankAccount);

})->group('renderDefault');
