<?php

namespace Tests\Feature\Livewire\BankAccounts\Entries;

use App\Models\{BankAccount, BankAccountEntry, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->createOne([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo('bank_account_entry_create');

    $this->user->bankAccounts()->save(
        $this->bankAccount = BankAccount::factory()->makeOne()
    );

    actingAs($this->user);

});

it("should be able to create a new entry", function () {
    // Arrange
    $newData = BankAccountEntry::factory()->makeOne();

    // Act
    livewire(\App\Http\Livewire\Banks\Accounts\Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('entry.value', $newData->value)
        ->set('entry.description', $newData->description)
        ->set('entry.date', $newData->date)
        ->call('save')
        ->assertHasNoErrors()
        ->assertEmitted('bank-account::entry::created');

    // Assert
    assertDatabaseHas('bank_account_entries', [
        'bank_account_id' => $this->bankAccount->id,
        'value'           => $newData->value,
        'description'     => $newData->description,
        'date'            => $newData->date,
    ]);

    assertDatabaseHas('bank_accounts', [
        'id'      => $this->bankAccount->id,
        'balance' => $this->bankAccount->balance + $newData->value,
    ]);

});

it('should be not able to create a new bank account entry if are not the account owner', function () {
    // Arrange
    $bankAccount2 = BankAccount::factory()->createOne();

    // Act
    livewire(\App\Http\Livewire\Banks\Accounts\Entries\Create::class, ['bankAccount' => $bankAccount2])
        ->call('save')
        ->assertForbidden();

});

it("should be not able to create a new bank account entry if not has permission to this", function () {
    // Arrange
    $this->user->revokePermissionTo('bank_account_entry_create');

    // Act
    livewire(\App\Http\Livewire\Banks\Accounts\Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->call('save')
        ->assertForbidden();

});

it("should be not able to create a new bank account entry if not authenticated", function () {
    // Arrange
    \Auth::logout();

    // Act
    livewire(\App\Http\Livewire\Banks\Accounts\Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->call('save')
        ->assertForbidden();

});

test('value is required', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('entry.value', null)
        ->call('save')
        ->assertHasErrors(['entry.value' => 'required']);

});

test('value should be a numeric', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('entry.value', 'test')
        ->call('save')
        ->assertHasErrors(['entry.value' => 'numeric']);

});

test('value should be a greater than zero', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('entry.value', -100)
        ->call('save')
        ->assertHasErrors(['entry.value' => 'min']);

    livewire(\App\Http\Livewire\Banks\Accounts\Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('entry.value', 1)
        ->call('save')
        ->assertHasNoErrors(['entry.value' => 'min']);

});

test('description is required', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('entry.description', '')
        ->call('save')
        ->assertHasErrors(['entry.description' => 'required']);

});

test('description should be a string', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('entry.description', 100)
        ->call('save')
        ->assertHasErrors(['entry.description' => 'string']);

});

test('description should be a less than 255 characters', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('entry.description', str_repeat('a', 256))
        ->call('save')
        ->assertHasErrors(['entry.description' => 'max']);

});

test('date is required', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('entry.date', '')
        ->call('save')
        ->assertHasErrors(['entry.date' => 'required']);

});

test('date should be a date', function () {

    livewire(\App\Http\Livewire\Banks\Accounts\Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('entry.date', 'test')
        ->call('save')
        ->assertHasErrors(['entry.date' => 'date']);

});
