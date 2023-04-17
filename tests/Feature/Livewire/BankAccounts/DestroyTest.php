<?php

namespace Tests\Feature\Livewire\BankAccounts;

use App\Http\Livewire\BankAccounts;
use App\Models\BankAccount;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->faker = \Faker\Factory::create();

    $this->user = User::factory()->createOne();

    $this->bankAccount = BankAccount::factory()->createOne([
        'user_id' => $this->user->id
    ]);

    actingAs($this->user);

});

it('should be able to destroy a bank account', function () {

    assertDatabaseHas('bank_accounts', [
        'user_id' => $this->user->id,
        'bank_name' => $this->bankAccount->bank_name,
        'type' => $this->bankAccount->type,
        'agency_number' => $this->bankAccount->agency_number,
        'number' => $this->bankAccount->number,
        'digit' => $this->bankAccount->digit,
        'balance' => $this->bankAccount->balance
    ]);

    livewire(BankAccounts\Destroy::class, ['bankAccount' => $this->bankAccount])
        ->call('destroy')
        ->assertEmitted('bank-account::destroyed');

    assertDatabaseMissing('bank_accounts', [
        'user_id' => $this->user->id,
        'bank_name' => $this->bankAccount->bank_name,
        'type' => $this->bankAccount->type,
        'agency_number' => $this->bankAccount->agency_number,
        'number' => $this->bankAccount->number,
        'digit' => $this->bankAccount->digit,
        'balance' => $this->bankAccount->balance
    ]);

});

it('should be able to delete a bank account only bank account owner', function () {

    actingAs(User::factory()->createOne());

    livewire(BankAccounts\Destroy::class, ['bankAccount' => $this->bankAccount])
        ->call('destroy')
        ->assertForbidden();

    actingAs($this->user);

    livewire(BankAccounts\Destroy::class, ['bankAccount' => $this->bankAccount])
        ->call('destroy')
        ->assertEmitted('bank-account::destroyed');

    assertDatabaseMissing('bank_accounts', [
        'user_id' => $this->user->id,
        'bank_name' => $this->bankAccount->bank_name,
        'type' => $this->bankAccount->type,
        'agency_number' => $this->bankAccount->agency_number,
        'number' => $this->bankAccount->number,
        'digit' => $this->bankAccount->digit,
        'balance' => $this->bankAccount->balance
    ]);
});
