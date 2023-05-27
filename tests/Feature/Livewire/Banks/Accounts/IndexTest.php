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
