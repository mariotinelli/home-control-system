<?php

namespace Tests\Feature\Livewire\Goals;

use App\Http\Livewire\Goals\Index;
use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(Index::class);

        $component->assertStatus(200);
    }
}
