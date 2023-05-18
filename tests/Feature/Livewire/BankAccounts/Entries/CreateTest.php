<?php

namespace Tests\Feature\Livewire\BankAccounts\Entries;

use App\Http\Livewire\BankAccounts\Entries;
use App\Models\{BankAccount, BankAccountEntry, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->createOne([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo('bank_account_entry_create');

    $this->bankAccount = BankAccount::factory()->createOne([
        'user_id' => $this->user->id,
    ]);

    actingAs($this->user);

});

it("should be able to create a new bank account entry if are the account owner and has the 'bank_account_entry_create' permission", function () {
    // Arrange
    $newData = BankAccountEntry::factory()->makeOne();

    // Act
    livewire(Entries\Create::class, ['bankAccount' => $this->bankAccount])
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

    $newBalance = number_format($this->bankAccount->balance + $newData->value, 2, '.', '');

    expect($this->bankAccount->refresh())
        ->balance->toBe($newBalance);

});

it('should be not able to create a new bank account entry if are not the account owner', function () {
    // Arrange
    $user2 = User::factory()->createOne();

    $user2->givePermissionTo('bank_account_entry_create');

    // Act
    actingAs($user2);

    livewire(Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->call('save')
        ->assertForbidden();

});

it("should be not able to create a new bank account entry if not has the 'bank_account_entry_create' permission", function () {
    // Arrange
    $user2 = User::factory()->createOne();

    $user2->revokePermissionTo('bank_account_entry_create');

    // Act
    actingAs($user2);

    livewire(Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->call('save')
        ->assertForbidden();

});

test('value is required', function () {

    livewire(Entries\Create::class, ['bankAccount' => $this->bankAccount])
        ->set('entry.value', null)
        ->call('save')
        ->assertHasErrors(['entry.value' => 'required']);

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
