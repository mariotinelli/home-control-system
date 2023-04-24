<?php

namespace Tests\Feature\Livewire\BankAccounts\Entries;

use App\Http\Livewire\BankAccounts\Entries;
use App\Models\{BankAccount, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->createOne();

    $this->bankAccount = BankAccount::factory()->createOne([
        'user_id' => $this->user->id,
    ]);

    actingAs($this->user);

});

it('should be create a new entry', function () {

    livewire(Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('entry.value', 100)
        ->set('entry.description', 'Test')
        ->set('entry.date', now()->format('Y-m-d'))
        ->call('save')
        ->assertEmitted('bank-account::entry::created');

    assertDatabaseHas('bank_account_entries', [
        'bank_account_id' => $this->bankAccount->id,
        'value'           => 100,
        'description'     => 'Test',
        'date'            => now()->format('Y-m-d'),
    ]);

    assertDatabaseHas('bank_accounts', [
        'id'      => $this->bankAccount->id,
        'balance' => $this->bankAccount->balance + 100,
    ]);

});

it('should be create a new entry in bank account only bank account owner', function () {

    actingAs(User::factory()->createOne());

    livewire(Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->call('save')
        ->assertForbidden();

    actingAs($this->user);

    livewire(Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('entry.value', 100)
        ->set('entry.description', 'Test')
        ->set('entry.date', now()->format('Y-m-d'))
        ->call('save')
        ->assertEmitted('bank-account::entry::created');

    assertDatabaseHas('bank_account_entries', [
        'bank_account_id' => $this->bankAccount->id,
        'value'           => 100,
        'description'     => 'Test',
        'date'            => now()->format('Y-m-d'),
    ]);

    assertDatabaseHas('bank_accounts', [
        'id'      => $this->bankAccount->id,
        'balance' => $this->bankAccount->balance + 100,
    ]);

});

test('value is required', function () {

    livewire(Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('entry.value', '')
        ->call('save')
        ->assertHasErrors(['entry.value' => 'required']);

});

test('value should be a number', function () {

    livewire(Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('entry.value', 'test')
        ->call('save')
        ->assertHasErrors(['entry.value' => 'numeric']);

});

test('value should be a greater than zero', function () {

    livewire(Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('entry.value', -100)
        ->call('save')
        ->assertHasErrors(['entry.value' => 'min']);

    livewire(Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('entry.value', 1)
        ->call('save')
        ->assertHasNoErrors(['entry.value' => 'min']);

});

test('value should be a less than 10 digits', function () {

    livewire(Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('entry.value', 10000000000)
        ->call('save')
        ->assertHasErrors(['entry.value' => 'max_digits']);

});

test('description is required', function () {

    livewire(Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('entry.description', '')
        ->call('save')
        ->assertHasErrors(['entry.description' => 'required']);

});

test('description should be a string', function () {

    livewire(Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('entry.description', 100)
        ->call('save')
        ->assertHasErrors(['entry.description' => 'string']);

});

test('description should be a less than 255 characters', function () {

    livewire(Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('entry.description', str_repeat('a', 256))
        ->call('save')
        ->assertHasErrors(['entry.description' => 'max']);

});

test('date is required', function () {

    livewire(Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('entry.date', '')
        ->call('save')
        ->assertHasErrors(['entry.date' => 'required']);

});

test('date should be a date', function () {

    livewire(Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('entry.date', 'test')
        ->call('save')
        ->assertHasErrors(['entry.date' => 'date']);

});
