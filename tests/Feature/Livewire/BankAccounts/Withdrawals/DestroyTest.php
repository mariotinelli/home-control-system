<?php

namespace Tests\Feature\Livewire\BankAccounts\Withdrawals;

use App\Http\Livewire\BankAccounts\Withdrawals;
use App\Models\{BankAccount, BankAccountWithdraw, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->createOne([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo('bank_account_withdraw_delete');

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

it('should be able to delete a withdraw', function () {

    // Act
    livewire(Withdrawals\Destroy::class, ['withdraw' => $this->withdraw])
        ->call('save')
        ->assertEmitted('bank-account::withdraw::deleted');

    // Assert
    assertDatabaseMissing('bank_account_withdraws', [
        'id' => $this->withdraw->id,
    ]);

    assertDatabaseHas('bank_accounts', [
        'id'      => $this->bankAccount->id,
        'balance' => $this->bankAccount->balance + $this->withdraw->value,
    ]);

});

it('should be not able to delete a withdraw if is not account owner', function () {
    // Arrange
    $bankAccount2 = BankAccount::factory()->createOne();

    $bankAccount2->withdrawals()->save(
        $withdraw2 = BankAccountWithdraw::factory()->makeOne()
    );

    // Act
    livewire(Withdrawals\Destroy::class, ['withdraw' => $withdraw2])
        ->call('save')
        ->assertForbidden();

});

it('should be not able to delete a withdraw if is not authenticated', function () {
    // Arrange
    \Auth::logout();

    // Act
    livewire(Withdrawals\Destroy::class, ['withdraw' => $this->withdraw])
        ->call('save')
        ->assertForbidden();

});

it('should be not able to delete a withdraw if is not has permission to this', function () {
    // Arrange
    $this->user->revokePermissionTo('bank_account_withdraw_delete');

    // Act
    livewire(Withdrawals\Destroy::class, ['withdraw' => $this->withdraw])
        ->call('save')
        ->assertForbidden();

});
