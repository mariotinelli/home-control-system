<?php

namespace Tests\Feature\Livewire\BankAccounts\Entries;

use App\Http\Livewire\BankAccounts\Entries;
use App\Models\{BankAccount, BankAccountEntry, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->createOne();

    $this->user->givePermissionTo(getUserPermissions());

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

it('should be to delete a entry', function () {

    assertDatabaseHas('bank_account_entries', [
        'bank_account_id' => $this->bankAccount->id,
        'value'           => $this->entry->value,
        'description'     => $this->entry->description,
        'date'            => $this->entry->date->format('Y-m-d'),
    ]);

    assertDatabaseHas('bank_accounts', [
        'id'      => $this->bankAccount->id,
        'balance' => $this->bankAccount->balance,
    ]);

    livewire(Entries\Destroy::class, ['entry' => $this->entry])
        ->call('save')
        ->assertEmitted('bank-account::entry::deleted');

    assertDatabaseMissing('bank_account_entries', [
        'bank_account_id' => $this->bankAccount->id,
        'value'           => $this->entry->value,
        'description'     => $this->entry->description,
        'date'            => $this->entry->date->format('Y-m-d'),
    ]);

    assertDatabaseHas('bank_accounts', [
        'id'      => $this->bankAccount->id,
        'balance' => $this->bankAccount->balance - $this->entry->value,
    ]);

});

it('should be to delete a entry only bank account owner', function () {

    actingAs(User::factory()->createOne());

    livewire(Entries\Destroy::class, ['entry' => $this->entry])
        ->call('save')
        ->assertForbidden();

    actingAs($this->user);

    assertDatabaseHas('bank_account_entries', [
        'bank_account_id' => $this->bankAccount->id,
        'value'           => $this->entry->value,
        'description'     => $this->entry->description,
        'date'            => $this->entry->date->format('Y-m-d'),
    ]);

    assertDatabaseHas('bank_accounts', [
        'id'      => $this->bankAccount->id,
        'balance' => $this->bankAccount->balance,
    ]);

    livewire(Entries\Destroy::class, ['entry' => $this->entry])
        ->call('save')
        ->assertEmitted('bank-account::entry::deleted');

    assertDatabaseMissing('bank_account_entries', [
        'bank_account_id' => $this->bankAccount->id,
        'value'           => $this->entry->value,
        'description'     => $this->entry->description,
        'date'            => $this->entry->date->format('Y-m-d'),
    ]);

    assertDatabaseHas('bank_accounts', [
        'id'      => $this->bankAccount->id,
        'balance' => $this->bankAccount->balance - $this->entry->value,
    ]);

});
