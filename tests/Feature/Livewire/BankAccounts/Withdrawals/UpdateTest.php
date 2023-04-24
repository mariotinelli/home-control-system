<?php

namespace Tests\Feature\Livewire\BankAccounts\Entries;

use App\Http\Livewire\BankAccounts\Withdrawals;
use App\Models\{BankAccount, BankAccountWithdraw, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->createOne();

    $this->bankAccount = BankAccount::factory()->createOne([
        'user_id' => $this->user->id,
    ]);

    $this->withdraw = BankAccountWithdraw::factory()->createOne([
        'bank_account_id' => $this->bankAccount->id,
    ]);

    $this->bankAccount->update([
        'balance' => $this->bankAccount->balance - $this->withdraw->value,
    ]);

    actingAs($this->user);

});

it('should be to update a withdraw', function () {

    assertDatabaseHas('bank_account_withdraws', [
        'bank_account_id' => $this->bankAccount->id,
        'value'           => $this->withdraw->value,
        'description'     => $this->withdraw->description,
        'date'            => $this->withdraw->date,
    ]);

    assertDatabaseHas('bank_accounts', [
        'id'      => $this->bankAccount->id,
        'balance' => $this->bankAccount->balance,
    ]);

    livewire(Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->set('withdraw.value', 200)
        ->set('withdraw.description', 'Test Update')
        ->set('withdraw.date', now()->format('Y-m-d'))
        ->call('save')
        ->assertEmitted('bank-account::withdraw::updated');

    assertDatabaseHas('bank_account_withdraws', [
        'bank_account_id' => $this->bankAccount->id,
        'value'           => 200,
        'description'     => 'Test Update',
        'date'            => now()->format('Y-m-d'),
    ]);

    assertDatabaseHas('bank_accounts', [
        'id'      => $this->bankAccount->id,
        'balance' => ($this->bankAccount->balance + $this->withdraw->value) - 200,
    ]);

});

it('should be to update a withdraw only bank account owner', function () {

    actingAs(User::factory()->createOne());

    livewire(Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->call('save')
        ->assertForbidden();

    actingAs($this->user);

    assertDatabaseHas('bank_account_withdraws', [
        'bank_account_id' => $this->bankAccount->id,
        'value'           => $this->withdraw->value,
        'description'     => $this->withdraw->description,
        'date'            => $this->withdraw->date,
    ]);

    assertDatabaseHas('bank_accounts', [
        'id'      => $this->bankAccount->id,
        'balance' => $this->bankAccount->balance,
    ]);

    livewire(Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->set('withdraw.value', 200)
        ->set('withdraw.description', 'Test Update')
        ->set('withdraw.date', now()->format('Y-m-d'))
        ->call('save')
        ->assertEmitted('bank-account::withdraw::updated');

    assertDatabaseHas('bank_account_withdraws', [
        'bank_account_id' => $this->bankAccount->id,
        'value'           => 200,
        'description'     => 'Test Update',
        'date'            => now()->format('Y-m-d'),
    ]);

    assertDatabaseHas('bank_accounts', [
        'id'      => $this->bankAccount->id,
        'balance' => ($this->bankAccount->balance + $this->withdraw->value) - 200,
    ]);
});

test('value is required', function () {

    livewire(Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->set('withdraw.value', '')
        ->call('save')
        ->assertHasErrors(['withdraw.value' => 'required']);

});

test('value should be a number', function () {

    livewire(Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->set('withdraw.value', 'test')
        ->call('save')
        ->assertHasErrors(['withdraw.value' => 'numeric']);

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

test('value should be a less than 10 digits', function () {

    livewire(Withdrawals\Update::class, ['withdraw' => $this->withdraw])
        ->set('withdraw.value', 10000000000)
        ->call('save')
        ->assertHasErrors(['withdraw.value' => 'max_digits']);

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
