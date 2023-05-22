<?php

namespace Tests\Feature\Livewire\BankAccounts\Entries;

use App\Http\Livewire\BankAccounts\Withdrawals;
use App\Models\{BankAccount, BankAccountWithdraw, User};
use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->createOne([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo('bank_account_withdraw_update');

    $this->user->bankAccounts()->save(
        $this->bankAccount = BankAccount::factory()->makeOne()
    );

    $this->bankAccount->withdrawals()->save(
        $this->withdraw = BankAccountWithdraw::factory()->makeOne()
    );

    $this->bankAccount->update([
        'balance' => $this->bankAccount->balance - $this->withdraw->value,
    ]);

    actingAs($this->user);

});

it('should be to update a withdraw', function () {
    // Arrange
    $newData = BankAccountWithdraw::factory()->makeOne();

    // Act
    livewire(\App\Http\Livewire\Banks\Accounts\Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->set('withdraw.value', $newData->value)
        ->set('withdraw.description', $newData->description)
        ->set('withdraw.date', $newData->date)
        ->call('save')
        ->assertHasNoErrors()
        ->assertEmitted('bank-account::withdraw::updated');

    // Assert
    assertDatabaseHas('bank_account_withdraws', [
        'id' => $this->withdraw->id,
        'value' => $newData->value,
        'description' => $newData->description,
        'date' => $newData->date,
    ]);

    assertDatabaseHas('bank_accounts', [
        'id' => $this->bankAccount->id,
        'balance' => ($this->bankAccount->balance + $this->withdraw->value) - $newData->value,
    ]);

});

it("should be not able to update a withdraw if not account owner", function () {
    // Arrange
    $bankAccount2 = BankAccount::factory()->createOne();

    $bankAccount2->withdrawals()->save(
        $withdraw2 = BankAccountWithdraw::factory()->makeOne()
    );

    // Act
    livewire(\App\Http\Livewire\Banks\Accounts\Withdrawals\Update::class, ['withdraw' => $withdraw2])
        ->call('save')
        ->assertForbidden();

});

it("should be not able to update a withdraw if not authenticated", function () {
    // Arrange
    \Auth::logout();

    // Act
    livewire(\App\Http\Livewire\Banks\Accounts\Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->call('save')
        ->assertForbidden();

});

it("should be not able to update a withdraw if not has permission to this", function () {
    // Arrange
    $this->user->revokePermissionTo('bank_account_withdraw_update');

    // Act
    livewire(\App\Http\Livewire\Banks\Accounts\Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->call('save')
        ->assertForbidden();

});

test('value is required', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->set('withdraw.value', null)
        ->call('save')
        ->assertHasErrors(['withdraw.value' => 'required']);

});

test('value should be a numeric', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->set('withdraw.value', 'test')
        ->call('save')
        ->assertHasErrors(['withdraw.value' => 'numeric']);

});

test('value should be a greater than zero', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->set('withdraw.value', -100)
        ->call('save')
        ->assertHasErrors(['withdraw.value' => 'min']);

    livewire(\App\Http\Livewire\Banks\Accounts\Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->set('withdraw.value', 1)
        ->call('save')
        ->assertHasNoErrors(['withdraw.value' => 'min']);

});

test('description is required', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->set('withdraw.description', '')
        ->call('save')
        ->assertHasErrors(['withdraw.description' => 'required']);

});

test('description should be a string', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->set('withdraw.description', 100)
        ->call('save')
        ->assertHasErrors(['withdraw.description' => 'string']);

});

test('description should be a less than 255 characters', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->set('withdraw.description', str_repeat('a', 256))
        ->call('save')
        ->assertHasErrors(['withdraw.description' => 'max']);

});

test('date is required', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->set('withdraw.date', '')
        ->call('save')
        ->assertHasErrors(['withdraw.date' => 'required']);

});

test('date should be a date', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->set('withdraw.date', 'test')
        ->call('save')
        ->assertHasErrors(['withdraw.date' => 'date']);

});
