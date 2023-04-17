<?php

namespace Tests\Feature\Livewire\BankAccounts;

use App\Http\Livewire\BankAccounts;
use App\Models\BankAccount;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;


beforeEach(function () {

    $this->faker = \Faker\Factory::create();

    $this->user = User::factory()->createOne();

    $this->bankAccount = BankAccount::factory()->createOne([
        'user_id' => $this->user->id
    ]);

    actingAs($this->user);

});

it('should be able to update a bank account', function () {

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.bank_name', 'Bank name Update')
        ->set('bankAccount.type', 'Corrente Update')
        ->set('bankAccount.agency_number', 4321)
        ->set('bankAccount.number', '654321')
        ->set('bankAccount.digit', 2)
        ->set('bankAccount.balance', 2000)
        ->call('save')
        ->assertEmitted('bank-account::updated');

    assertDatabaseHas('bank_accounts', [
        'user_id' => $this->user->id,
        'bank_name' => 'Bank name Update',
        'type' => 'Corrente Update',
        'agency_number' => 4321,
        'number' => '654321',
        'digit' => 2,
        'balance' => 2000
    ]);

});

it('should be able to update a bank account only bank account owner', function () {

    actingAs(User::factory()->createOne());

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->call('save')
        ->assertForbidden();

    actingAs($this->user);

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.bank_name', 'Bank name Update')
        ->set('bankAccount.type', 'Corrente Update')
        ->set('bankAccount.agency_number', 4321)
        ->set('bankAccount.number', '654321')
        ->set('bankAccount.digit', 2)
        ->set('bankAccount.balance', 2000)
        ->call('save')
        ->assertEmitted('bank-account::updated');

    assertDatabaseHas('bank_accounts', [
        'user_id' => $this->user->id,
        'bank_name' => 'Bank name Update',
        'type' => 'Corrente Update',
        'agency_number' => 4321,
        'number' => '654321',
        'digit' => 2,
        'balance' => 2000
    ]);

});

todo('should be able to update a bank account only when not exists entrances and exits of values');

test('bank name is required', function () {

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.bank_name', '')
        ->call('save')
        ->assertHasErrors(['bankAccount.bank_name' => 'required']);

});

test('bank name should be a string', function () {

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.bank_name', 123)
        ->call('save')
        ->assertHasErrors(['bankAccount.bank_name' => 'string']);

});

test('bank name should be have a max length of 100 characters', function () {

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.bank_name', str_repeat('a', 101))
        ->call('save')
        ->assertHasErrors(['bankAccount.bank_name' => 'max']);

});

test('type is required', function () {

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.type', '')
        ->call('save')
        ->assertHasErrors(['bankAccount.type' => 'required']);

});

test('type should be a string', function () {

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.type', 123)
        ->call('save')
        ->assertHasErrors(['bankAccount.type' => 'string']);

});

test('type should be have a max length of 100 characters', function () {

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.type', str_repeat('a', 101))
        ->call('save')
        ->assertHasErrors(['bankAccount.type' => 'max']);

});

test('number is required', function () {

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.number', '')
        ->call('save')
        ->assertHasErrors(['bankAccount.number' => 'required']);

});

test('number should be a string', function () {

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.number', 123)
        ->call('save')
        ->assertHasErrors(['bankAccount.number' => 'string']);

});

test('ignore number unique only when updating same bank account', function () {

    $newBankAccount = BankAccount::factory()->createOne([
        'user_id' => $this->user->id,
        'number' => '123456'
    ]);

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.number', $newBankAccount->number)
        ->call('save')
        ->assertHasErrors(['bankAccount.number' => 'unique']);

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.number', $this->bankAccount->number)
        ->call('save')
        ->assertHasNoErrors(['bankAccount.number' => 'unique']);
});

test('number should be unique', function () {

    $newBankAccount = BankAccount::factory()->createOne([
        'user_id' => $this->user->id,
        'number' => '123456'
    ]);

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.number', $newBankAccount->number)
        ->call('save')
        ->assertHasErrors(['bankAccount.number' => 'unique']);

});

test('number should be have a min length of 5 characters', function () {

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.number', str_repeat('9', 4))
        ->call('save')
        ->assertHasErrors(['bankAccount.number' => 'min']);

});


test('number should be have a max length of 20 characters', function () {

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.number', str_repeat('9', 21))
        ->call('save')
        ->assertHasErrors(['bankAccount.number' => 'max']);

});

test('digit is required', function () {

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.digit', '')
        ->call('save')
        ->assertHasErrors(['bankAccount.digit' => 'required']);

});

test('digit should be a numeric', function () {

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.digit', 'abc')
        ->call('save')
        ->assertHasErrors(['bankAccount.digit' => 'numeric']);

});

test('digit should be have a max digits of 1', function () {

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.digit', $this->faker->randomNumber(2))
        ->call('save')
        ->assertHasErrors(['bankAccount.digit' => 'max_digits']);

});

test('balance is required', function () {

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.balance', '')
        ->call('save')
        ->assertHasErrors(['bankAccount.balance' => 'required']);

});

test('balance should be a numeric', function () {

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.balance', 'abc')
        ->call('save')
        ->assertHasErrors(['bankAccount.balance' => 'numeric']);

});

test('balance should be have a max digits of 10 digits', function () {

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.balance', 11111111111)
        ->call('save')
        ->assertHasErrors(['bankAccount.balance' => 'max_digits']);

});

test('agency number is required', function () {

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.agency_number', '')
        ->call('save')
        ->assertHasErrors(['bankAccount.agency_number' => 'required']);

});

test('agency number should be a numeric', function () {

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.agency_number', 'abc')
        ->call('save')
        ->assertHasErrors(['bankAccount.agency_number' => 'numeric']);

});

test('agency number should be have a min digits of 4', function () {

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.agency_number', $this->faker->randomNumber(3))
        ->call('save')
        ->assertHasErrors(['bankAccount.agency_number' => 'min_digits']);

});

test('agency number should be have a max digits of 4', function () {

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.agency_number', $this->faker->randomNumber(5))
        ->call('save')
        ->assertHasErrors(['bankAccount.agency_number' => 'max_digits']);

});

test('agency digit is nullable', function () {

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.agency_digit', '')
        ->call('save')
        ->assertHasNoErrors(['bankAccount.agency_digit']);

});

test('agency digit should be a numeric', function () {

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.agency_digit', 'a')
        ->call('save')
        ->assertHasErrors(['bankAccount.agency_digit' => 'numeric']);

});

test('agency digit should be have a max digits of 1', function () {

    livewire(BankAccounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.agency_digit', $this->faker->randomNumber(2))
        ->call('save')
        ->assertHasErrors(['bankAccount.agency_digit' => 'max_digits']);

});
