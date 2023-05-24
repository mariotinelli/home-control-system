<?php

namespace Tests\Feature\Livewire\Goals;

use App\Http\Livewire\Goals;
use App\Models\{Goal, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo(getUserPermissions());

    $this->user->goals()->save(
        $this->goal = Goal::factory()->create()
    );

    actingAs($this->user);

});

it('should be able to delete a goal', function () {

    $lw = livewire(Goals\Destroy::class, ['goal' => $this->goal])
        ->call('save');

    $lw->assertHasNoErrors()
        ->assertEmitted('goal::deleted');

    $this->assertDatabaseMissing('goals', [
        'id'      => $this->goal->id,
        'user_id' => $this->user->id,
    ]);

});

it('should be able to disable a goals if that has entries', function () {

    assertDatabaseHas('goals', [
        'id'         => $this->goal->id,
        'deleted_at' => null,
    ]);

    $this->goal->entries()->create([
        'amount' => 100,
        'date'   => now(),
    ]);

    $lw = livewire(Goals\Destroy::class, ['goal' => $this->goal])
        ->call('save');

    $lw->assertHasNoErrors()
        ->assertEmitted('goal::deleted');

    expect($this->goal->refresh())
        ->deleted_at->not()->toBeNull;

});

it('should be able to disable a goals if that has withdrawals', function () {

    assertDatabaseHas('goals', [
        'id'         => $this->goal->id,
        'deleted_at' => null,
    ]);

    $this->goal->withdrawals()->create([
        'amount' => 100,
        'date'   => now(),
    ]);

    $lw = livewire(Goals\Destroy::class, ['goal' => $this->goal])
        ->call('save');

    $lw->assertHasNoErrors()
        ->assertEmitted('goal::deleted');

    expect($this->goal->refresh())
        ->deleted_at->not()->toBeNull;

});
