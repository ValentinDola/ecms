<?php

namespace Tests\Unit;

use App\Models\Citizen;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CitizenModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_name_is_generated_on_save(): void
    {
        $citizen = Citizen::factory()->create([
            'first_name' => 'Koffi',
            'last_name' => 'Mensah',
        ]);

        $this->assertSame('Koffi Mensah', $citizen->fresh()->full_name);
    }
}
