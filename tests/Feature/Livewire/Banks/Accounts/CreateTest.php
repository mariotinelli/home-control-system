<?php

namespace Tests\Feature\Livewire\BankAccounts;

use App\Http\Livewire\BankAccounts;
use App\Models\{BankAccount, User};
use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->createOne([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo('bank_account_create');

    actingAs($this->user);

});

it('should be able to create a bank account', function () {
    // Arrange
    $newData = BankAccount::factory()->makeOne();

    // Act
    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
        ->set('bankAccount.bank_name', $newData->bank_name)
        ->set('bankAccount.type', $newData->type)
        ->set('bankAccount.number', $newData->number)
        ->set('bankAccount.digit', $newData->digit)
        ->set('bankAccount.agency_number', $newData->agency_number)
        ->set('bankAccount.agency_digit', $newData->agency_digit)
        ->set('bankAccount.balance', $newData->balance)
        ->call('save')
        ->assertEmitted('bank-account::created');

    // Assert
    assertDatabaseHas('bank_accounts', [
        'user_id' => $this->user->id,
        'bank_name' => $newData->bank_name,
        'type' => $newData->type,
        'number' => $newData->number,
        'digit' => $newData->digit,
        'agency_number' => $newData->agency_number,
        'agency_digit' => $newData->agency_digit,
        'balance' => $newData->balance,
    ]);

});

it("should be not able to create a bank account if not has permission to this", function () {
    // Arrange
    $this->user->revokePermissionTo('bank_account_create');

    // Act
    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
        ->call('save')
        ->assertForbidden();

});

it("should be not able to create a bank account if not authenticated", function () {
    // Arrange
    \Auth::logout();

    // Act
    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
        ->call('save')
        ->assertForbidden();

});

test('bank name is required', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
        ->set('bankAccount.bank_name', '')
        ->call('save')
        ->assertHasErrors(['bankAccount.bank_name' => 'required']);

});

test('bank name should be a string', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
        ->set('bankAccount.bank_name', 123)
        ->call('save')
        ->assertHasErrors(['bankAccount.bank_name' => 'string']);

});

test('bank name should be have a max length of 100 characters', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
        ->set('bankAccount.bank_name', str_repeat('a', 101))
        ->call('save')
        ->assertHasErrors(['bankAccount.bank_name' => 'max']);

});

test('type is required', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
        ->set('bankAccount.type', null)
        ->call('save')
        ->assertHasErrors(['bankAccount.type' => 'required']);

});

test('number is required', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
        ->set('bankAccount.number', '')
        ->call('save')
        ->assertHasErrors(['bankAccount.number' => 'required']);

});

test('number should be a numeric', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
        ->set('bankAccount.number', 'abc')
        ->call('save')
        ->assertHasErrors(['bankAccount.number' => 'numeric']);

});

test('number should be unique', function () {
    // Arrange
    $newBankAccount = BankAccount::factory()->createOne([
        'user_id' => $this->user->id,
        'number' => '123456',
    ]);

    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
        ->set('bankAccount.number', $newBankAccount->number)
        ->call('save')
        ->assertHasErrors(['bankAccount.number' => 'unique']);

});

test('number should be have a min digits of 5', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
        ->set('bankAccount.number', (int)str_repeat('9', 4))
        ->call('save')
        ->assertHasErrors(['bankAccount.number' => 'min_digits']);

});

test('number should be have a max digits of 20', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
        ->set('bankAccount.number', (float)str_repeat('9', 21))
        ->call('save')
        ->assertHasErrors(['bankAccount.number' => 'max_digits']);

});

test('digit is required', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
        ->set('bankAccount.digit', null)
        ->call('save')
        ->assertHasErrors(['bankAccount.digit' => 'required']);

});

test('digit should be a numeric', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
        ->set('bankAccount.digit', 'abc')
        ->call('save')
        ->assertHasErrors(['bankAccount.digit' => 'numeric']);

});

test('digit should be have a max digits of 1', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
        ->set('bankAccount.digit', (int)str_repeat('9', 2))
        ->call('save')
        ->assertHasErrors(['bankAccount.digit' => 'max_digits']);

});

test('balance is required', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
        ->set('bankAccount.balance', null)
        ->call('save')
        ->assertHasErrors(['bankAccount.balance' => 'required']);

});

test('balance should be a numeric', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
        ->set('bankAccount.balance', 'abc')
        ->call('save')
        ->assertHasErrors(['bankAccount.balance' => 'numeric']);

});

test('agency number is required', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
        ->set('bankAccount.agency_number', '')
        ->call('save')
        ->assertHasErrors(['bankAccount.agency_number' => 'required']);

});

test('agency number should be a numeric', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
        ->set('bankAccount.agency_number', 'abc')
        ->call('save')
        ->assertHasErrors(['bankAccount.agency_number' => 'numeric']);

});

test('agency number should be have a min digits of 4', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
        ->set('bankAccount.agency_number', (int)str_repeat('9', 3))
        ->call('save')
        ->assertHasErrors(['bankAccount.agency_number' => 'min_digits']);

});

test('agency number should be have a max digits of 4', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
        ->set('bankAccount.agency_number', (int)str_repeat('9', 5))
        ->call('save')
        ->assertHasErrors(['bankAccount.agency_number' => 'max_digits']);

});

test('agency digit is nullable', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
        ->set('bankAccount.agency_digit', '')
        ->call('save')
        ->assertHasNoErrors(['bankAccount.agency_digit']);

});

test('agency digit should be a numeric', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
        ->set('bankAccount.agency_digit', 'a')
        ->call('save')
        ->assertHasErrors(['bankAccount.agency_digit' => 'numeric']);

});

test('agency digit should be have a max digits of 1', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
        ->set('bankAccount.agency_digit', (int)str_repeat('9', 2))
        ->call('save')
        ->assertHasErrors(['bankAccount.agency_digit' => 'max_digits']);

});
