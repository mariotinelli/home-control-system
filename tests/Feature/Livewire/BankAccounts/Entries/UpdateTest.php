<?php

namespace Tests\Feature\Livewire\BankAccounts\Entries;

use App\Http\Livewire\BankAccounts\Entries;
use App\Models\BankAccount;
use App\Models\BankAccountEntry;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->createOne();

    $this->bankAccount = BankAccount::factory()->createOne([
        'user_id' => $this->user->id,
    ]);

    $this->entry = BankAccountEntry::factory()->createOne([
        'bank_account_id' => $this->bankAccount->id,
    ]);

    $this->bankAccount->update([
        'balance' => $this->bankAccount->balance + $this->entry->value,
    ]);

    actingAs($this->user);

});

it('should be to update a entry', function () {

    assertDatabaseHas('bank_account_entries', [
        'bank_account_id' => $this->bankAccount->id,
        'value' => $this->entry->value,
        'description' => $this->entry->description,
        'date' => $this->entry->date->format('Y-m-d'),
    ]);

    assertDatabaseHas('bank_accounts', [
        'id' => $this->bankAccount->id,
        'balance' => $this->bankAccount->balance,
    ]);

    livewire(Entries\Update::class, ['entry' => $this->entry])
        ->set('entry.value', 200)
        ->set('entry.description', 'Test Update')
        ->set('entry.date', now()->format('Y-m-d'))
        ->call('save')
        ->assertEmitted('bank-account::entry::updated');

    assertDatabaseHas('bank_account_entries', [
        'bank_account_id' => $this->bankAccount->id,
        'value' => 200,
        'description' => 'Test Update',
        'date' => now()->format('Y-m-d'),
    ]);

    assertDatabaseHas('bank_accounts', [
        'id' => $this->bankAccount->id,
        'balance' => ($this->bankAccount->balance - $this->entry->value) + 200,
    ]);

});

it('should be to update a entry only bank account owner', function () {

    actingAs(User::factory()->createOne());

    livewire(Entries\Update::class, ['entry' => $this->entry])
        ->call('save')
        ->assertForbidden();

    actingAs($this->user);

    assertDatabaseHas('bank_account_entries', [
        'bank_account_id' => $this->bankAccount->id,
        'value' => $this->entry->value,
        'description' => $this->entry->description,
        'date' => $this->entry->date->format('Y-m-d'),
    ]);

    assertDatabaseHas('bank_accounts', [
        'id' => $this->bankAccount->id,
        'balance' => $this->bankAccount->balance,
    ]);

    livewire(Entries\Update::class, ['entry' => $this->entry])
        ->set('entry.value', 200)
        ->set('entry.description', 'Test Update')
        ->set('entry.date', now()->format('Y-m-d'))
        ->call('save')
        ->assertEmitted('bank-account::entry::updated');

    assertDatabaseHas('bank_account_entries', [
        'bank_account_id' => $this->bankAccount->id,
        'value' => 200,
        'description' => 'Test Update',
        'date' => now()->format('Y-m-d'),
    ]);

    assertDatabaseHas('bank_accounts', [
        'id' => $this->bankAccount->id,
        'balance' => ($this->bankAccount->balance - $this->entry->value) + 200,
    ]);

});

test('value is required', function () {

    livewire(Entries\Update::class, ['entry' => $this->entry])
        ->set('entry.value', '')
        ->call('save')
        ->assertHasErrors(['entry.value' => 'required']);

});

test('value should be a number', function () {

    livewire(Entries\Update::class, ['entry' => $this->entry])
        ->set('entry.value', 'test')
        ->call('save')
        ->assertHasErrors(['entry.value' => 'numeric']);

});

test('value should be a greater than zero', function () {

    livewire(Entries\Update::class, ['entry' => $this->entry])
        ->set('entry.value', -100)
        ->call('save')
        ->assertHasErrors(['entry.value' => 'min']);

    livewire(Entries\Update::class, ['entry' => $this->entry])
        ->set('entry.value', 1)
        ->call('save')
        ->assertHasNoErrors(['entry.value' => 'min']);

});

test('value should be a less than 10 digits', function () {

    livewire(Entries\Update::class, ['entry' => $this->entry])
        ->set('entry.value', 10000000000)
        ->call('save')
        ->assertHasErrors(['entry.value' => 'max_digits']);

});

test('description is required', function () {

    livewire(Entries\Update::class, ['entry' => $this->entry])
        ->set('entry.description', '')
        ->call('save')
        ->assertHasErrors(['entry.description' => 'required']);

});

test('description should be a string', function () {

    livewire(Entries\Update::class, ['entry' => $this->entry])
        ->set('entry.description', 100)
        ->call('save')
        ->assertHasErrors(['entry.description' => 'string']);

});

test('description should be a less than 255 characters', function () {

    livewire(Entries\Update::class, ['entry' => $this->entry])
        ->set('entry.description', str_repeat('a', 256))
        ->call('save')
        ->assertHasErrors(['entry.description' => 'max']);

});

test('date is required', function () {

    livewire(Entries\Update::class, ['entry' => $this->entry])
        ->set('entry.date', '')
        ->call('save')
        ->assertHasErrors(['entry.date' => 'required']);

});

test('date should be a date', function () {

    livewire(Entries\Update::class, ['entry' => $this->entry])
        ->set('entry.date', 'test')
        ->call('save')
        ->assertHasErrors(['entry.date' => 'date']);

});
