<?php

namespace Tests\Feature;

use App\Models\AssistanceCase;
use App\Models\Citizen;
use Tests\TestCase;

class AssistanceCaseManagementTest extends TestCase
{
    public function test_assistance_index_page_loads(): void
    {
        AssistanceCase::factory()->create(['case_number' => 'CA-2026-00099']);

        $response = $this->get(route('assistance.index'));

        $response->assertOk();
        $response->assertSee('CA-2026-00099');
    }

    public function test_assistance_case_is_created_with_auto_generated_case_number(): void
    {
        $year = now()->format('Y');
        $citizen = Citizen::factory()->create();

        $response = $this->post(route('assistance.store'), [
            'citizen_id' => $citizen->id,
            'case_type' => 'lost_passport',
            'status' => 'open',
            'opened_at' => now()->toDateString(),
            'description' => 'Passport lost at airport.',
        ]);

        $case = AssistanceCase::first();

        $response->assertRedirect(route('assistance.show', $case));
        $this->assertSame("CA-{$year}-00001", $case->case_number);
        $this->assertSame($citizen->id, $case->citizen_id);
    }

    public function test_assistance_case_number_increments(): void
    {
        $year = now()->format('Y');
        $citizen = Citizen::factory()->create();

        AssistanceCase::factory()->create([
            'citizen_id' => $citizen->id,
            'case_number' => "CA-{$year}-00003",
        ]);

        $response = $this->post(route('assistance.store'), [
            'citizen_id' => $citizen->id,
            'case_type' => 'medical',
            'status' => 'open',
            'opened_at' => now()->toDateString(),
            'description' => 'Medical emergency.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('assistance_cases', ['case_number' => "CA-{$year}-00004"]);
    }

    public function test_closing_case_sets_closed_at_on_update(): void
    {
        $case = AssistanceCase::factory()->create(['status' => 'open', 'closed_at' => null]);

        $response = $this->put(route('assistance.update', $case), [
            'citizen_id' => $case->citizen_id,
            'case_type' => $case->case_type,
            'status' => 'closed',
            'opened_at' => $case->opened_at->format('Y-m-d'),
            'description' => $case->description,
        ]);

        $response->assertRedirect(route('assistance.show', $case));
        $this->assertNotNull($case->fresh()->closed_at);
    }

    public function test_assistance_case_can_be_deleted(): void
    {
        $case = AssistanceCase::factory()->create();

        $response = $this->delete(route('assistance.destroy', $case));

        $response->assertRedirect(route('assistance.index'));
        $this->assertDatabaseMissing('assistance_cases', ['id' => $case->id]);
    }
}
