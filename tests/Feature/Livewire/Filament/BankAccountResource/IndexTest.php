<?php

namespace Livewire\Banks\Accounts;

use App\Http\Livewire\Filament;
use App\Models\{BankAccount, User};
use Filament\Pages\Actions\{CreateAction, EditAction};
use Filament\Tables;

use function Pest\Laravel\{actingAs, assertDatabaseMissing, assertModelMissing, get};
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

    livewire(Filament\BankAccountResource\Index::class)
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

    livewire(Filament\BankAccountResource\Index::class)
        ->assertSeeHtml('Contas Bancárias');

})->group('canRenderTableHeader');

it('can render create action button', function () {

    livewire(Filament\BankAccountResource\Index::class)
        ->assertTableActionExists('create');

})->group('canRenderTableHeader');

/* ###################################################################### */
/* CAN RENDER TABLE COLUMNS */
/* ###################################################################### */
it('can render bank account id column in table', function () {

    livewire(Filament\BankAccountResource\Index::class)
        ->assertCanRenderTableColumn('id');

})->group('canRenderTableColumns');

it('can render bank account name column in table', function () {

    livewire(Filament\BankAccountResource\Index::class)
        ->assertCanRenderTableColumn('bank_name');

})->group('canRenderTableColumns');

it('can render bank account type column in table', function () {

    livewire(Filament\BankAccountResource\Index::class)
        ->assertCanRenderTableColumn('type');

})->group('canRenderTableColumns');

it('can render bank account formatted agency column in table', function () {

    livewire(Filament\BankAccountResource\Index::class)
        ->assertCanRenderTableColumn('formatted_agency');

})->group('canRenderTableColumns');

it('can render bank account formatted number in table', function () {

    livewire(Filament\BankAccountResource\Index::class)
        ->assertCanRenderTableColumn('formatted_number');

})->group('canRenderTableColumns');

it('can render bank account formatted balance in table', function () {

    livewire(Filament\BankAccountResource\Index::class)
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
    livewire(Filament\BankAccountResource\Index::class)
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
    livewire(Filament\BankAccountResource\Index::class)
        ->assertCanSeeTableRecords($bankAccounts->sortByDesc('id'), inOrder: true);

})->group('renderDefault');

it('bank account number display in format number-digit', function () {

    // Arrange
    $this->user->bankAccounts()->save(
        $bankAccount = BankAccount::factory()->makeOne()
    );

    $formattedNumber = $bankAccount->number . '-' . $bankAccount->digit;

    // Act
    livewire(Filament\BankAccountResource\Index::class)
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
    livewire(Filament\BankAccountResource\Index::class)
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
    livewire(Filament\BankAccountResource\Index::class)
        ->assertTableColumnFormattedStateSet('formatted_agency', $formattedAgency, record: $bankAccount);

})->group('renderDefault');

it('bank account balance display in format R$ 9.999,99', function () {

    // Arrange
    $this->user->bankAccounts()->save(
        $bankAccount = BankAccount::factory()->makeOne()
    );

    $formattedBalance = "R$ " . number_format($bankAccount->balance, 2, ',', '.');

    // Act
    livewire(Filament\BankAccountResource\Index::class)
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

    livewire(Filament\BankAccountResource\Index::class)
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

    livewire(Filament\BankAccountResource\Index::class)
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
    livewire(Filament\BankAccountResource\Index::class)
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
    livewire(Filament\BankAccountResource\Index::class)
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
    livewire(Filament\BankAccountResource\Index::class)
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
    livewire(Filament\BankAccountResource\Index::class)
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
    livewire(Filament\BankAccountResource\Index::class)
        ->searchTable($search)
        ->assertCanSeeTableRecords($canSeeRecord)
        ->assertCanNotSeeTableRecords($cannotSeeRecord);

})->group('canHeaderSearchTable');

/* ###################################################################### */
/* TABLE ACTIONS CRUD */
/* ###################################################################### */
it('can render all table row actions', function () {

    $this->user->bankAccounts()->saveMany(
        BankAccount::factory()->count(5)->make()
    );

    livewire(Filament\BankAccountResource\Index::class)
        ->assertTableActionExists(Tables\Actions\EditAction::class);

    livewire(Filament\BankAccountResource\Index::class)
        ->assertTableActionExists(Tables\Actions\DeleteAction::class);

})->group('tableActionsCrud');

it('can redirect to create page on click create button', function () {

    livewire(Filament\BankAccountResource\Index::class)
        ->assertTableActionHasUrl(CreateAction::class, route('banks.accounts.create'));

})->group('tableActionsCrud');

it('can redirect to edit page on click edit button', function () {

    $bankAccount = BankAccount::factory()->create([
        'user_id' => $this->user->id,
    ]);

    livewire(Filament\BankAccountResource\Index::class)
        ->assertTableActionHasUrl(EditAction::class, route('banks.accounts.edit', $bankAccount), record: $bankAccount);

})->group('tableActionsCrud');

it('can delete bank accounts', function () {

    $bankAccount = BankAccount::factory()->createOne([
        'user_id' => $this->user->id,
    ]);

    livewire(Filament\BankAccountResource\Index::class)
        ->callTableAction(Tables\Actions\DeleteAction::class, $bankAccount);

    assertModelMissing($bankAccount);

})->group('tableActionsCrud');

it('can delete bank accounts and delete all entries and withdrawals', function () {
    // Arrange
    $bankAccount = BankAccount::factory()->createOne([
        'user_id' => $this->user->id,
    ]);

    $bankAccount->entries()->createMany([
        [
            'description' => 'Teste 1',
            'value'       => 100,
            'date'        => now(),
        ],
        [
            'description' => 'Teste 2',
            'value'       => 100,
            'date'        => now(),
        ],
    ]);

    $bankAccount->withdrawals()->createMany([
        [
            'description' => 'Teste 1',
            'value'       => 100,
            'date'        => now(),
        ],
        [
            'description' => 'Teste 2',
            'value'       => 100,
            'date'        => now(),
        ],
    ]);

    expect($bankAccount->entries()->count())->toBe(2)
        ->and($bankAccount->withdrawals()->count())->toBe(2);

    // Act
    livewire(Filament\BankAccountResource\Index::class)
        ->callTableAction(Tables\Actions\DeleteAction::class, $bankAccount);

    // Assert
    assertModelMissing($bankAccount);

    assertDatabaseMissing('bank_account_entries', [
        'bank_account_id' => $bankAccount->id,
    ]);

    assertDatabaseMissing('bank_account_withdraws', [
        'bank_account_id' => $bankAccount->id,
    ]);
});

/* ###################################################################### */
/* CANNOT HAS PERMISSION */
/* ###################################################################### */
it('cannot render page if not has permission', function () {

    // Arrange
    $this->user->revokePermissionTo('bank_account_read');

    // Act
    livewire(Filament\BankAccountResource\Index::class)
        ->assertForbidden();

})->group('cannotHasPermission');

it('cannot render create action button if not has permission', function () {

    // Arrange
    $this->user->revokePermissionTo('bank_account_create');

    // Act
    livewire(Filament\BankAccountResource\Index::class)
        ->assertDontSeeHtml('Criar conta bancária');

})->group('cannotHasPermission');

it('can disable edit action button if not has permission', function () {

    // Arrange
    $this->user->bankAccounts()->save(
        $bankAccount = BankAccount::factory()->makeOne()
    );

    $this->user->revokePermissionTo('bank_account_update');

    // Act
    livewire(Filament\BankAccountResource\Index::class)
        ->assertTableActionDisabled(Tables\Actions\EditAction::class, $bankAccount);

})->group('cannotHasPermission');

it('can disable delete action button if not has permission', function () {

    // Arrange
    $this->user->bankAccounts()->save(
        $bankAccount = BankAccount::factory()->makeOne()
    );

    $this->user->revokePermissionTo('bank_account_delete');

    // Act
    livewire(Filament\BankAccountResource\Index::class)
        ->assertTableActionDisabled(Tables\Actions\DeleteAction::class, $bankAccount);

})->group('cannotHasPermission');
