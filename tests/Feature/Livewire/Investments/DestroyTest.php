<?php

namespace Tests\Feature\Livewire\Investments;

use App\Http\Livewire\Investments;
use App\Models\Investment;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    $this->investment = Investment::factory()->create();

    actingAs($this->user);

});

it('should be able to force delete an investment if that doesnt have entries and withdrawals', function () {

    assertDatabaseHas('investments', ['id' => $this->investment->id]);

    livewire(Investments\Destroy::class, ['investment' => $this->investment])
        ->call('save')
        ->assertEmitted('investment::deleted');

    assertDatabaseMissing('investments', ['id' => $this->investment->id]);

});

it('should be able to disable an investment if that have entries', function () {

    $this->investment->entries()->create([
        'amount' => 100,
        'date' => now()->format('Y-m-d'),
    ]);

    assertDatabaseHas('investments', [
        'id' => $this->investment->id,
        'deleted_at' => null
    ]);

    livewire(Investments\Destroy::class, ['investment' => $this->investment])
        ->call('save')
        ->assertEmitted('investment::deleted');

    assertDatabaseHas('investments', [
        'id' => $this->investment->id,
        'deleted_at' => now()
    ]);

});

it('should be able to disable an investment if that have withdrawals', function () {
    $this->investment->withdrawals()->create([
        'amount' => 100,
        'date' => now()->format('Y-m-d'),
    ]);

    assertDatabaseHas('investments', [
        'id' => $this->investment->id,
        'deleted_at' => null
    ]);

    livewire(Investments\Destroy::class, ['investment' => $this->investment])
        ->call('save')
        ->assertEmitted('investment::deleted');

    assertDatabaseHas('investments', [
        'id' => $this->investment->id,
        'deleted_at' => now()
    ]);
});
