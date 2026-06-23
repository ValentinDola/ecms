<?php

namespace Tests\Feature;

use App\Models\Citizen;
use App\Models\Visa;
use Tests\TestCase;

class VisaManagementTest extends TestCase
{
    public function test_visa_index_page_loads(): void
    {
        Visa::factory()->create(['visa_number' => 'V-2026-12345']);

        $response = $this->get(route('visas.index'));

        $response->assertOk();
        $response->assertSee('V-2026-12345');
    }

    public function test_visa_can_be_created_with_linked_citizen(): void
    {
        $citizen = Citizen::factory()->create([
            'first_name' => 'Linked',
            'last_name' => 'Applicant',
            'passport_number' => 'TG3333333',
        ]);

        $response = $this->post(route('visas.store'), [
            'citizen_id' => $citizen->id,
            'visa_number' => 'V-2026-54321',
            'passport_number' => 'TG3333333',
            'applicant_first_name' => 'Linked',
            'applicant_last_name' => 'Applicant',
            'visa_type' => 'business',
            'issue_date' => '2026-01-01',
            'expiry_date' => '2026-06-01',
            'status' => 'approved',
        ]);

        $visa = Visa::where('visa_number', 'V-2026-54321')->first();

        $response->assertRedirect(route('visas.show', $visa));
        $this->assertDatabaseHas('visas', [
            'visa_number' => 'V-2026-54321',
            'citizen_id' => $citizen->id,
        ]);
    }

    public function test_visa_can_be_created_without_linked_citizen(): void
    {
        $response = $this->post(route('visas.store'), [
            'citizen_id' => '',
            'visa_number' => 'V-2026-99901',
            'passport_number' => 'GH8888888',
            'applicant_first_name' => 'Jean',
            'applicant_last_name' => 'Dupont',
            'visa_type' => 'tourist',
            'issue_date' => '2026-02-01',
            'expiry_date' => '2026-05-01',
            'status' => 'pending',
        ]);

        $visa = Visa::where('visa_number', 'V-2026-99901')->first();

        $response->assertRedirect(route('visas.show', $visa));
        $this->assertNull($visa->citizen_id);
    }

    public function test_citizen_lookup_returns_matching_citizens(): void
    {
        Citizen::factory()->create([
            'full_name' => 'Lookup Target',
            'passport_number' => 'TGLOOKUP1',
        ]);

        $response = $this->getJson(route('visas.citizens.lookup', ['q' => 'Lookup']));

        $response->assertOk();
        $response->assertJsonFragment(['passport_number' => 'TGLOOKUP1']);
    }

    public function test_visa_can_be_deleted(): void
    {
        $visa = Visa::factory()->create();

        $response = $this->delete(route('visas.destroy', $visa));

        $response->assertRedirect(route('visas.index'));
        $this->assertDatabaseMissing('visas', ['id' => $visa->id]);
    }
}
