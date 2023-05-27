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
