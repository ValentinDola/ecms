<?php

namespace Tests\Feature;

use App\Models\Citizen;
use Tests\TestCase;

class CitizenManagementTest extends TestCase
{
    public function test_citizen_index_page_loads(): void
    {
        Citizen::factory()->create([
            'first_name' => 'Koffi',
            'last_name' => 'Mensah',
        ]);

        $response = $this->get(route('citizens.index'));

        $response->assertOk();
        $response->assertSee('Koffi Mensah');
    }

    public function test_citizen_can_be_created(): void
    {
        $response = $this->post(route('citizens.store'), [
            'first_name' => 'Ama',
            'last_name' => 'Agbeko',
            'nationality' => 'Togolese',
            'passport_number' => 'TG1111111',
            'phone' => '+233 24 000 0000',
            'registration_date' => '2026-01-15',
        ]);

        $citizen = Citizen::where('passport_number', 'TG1111111')->first();

        $response->assertRedirect(route('citizens.show', $citizen));
        $this->assertDatabaseHas('citizens', [
            'passport_number' => 'TG1111111',
            'full_name' => 'Ama Agbeko',
        ]);
    }

    public function test_citizen_creation_requires_unique_passport_number(): void
    {
        Citizen::factory()->create(['passport_number' => 'TG2222222']);

        $response = $this->post(route('citizens.store'), [
            'first_name' => 'Duplicate',
            'last_name' => 'Citizen',
            'nationality' => 'Togolese',
            'passport_number' => 'TG2222222',
            'registration_date' => '2026-01-15',
        ]);

        $response->assertSessionHasErrors('passport_number');
    }

    public function test_citizen_can_be_updated(): void
    {
        $citizen = Citizen::factory()->create([
            'first_name' => 'Old',
            'last_name' => 'Name',
        ]);

        $response = $this->put(route('citizens.update', $citizen), [
            'first_name' => 'New',
            'last_name' => 'Name',
            'nationality' => 'Togolese',
            'passport_number' => $citizen->passport_number,
            'registration_date' => $citizen->registration_date->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('citizens.show', $citizen));
        $this->assertSame('New Name', $citizen->fresh()->full_name);
    }

    public function test_citizen_can_be_deleted(): void
    {
        $citizen = Citizen::factory()->create();

        $response = $this->delete(route('citizens.destroy', $citizen));

        $response->assertRedirect(route('citizens.index'));
        $this->assertDatabaseMissing('citizens', ['id' => $citizen->id]);
    }

    public function test_citizen_index_can_be_filtered(): void
    {
        Citizen::factory()->create([
            'first_name' => 'Find',
            'last_name' => 'Me',
            'passport_number' => 'TGFINDME',
        ]);
        Citizen::factory()->create([
            'first_name' => 'Someone',
            'last_name' => 'Else',
        ]);

        $response = $this->get(route('citizens.index', ['q' => 'FINDME']));

        $response->assertOk();
        $response->assertSee('Find Me');
        $response->assertDontSee('Someone Else');
    }
}
