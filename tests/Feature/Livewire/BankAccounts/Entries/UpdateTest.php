<?php

namespace Tests\Feature\Livewire\BankAccounts\Entries;

use App\Http\Livewire\BankAccounts\Entries;
use App\Models\{BankAccount, BankAccountEntry, User};

use function Pest\Laravel\{actingAs};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->createOne([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo('bank_account_entry_update');

    $this->bankAccount = BankAccount::factory()->createOne([
        'user_id' => $this->user->id,
    ]);

    $this->bankAccount->entries()->save(
        $this->entry = BankAccountEntry::factory()->makeOne()
    );

    $this->bankAccount->update([
        'balance' => $this->bankAccount->balance + $this->entry->value,
    ]);

    actingAs($this->user);

});

it("should be able to update a entry if are the account owner and has the 'bank_account_entry_update' permission", function () {
    // Arrange
    $newData = BankAccountEntry::factory()->makeOne();

    // Act
    livewire(Entries\Update::class, ['entry' => $this->entry])
        ->set('entry.value', $newData->value)
        ->set('entry.description', $newData->description)
        ->set('entry.date', $newData->date)
        ->call('save')
        ->assertHasNoErrors()
        ->assertEmitted('bank-account::entry::updated');

    // Assert
    $oldEntryValue = $this->entry->getOriginal('value');

    $oldBalance = $this->bankAccount->balance;

    expect($this->entry->refresh()->value)
        ->toBe($newData->value)
        ->and($this->entry->refresh())
            ->description->toBe($newData->description)
            ->date->toBe($newData->date);

    $newBalance = number_format(($oldBalance - $oldEntryValue) + $newData->value, 2, '.', '');

    expect($this->bankAccount->refresh())
        ->balance->toBe($newBalance);

});

it('should be not able to update a entry if are not the account owner', function () {
    // Arrange
    $user2 = User::factory()->createOne();

    $user2->givePermissionTo('bank_account_entry_update');

    // Act
    actingAs($user2);

    livewire(Entries\Update::class, ['entry' => $this->entry])
        ->call('save')
        ->assertForbidden();

});

it("should be not able to update a entry if not has the 'bank_account_entry_update' permission", function () {
    // Arrange
    $user2 = User::factory()->createOne();

    // Act
    actingAs($user2);

    livewire(Entries\Update::class, ['entry' => $this->entry])
        ->call('save')
        ->assertForbidden();

});

test('value is required', function () {

    livewire(Entries\Update::class, ['entry' => $this->entry])
        ->set('entry.value', null)
        ->call('save')
        ->assertHasErrors(['entry.value' => 'required']);

});

test('value should be a greater than zero', function () {

    livewire(Entries\Update::class, ['entry' => $this->entry])
        ->set('entry.value', -100)
        ->call('save')
        ->assertHasErrors(['entry.value' => 'min']);

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
