<?php

namespace Tests\Feature\Livewire\BankAccounts\Withdrawals;

use App\Http\Livewire\BankAccounts\Withdrawals;
use App\Models\{BankAccount, BankAccountWithdraw, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->createOne();

    $this->user->givePermissionTo(getUserPermissions());

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

it('should be to delete a withdraw', function () {

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

    livewire(Withdrawals\Destroy::class, ['withdraw' => $this->withdraw])
        ->call('save')
        ->assertEmitted('bank-account::withdraw::deleted');

    assertDatabaseMissing('bank_account_withdraws', [
        'bank_account_id' => $this->bankAccount->id,
        'value'           => $this->withdraw->value,
        'description'     => $this->withdraw->description,
        'date'            => $this->withdraw->date,
    ]);

    assertDatabaseHas('bank_accounts', [
        'id'      => $this->bankAccount->id,
        'balance' => $this->bankAccount->balance + $this->withdraw->value,
    ]);

});

it('should be to delete a withdraw only bank account owner', function () {

    actingAs(User::factory()->createOne());

    livewire(Withdrawals\Destroy::class, ['withdraw' => $this->withdraw])
        ->call('save')
        ->assertForbidden();

    actingAs($this->user);

    assertDatabaseHas('bank_account_withdraws', [
        'bank_account_id' => $this->bankAccount->id,
        'value'           => $this->withdraw->value,
        'description'     => $this->withdraw->description,
        'date'            => $this->withdraw->date,
    ]);

    livewire(Withdrawals\Destroy::class, ['withdraw' => $this->withdraw])
        ->call('save')
        ->assertEmitted('bank-account::withdraw::deleted');

    assertDatabaseMissing('bank_account_withdraws', [
        'bank_account_id' => $this->bankAccount->id,
        'value'           => $this->withdraw->value,
        'description'     => $this->withdraw->description,
        'date'            => $this->withdraw->date,
    ]);

    assertDatabaseHas('bank_accounts', [
        'id'      => $this->bankAccount->id,
        'balance' => $this->bankAccount->balance + $this->withdraw->value,
    ]);

});
