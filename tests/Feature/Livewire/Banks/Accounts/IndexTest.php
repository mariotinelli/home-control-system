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

it('bank account number display in format number-digit', function () {

    // Arrange
    $this->user->bankAccounts()->save(
        $bankAccount = BankAccount::factory()->makeOne()
    );

    $formattedNumber = $bankAccount->number . '-' . $bankAccount->digit;

    // Act
    livewire(Banks\Accounts\Index::class)
        ->assertTableColumnFormattedStateSet('formatted_number', $formattedNumber, record: $bankAccount);

})->group('renderDefault');

it('bank account agency display in format agency_number-agency_digit', function () {

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

it('bank account agency display in format agency_number when agency_digit is null', function () {

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

it('bank account balance display in format R$ 9.999,99', function () {

    // Arrange
    $this->user->bankAccounts()->save(
        $bankAccount = BankAccount::factory()->makeOne()
    );

    $formattedBalance = "R$ " . number_format($bankAccount->balance, 2, ',', '.');

    // Act
    livewire(Banks\Accounts\Index::class)
        ->assertTableColumnFormattedStateSet('formatted_balance', $formattedBalance, record: $bankAccount);

})->group('renderDefault');

/* ###################################################################### */
/* SORT COLUMN TABLE */
/* ###################################################################### */
it('can sort spending by id', function () {

    // Arrange
    $this->user->bankAccounts()->saveMany(
        $bankAccounts = BankAccount::factory()->count(3)->make()
    );

    livewire(Banks\Accounts\Index::class)
        ->sortTable('id')
        ->assertCanSeeTableRecords($bankAccounts->sortBy('id'), inOrder: true)
        ->sortTable('id', 'desc')
        ->assertCanSeeTableRecords($bankAccounts->sortByDesc('id'), inOrder: true);

})->group('canSortTable');

it('can sort spending by bank name', function () {

    // Arrange
    $this->user->bankAccounts()->saveMany(
        $bankAccounts = BankAccount::factory()->count(5)->make()
    );

    livewire(Banks\Accounts\Index::class)
        ->sortTable('bank_name')
        ->assertCanSeeTableRecords($bankAccounts->sortBy('bank_name'), inOrder: true)
        ->sortTable('bank_name', 'desc')
        ->assertCanSeeTableRecords($bankAccounts->sortByDesc('bank_name'), inOrder: true);

})->group('canSortTable');

it('can sort spending by type', function () {

    // Arrange
    $this->user->bankAccounts()->saveMany(
        BankAccount::factory()->count(5)->make()
    );

    $bankAccountsAsc = BankAccount::whereUserId($this->user->id)
        ->orderBy('type')
        ->get();

    $bankAccountsDesc = BankAccount::whereUserId($this->user->id)
        ->orderByDesc('type')
        ->get();

    // Act
    livewire(Banks\Accounts\Index::class)
        ->sortTable('type')
        ->assertCanSeeTableRecords($bankAccountsAsc, inOrder: true)
        ->sortTable('type', 'desc')
        ->assertCanSeeTableRecords($bankAccountsDesc, inOrder: true);

})->group('canSortTable');

it('can sort spending by formatted agency', function () {

    // Arrange
    $this->user->bankAccounts()->saveMany(
        BankAccount::factory()->count(5)->make()
    );

    $bankAccountsAsc = BankAccount::whereUserId($this->user->id)
        ->orderBy('agency_number')
        ->orderBy('agency_digit')
        ->get();

    $bankAccountsDesc = BankAccount::whereUserId($this->user->id)
        ->orderByDesc('agency_number')
        ->orderByDesc('agency_digit')
        ->get();

    // Act
    livewire(Banks\Accounts\Index::class)
        ->sortTable('formatted_agency')
        ->assertCanSeeTableRecords($bankAccountsAsc, inOrder: true)
        ->sortTable('formatted_agency', 'desc')
        ->assertCanSeeTableRecords($bankAccountsDesc, inOrder: true);

})->group('canSortTable');

it('can sort spending by formatted number', function () {

    // Arrange
    $this->user->bankAccounts()->saveMany(
        BankAccount::factory()->count(5)->make()
    );

    $bankAccountsAsc = BankAccount::whereUserId($this->user->id)
        ->orderBy('number')
        ->orderBy('digit')
        ->get();

    $bankAccountsDesc = BankAccount::whereUserId($this->user->id)
        ->orderByDesc('number')
        ->orderByDesc('digit')
        ->get();

    // Act
    livewire(Banks\Accounts\Index::class)
        ->sortTable('formatted_number')
        ->assertCanSeeTableRecords($bankAccountsAsc, inOrder: true)
        ->sortTable('formatted_number', 'desc')
        ->assertCanSeeTableRecords($bankAccountsDesc, inOrder: true);

})->group('canSortTable');

it('can sort spending by formatted balance', function () {

    // Arrange
    $this->user->bankAccounts()->saveMany(
        $bankAccounts = BankAccount::factory()->count(5)->make()
    );

    // Act
    livewire(Banks\Accounts\Index::class)
        ->sortTable('formatted_balance')
        ->assertCanSeeTableRecords($bankAccounts->sortBy('balance'), inOrder: true)
        ->sortTable('formatted_balance', 'desc')
        ->assertCanSeeTableRecords($bankAccounts->sortByDesc('balance'), inOrder: true);

})->group('canSortTable');

/* ###################################################################### */
/* HEADER SEARCH TABLE */
/* ###################################################################### */
it('can search bank accounts from header search', function () {

    // Arrange
    $this->user->bankAccounts()->saveMany(
        BankAccount::factory()->count(5)->make()
    );

    $fakeNumber = fake()->numberBetween(1, 5);

    $search = $fakeNumber % 2 == 0
        ? $fakeNumber
        : fake()->sentence(1);

    $canSeeRecord = BankAccount::whereUserId($this->user->id)->where('id', 'LIKE', "%{$search}%")
        ->orWhere('bank_name', 'LIKE', "%{$search}%")
        ->orWhere('type', 'LIKE', "%{$search}%")
        ->orWhere('agency_number', 'LIKE', "%{$search}%")
        ->orWhere('agency_digit', 'LIKE', "%{$search}%")
        ->orWhere('number', 'LIKE', "%{$search}%")
        ->orWhere('digit', 'LIKE', "%{$search}%")
        ->orWhere('balance', 'LIKE', "%{$search}%")
        ->get();

    $cannotSeeRecord = BankAccount::whereUserId($this->user->id)->where('id', 'NOT LIKE', "%{$search}%")
        ->where('bank_name', 'NOT LIKE', "%{$search}%")
        ->where('type', 'NOT LIKE', "%{$search}%")
        ->where('agency_number', 'NOT LIKE', "%{$search}%")
        ->where('agency_digit', 'NOT LIKE', "%{$search}%")
        ->where('number', 'NOT LIKE', "%{$search}%")
        ->where('digit', 'NOT LIKE', "%{$search}%")
        ->where('balance', 'NOT LIKE', "%{$search}%")
        ->get();

    // Act
    livewire(Banks\Accounts\Index::class)
        ->searchTable($search)
        ->assertCanSeeTableRecords($canSeeRecord)
        ->assertCanNotSeeTableRecords($cannotSeeRecord);

})->group('canHeaderSearchTable');
