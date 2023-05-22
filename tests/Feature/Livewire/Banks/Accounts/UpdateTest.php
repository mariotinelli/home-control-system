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

    $this->user->givePermissionTo('bank_account_update');

    $this->user->bankAccounts()->save(
        $this->bankAccount = BankAccount::factory()->makeOne()
    );

    actingAs($this->user);

});

it('should be able to update a bank account', function () {
    // Arrange
    $newData = BankAccount::factory()->makeOne();

    // Act
    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.bank_name', $newData->bank_name)
        ->set('bankAccount.type', $newData->type)
        ->set('bankAccount.agency_number', $newData->agency_number)
        ->set('bankAccount.number', $newData->number)
        ->set('bankAccount.digit', $newData->digit)
        ->set('bankAccount.balance', $newData->balance)
        ->call('save')
        ->assertHasNoErrors()
        ->assertEmitted('bank-account::updated');

    // Assert
    assertDatabaseHas('bank_accounts', [
        'user_id' => $this->user->id,
        'bank_name' => $newData->bank_name,
        'type' => $newData->type,
        'agency_number' => $newData->agency_number,
        'number' => $newData->number,
        'digit' => $newData->digit,
        'balance' => $newData->balance,
    ]);

});

it('should be not able to update a bank account if not own it', function () {
    // Arrange
    $bankAccount2 = BankAccount::factory()->createOne();

    // Act
    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $bankAccount2])
        ->call('save')
        ->assertForbidden();

});

it("should be not able to update a bank account if not has permission to this", function () {
    // Arrange
    $this->user->revokePermissionTo('bank_account_update');

    // Act
    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->call('save')
        ->assertForbidden();

});

it("should be not able to update a bank account if not authenticated", function () {
    // Arrange
    \Auth::logout();

    // Act
    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->call('save')
        ->assertForbidden();

});

it('should be not able to update a balance when exists entries', function () {
    // Arrange
    $newData = BankAccount::factory()->makeOne();

    $this->bankAccount->entries()->create([
        'value' => 1000,
        'description' => 'Entrance 1',
        'date' => now(),
    ]);

    // Act
    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.balance', $newData->balance)
        ->call('save')
        ->assertForbidden();

});

it('should be not able to update a balance when exists withdraws', function () {
    // Arrange
    $newData = BankAccount::factory()->makeOne();

    $this->bankAccount->withdrawals()->create([
        'value' => 1000,
        'description' => 'Withdraw 1',
        'date' => now(),
    ]);

    // Act
    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.balance', $newData->balance)
        ->call('save')
        ->assertForbidden();

});

it('should be able to update a balance when not exists entries and withdraws', function () {
    // Arrange
    $newData = BankAccount::factory()->makeOne();

    expect($this->bankAccount->entries()->count())->toBe(0)
        ->and($this->bankAccount->withdrawals()->count())->toBe(0);

    // Act
    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.bank_name', $newData->bank_name)
        ->set('bankAccount.type', $newData->type)
        ->set('bankAccount.number', $newData->number)
        ->set('bankAccount.digit', $newData->digit)
        ->set('bankAccount.agency_number', $newData->agency_number)
        ->set('bankAccount.agency_digit', $newData->agency_digit)
        ->set('bankAccount.balance', $newData->balance)
        ->call('save')
        ->assertHasNoErrors()
        ->assertEmitted('bank-account::updated');

    // Assert
    assertDatabaseHas('bank_accounts', [
        'user_id' => $this->user->id,
        'bank_name' => $newData->bank_name,
        'type' => $newData->type,
        'agency_number' => $newData->agency_number,
        'number' => $newData->number,
        'digit' => $newData->digit,
        'balance' => $newData->balance,
    ]);

});

test('bank name is required', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.bank_name', '')
        ->call('save')
        ->assertHasErrors(['bankAccount.bank_name' => 'required']);

});

test('bank name should be a string', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.bank_name', 123)
        ->call('save')
        ->assertHasErrors(['bankAccount.bank_name' => 'string']);

});

test('bank name should be have a max length of 100 characters', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.bank_name', str_repeat('a', 101))
        ->call('save')
        ->assertHasErrors(['bankAccount.bank_name' => 'max']);

});

test('bank account type is required', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.type', null)
        ->call('save')
        ->assertHasErrors(['bankAccount.type' => 'required']);

});

test('number is required', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.number', '')
        ->call('save')
        ->assertHasErrors(['bankAccount.number' => 'required']);

});

test('number should be a numeric', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.number', 'abc')
        ->call('save')
        ->assertHasErrors(['bankAccount.number' => 'numeric']);

});

test('number should be unique', function () {

    $newData = BankAccount::factory()->createOne([
        'user_id' => $this->user->id,
        'number' => '123456',
    ]);

    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.number', $newData->number)
        ->call('save')
        ->assertHasErrors(['bankAccount.number' => 'unique']);

});

test('ignore number unique only when updating same bank account', function () {
    // Arrange
    $newData = BankAccount::factory()->createOne([
        'user_id' => $this->user->id,
        'number' => '123456',
    ]);

    // Act - Error
    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.number', $newData->number)
        ->call('save')
        ->assertHasErrors(['bankAccount.number' => 'unique']);

    // Act - Success
    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.number', $this->bankAccount->number)
        ->call('save')
        ->assertHasNoErrors(['bankAccount.number' => 'unique']);
});

test('number should be have a min digits of 5', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.number', (int)str_repeat('9', 4))
        ->call('save')
        ->assertHasErrors(['bankAccount.number' => 'min_digits']);

});

test('number should be have a max digits of 20', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.number', (float)str_repeat('9', 21))
        ->call('save')
        ->assertHasErrors(['bankAccount.number' => 'max_digits']);

});

test('digit is required', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.digit', '')
        ->call('save')
        ->assertHasErrors(['bankAccount.digit' => 'required']);

});

test('digit should be a numeric', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.digit', 'abc')
        ->call('save')
        ->assertHasErrors(['bankAccount.digit' => 'numeric']);

});

test('digit should be have a max digits of 1', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.digit', (int)str_repeat('9', 2))
        ->call('save')
        ->assertHasErrors(['bankAccount.digit' => 'max_digits']);

});

test('balance is required', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.balance', null)
        ->call('save')
        ->assertHasErrors(['bankAccount.balance' => 'required']);

});

test('balance should be a numeric', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.balance', 'abc')
        ->call('save')
        ->assertHasErrors(['bankAccount.balance' => 'numeric']);

});

test('agency number is required', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.agency_number', '')
        ->call('save')
        ->assertHasErrors(['bankAccount.agency_number' => 'required']);

});

test('agency number should be a numeric', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.agency_number', 'abc')
        ->call('save')
        ->assertHasErrors(['bankAccount.agency_number' => 'numeric']);

});

test('agency number should be have a min digits of 4', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.agency_number', (int)str_repeat('9', 3))
        ->call('save')
        ->assertHasErrors(['bankAccount.agency_number' => 'min_digits']);

});

test('agency number should be have a max digits of 4', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.agency_number', (int)str_repeat('9', 5))
        ->call('save')
        ->assertHasErrors(['bankAccount.agency_number' => 'max_digits']);

});

test('agency digit is nullable', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.agency_digit', null)
        ->call('save')
        ->assertHasNoErrors(['bankAccount.agency_digit']);

});

test('agency digit should be a numeric', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.agency_digit', 'a')
        ->call('save')
        ->assertHasErrors(['bankAccount.agency_digit' => 'numeric']);

});

test('agency digit should be have a max digits of 1', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Update::class, ['bankAccount' => $this->bankAccount])
        ->set('bankAccount.agency_digit', (int)str_repeat('9', 2))
        ->call('save')
        ->assertHasErrors(['bankAccount.agency_digit' => 'max_digits']);

});
