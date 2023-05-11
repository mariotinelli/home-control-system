<?php

namespace Tests\Feature\Livewire\BankAccounts\Withdrawals;

use App\Http\Livewire\BankAccounts\Withdrawals;
use App\Models\{BankAccount, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->createOne();

    $this->user->givePermissionTo(getUserPermissions());

    $this->bankAccount = BankAccount::factory()->createOne([
        'user_id' => $this->user->id,
    ]);

    actingAs($this->user);

});

test('should be able to create a withdraw', function () {

    livewire(Withdrawals\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('withdraw.value', 100)
        ->set('withdraw.description', 'Test')
        ->set('withdraw.date', '2021-01-01')
        ->call('save')
        ->assertEmitted('bank-account::withdraw::created');

    assertDatabaseHas('bank_account_withdraws', [
        'bank_account_id' => $this->bankAccount->id,
        'value'           => 100,
        'description'     => 'Test',
        'date'            => '2021-01-01',
    ]);

    assertDatabaseHas('bank_accounts', [
        'id'      => $this->bankAccount->id,
        'balance' => $this->bankAccount->balance - 100,
    ]);

});

it('should be create a new withdraw in bank account only bank account owner', function () {

    actingAs(User::factory()->createOne());

    livewire(Withdrawals\Create::class, ['bankAccount' => $this->bankAccount])
        ->call('save')
        ->assertForbidden();

    actingAs($this->user);

    livewire(Withdrawals\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('withdraw.value', 100)
        ->set('withdraw.description', 'Test')
        ->set('withdraw.date', now()->format('Y-m-d'))
        ->call('save')
        ->assertEmitted('bank-account::withdraw::created');

    assertDatabaseHas('bank_account_withdraws', [
        'bank_account_id' => $this->bankAccount->id,
        'value'           => 100,
        'description'     => 'Test',
        'date'            => now()->format('Y-m-d'),
    ]);

    assertDatabaseHas('bank_accounts', [
        'id'      => $this->bankAccount->id,
        'balance' => $this->bankAccount->balance - 100,
    ]);

});

test('value is required', function () {

    livewire(Withdrawals\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('withdraw.value', '')
        ->call('save')
        ->assertHasErrors(['withdraw.value' => 'required']);

});

test('value should be a number', function () {

    livewire(Withdrawals\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('withdraw.value', 'test')
        ->call('save')
        ->assertHasErrors(['withdraw.value' => 'numeric']);

});

test('value should be a greater than zero', function () {

    livewire(Withdrawals\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('withdraw.value', -100)
        ->call('save')
        ->assertHasErrors(['withdraw.value' => 'min']);

    livewire(Withdrawals\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('withdraw.value', 1)
        ->call('save')
        ->assertHasNoErrors(['withdraw.value' => 'min']);

});

test('value should be a less than 10 digits', function () {

    livewire(Withdrawals\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('withdraw.value', 10000000000)
        ->call('save')
        ->assertHasErrors(['withdraw.value' => 'max_digits']);

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
