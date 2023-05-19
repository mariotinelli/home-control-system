<?php

namespace Tests\Feature\Livewire\BankAccounts\Withdrawals;

use App\Http\Livewire\BankAccounts\Withdrawals;
use App\Models\{BankAccount, BankAccountWithdraw, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->createOne([
        'email' => 'teste@email.com',
    ]);

    $this->user->bankAccounts()->save(
        $this->bankAccount = BankAccount::factory()->makeOne()
    );

    $this->user->givePermissionTo('bank_account_withdraw_create');

    actingAs($this->user);

});

test("should be able to create a new withdraw", function () {
    // Arrange
    $newData = BankAccountWithdraw::factory()->makeOne();

    // Act
    livewire(Withdrawals\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('withdraw.value', $newData->value)
        ->set('withdraw.description', $newData->description)
        ->set('withdraw.date', $newData->date)
        ->call('save')
        ->assertHasNoErrors()
        ->assertEmitted('bank-account::withdraw::created');

    // Assert
    assertDatabaseHas('bank_account_withdraws', [
        'bank_account_id' => $this->bankAccount->id,
        'value'           => $newData->value,
        'description'     => $newData->description,
        'date'            => $newData->date,
    ]);

    assertDatabaseHas('bank_accounts', [
        'id'      => $this->bankAccount->id,
        'balance' => $this->bankAccount->balance - $newData->value,
    ]);

});

test("should be not able to create a new withdraw if not account owner", function () {
    // Arrange
    $bankAccount2 = BankAccount::factory()->createOne();

    // Act
    livewire(Withdrawals\Create::class, ['bankAccount' => $bankAccount2])
        ->call('save')
        ->assertForbidden();

});

test("should be not able to create a new withdraw if not authenticated", function () {
    // Arrange
    \Auth::logout();

    // Act
    livewire(Withdrawals\Create::class, ['bankAccount' => $this->bankAccount])
        ->call('save')
        ->assertForbidden();

});

test("should be not able to create a new withdraw if not has permission to this", function () {
    // Arrange
    $this->user->revokePermissionTo('bank_account_withdraw_create');

    // Act
    livewire(Withdrawals\Create::class, ['bankAccount' => $this->bankAccount])
        ->call('save')
        ->assertForbidden();

});

test('value is required', function () {

    livewire(Withdrawals\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('withdraw.value', null)
        ->call('save')
        ->assertHasErrors(['withdraw.value' => 'required']);

});

test('value should be a numeric', function () {

    livewire(Withdrawals\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('withdraw.value', 'a')
        ->call('save')
        ->assertHasErrors(['withdraw.value' => 'numeric']);

});

test('value should be a greater than zero', function () {

    livewire(Withdrawals\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('withdraw.value', -100)
        ->call('save')
        ->assertHasErrors(['withdraw.value' => 'min']);

});

test('description is required', function () {

    livewire(Withdrawals\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('withdraw.description', '')
        ->call('save')
        ->assertHasErrors(['withdraw.description' => 'required']);

});

test('description should be a string', function () {

    livewire(Withdrawals\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('withdraw.description', 100)
        ->call('save')
        ->assertHasErrors(['withdraw.description' => 'string']);

});

test('description should be a less than 255 characters', function () {

    livewire(Withdrawals\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('withdraw.description', str_repeat('a', 256))
        ->call('save')
        ->assertHasErrors(['withdraw.description' => 'max']);

});

test('date is required', function () {

    livewire(Withdrawals\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('withdraw.date', '')
        ->call('save')
        ->assertHasErrors(['withdraw.date' => 'required']);

});

test('date should be a date', function () {

    livewire(Withdrawals\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('withdraw.date', 'test')
        ->call('save')
        ->assertHasErrors(['withdraw.date' => 'date']);

});
