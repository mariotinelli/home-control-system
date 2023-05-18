<?php

namespace Tests\Feature\Livewire\BankAccounts\Entries;

use App\Http\Livewire\BankAccounts\Withdrawals;
use App\Models\{BankAccount, BankAccountWithdraw, User};

use function Pest\Laravel\{actingAs};
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

it('should be to update a withdraw if are the account owner', function () {
    // Arrange
    $newData = BankAccountWithdraw::factory()->makeOne();

    $notOwner = User::factory()->createOne();

    $notOwner->givePermissionTo('bank_account_withdraw_update');

    // Act - Not owner
    actingAs($notOwner);

    livewire(Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->call('save')
        ->assertForbidden();

    // Act - Owner
    actingAs($this->user);

    livewire(Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->set('withdraw.value', $newData->value)
        ->set('withdraw.description', $newData->description)
        ->set('withdraw.date', $newData->date)
        ->call('save')
        ->assertHasNoErrors()
        ->assertEmitted('bank-account::withdraw::updated');

    // Assert
    $oldWithdrawValue = $this->withdraw->getOriginal('value');

    $oldBalance = $this->bankAccount->balance;

    expect($this->withdraw->refresh()->value)
        ->toBe($newData->value)
        ->and($this->withdraw->refresh())
            ->description->toBe($newData->description)
            ->date->toBe($newData->date);

    $newBalance = number_format(($oldBalance + $oldWithdrawValue) - $newData->value, 2, '.', '');

    expect($this->bankAccount->refresh())
        ->balance->toBe($newBalance);

});

it("should be to update a withdraw if has the 'bank_account_entry_update' permission", function () {
    // Arrange
    $newData = BankAccountWithdraw::factory()->makeOne();

    $notHasPermission = User::factory()->createOne();

    // Act - Not owner
    actingAs($notHasPermission);

    livewire(Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->call('save')
        ->assertForbidden();

    // Act - Owner
    actingAs($this->user);

    livewire(Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->set('withdraw.value', $newData->value)
        ->set('withdraw.description', $newData->description)
        ->set('withdraw.date', $newData->date)
        ->call('save')
        ->assertHasNoErrors()
        ->assertEmitted('bank-account::withdraw::updated');

    // Assert
    $oldWithdrawValue = $this->withdraw->getOriginal('value');

    $oldBalance = $this->bankAccount->balance;

    expect($this->withdraw->refresh()->value)
        ->toBe($newData->value)
        ->and($this->withdraw->refresh())
        ->description->toBe($newData->description)
        ->date->toBe($newData->date);

    $newBalance = number_format(($oldBalance + $oldWithdrawValue) - $newData->value, 2, '.', '');

    expect($this->bankAccount->refresh())
        ->balance->toBe($newBalance);

});

test('value is required', function () {

    livewire(Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->set('withdraw.value', null)
        ->call('save')
        ->assertHasErrors(['withdraw.value' => 'required']);

});

test('value should be a greater than zero', function () {

    livewire(Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->set('withdraw.value', -100)
        ->call('save')
        ->assertHasErrors(['withdraw.value' => 'min']);

    livewire(Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->set('withdraw.value', 1)
        ->call('save')
        ->assertHasNoErrors(['withdraw.value' => 'min']);

});

test('description is required', function () {

    livewire(Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->set('withdraw.description', '')
        ->call('save')
        ->assertHasErrors(['withdraw.description' => 'required']);

});

test('description should be a string', function () {

    livewire(Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->set('withdraw.description', 100)
        ->call('save')
        ->assertHasErrors(['withdraw.description' => 'string']);

});

test('description should be a less than 255 characters', function () {

    livewire(Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->set('withdraw.description', str_repeat('a', 256))
        ->call('save')
        ->assertHasErrors(['withdraw.description' => 'max']);

});

test('date is required', function () {

    livewire(Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->set('withdraw.date', '')
        ->call('save')
        ->assertHasErrors(['withdraw.date' => 'required']);

});

test('date should be a date', function () {

    livewire(Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->set('withdraw.date', 'test')
        ->call('save')
        ->assertHasErrors(['withdraw.date' => 'date']);

});
