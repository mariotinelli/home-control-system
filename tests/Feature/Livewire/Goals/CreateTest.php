<?php

namespace Tests\Feature\Livewire\Goals;

use App\Http\Livewire\Goals;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    actingAs($this->user);

});

it('should be able to create a new goal', function () {

    $lw = livewire(Goals\Create::class)
        ->set('goal.name', 'Test Goal')
        ->set('goal.to_reach', 100)
        ->set('goal.owner', 'Mario')
        ->set('goal.description', 'Test Description')
        ->call('save');

    $lw->assertHasNoErrors()
        ->assertEmitted('goal::created');


    assertDatabaseHas('goals', [
        'name' => 'Test Goal',
        'to_reach' => 100,
        'owner' => 'Mario',
        'description' => 'Test Description',
    ]);

});

test('name is required', function () {

    livewire(Goals\Create::class)
        ->set('goal.name', '')
        ->call('save')
        ->assertHasErrors(['goal.name' => 'required']);

});

test('name should be a string', function () {

    livewire(Goals\Create::class)
        ->set('goal.name', 123)
        ->call('save')
        ->assertHasErrors(['goal.name' => 'string']);

});

test('name should be max 255 characters', function () {

    livewire(Goals\Create::class)
        ->set('goal.name', str_repeat('a', 256))
        ->call('save')
        ->assertHasErrors(['goal.name' => 'max']);

});

test('to_reach is required', function () {

    livewire(Goals\Create::class)
        ->set('goal.to_reach', '')
        ->call('save')
        ->assertHasErrors(['goal.to_reach' => 'required']);

});

test('to_reach should be a number', function () {

    livewire(Goals\Create::class)
        ->set('goal.to_reach', 'abc')
        ->call('save')
        ->assertHasErrors(['goal.to_reach' => 'numeric']);

});

test('to_reach should be at least 1', function () {

    livewire(Goals\Create::class)
        ->set('goal.to_reach', 0)
        ->call('save')
        ->assertHasErrors(['goal.to_reach' => 'min']);

});

test('owner is required', function () {

    livewire(Goals\Create::class)
        ->set('goal.owner', '')
        ->call('save')
        ->assertHasErrors(['goal.owner' => 'required']);

});

test('owner should be a string', function () {

    livewire(Goals\Create::class)
        ->set('goal.owner', 123)
        ->call('save')
        ->assertHasErrors(['goal.owner' => 'string']);

});

test('owner should be max 255 characters', function () {

    livewire(Goals\Create::class)
        ->set('goal.owner', str_repeat('a', 256))
        ->call('save')
        ->assertHasErrors(['goal.owner' => 'max']);

});

test('description is required', function () {

    livewire(Goals\Create::class)
        ->set('goal.description', '')
        ->call('save')
        ->assertHasErrors(['goal.description' => 'required']);

});

test('description should be a string', function () {

    livewire(Goals\Create::class)
        ->set('goal.description', 123)
        ->call('save')
        ->assertHasErrors(['goal.description' => 'string']);

});

test('description should be max 255 characters', function () {

    livewire(Goals\Create::class)
        ->set('goal.description', str_repeat('a', 256))
        ->call('save')
        ->assertHasErrors(['goal.description' => 'max']);

});




