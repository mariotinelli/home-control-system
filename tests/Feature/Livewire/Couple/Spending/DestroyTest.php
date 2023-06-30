<?php

namespace Tests\Feature\Livewire\Couple\Spending;

use App\Http\Livewire\Couple\Spending\Destroy;
use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use Livewire\Livewire;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(Destroy::class);

        $component->assertStatus(200);
    }
}
