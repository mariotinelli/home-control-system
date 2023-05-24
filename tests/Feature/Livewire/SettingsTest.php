<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Settings;
use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use Livewire\Livewire;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(Settings::class);

        $component->assertStatus(200);
    }
}
