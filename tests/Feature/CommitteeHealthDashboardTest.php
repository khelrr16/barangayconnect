<?php

namespace Tests\Feature;

use App\Models\Committee;
use App\Models\Health\Immunization;
use App\Models\Health\Infant;
use App\Models\Health\Medicine;
use App\Models\Health\NutritionalAssessment;
use App\Models\Official;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommitteeHealthDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_health_committee_head_can_access_health_dashboard(): void
    {
        $user = $this->createCommitteeHead('health_sanitation', 'Health Sanitation');

        $response = $this->actingAs($user)->get(route('committee.health.dashboard'));

        $response->assertOk();
        $response->assertSee('Health Dashboard');
    }

    public function test_non_health_committee_head_cannot_access_health_dashboard(): void
    {
        $user = $this->createCommitteeHead('education', 'Education');

        $response = $this->actingAs($user)->get(route('committee.health.dashboard'));

        $response->assertForbidden();
    }

    public function test_committee_dashboard_landing_redirects_health_head_to_health_dashboard(): void
    {
        $user = $this->createCommitteeHead('health_sanitation', 'Health Sanitation');

        $response = $this->actingAs($user)->get(route('committee.dashboard'));

        $response->assertRedirect(route('committee.health.dashboard'));
    }

    public function test_health_dashboard_print_and_pdf_endpoints_are_accessible(): void
    {
        $user = $this->createCommitteeHead('health_sanitation', 'Health Sanitation');

        $printResponse = $this->actingAs($user)->get(route('committee.health.dashboard.print', [
            'mode' => 'month',
            'year' => now()->year,
            'month' => now()->month,
        ]));

        $printResponse->assertOk();
        $printResponse->assertSee('Health Dashboard Report');

        $pdfResponse = $this->actingAs($user)->get(route('committee.health.dashboard.pdf', [
            'mode' => 'year',
            'year' => now()->year,
        ]));

        $pdfResponse->assertOk();
        $pdfResponse->assertHeader('content-type', 'application/pdf');
    }

    public function test_monthly_report_shows_medicine_and_status_data(): void
    {
        $user = $this->createCommitteeHead('health_sanitation', 'Health Sanitation');

        $infant = Infant::create([
            'birthday' => now()->subMonths(8)->toDateString(),
            'family_serial_number' => 'FSN-TEST-001',
            'name' => 'Infant One',
            'sex' => 'Male',
            'status' => 'Under Monitoring',
            'iron_1' => now()->subDays(5)->toDateString(),
            'vitamin_a' => now()->subDays(4)->toDateString(),
            'mnp_start' => now()->subDays(3)->toDateString(),
        ]);

        $infant->forceFill(['updated_at' => now()->subDays(2)])->saveQuietly();

        $medicine = Medicine::create([
            'name' => 'BCG',
            'type' => 'Vaccine',
        ]);

        Immunization::create([
            'infant_id' => $infant->id,
            'medicine_id' => $medicine->id,
            'dose_number' => '1st',
            'administration_date' => now()->subDays(1)->toDateString(),
        ]);

        NutritionalAssessment::create([
            'infant_id' => $infant->id,
            'category' => 'months_6_11',
            'status' => 'Normal',
            'assessment_date' => now()->subDays(1)->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('committee.health.dashboard', [
            'mode' => 'month',
            'year' => now()->year,
            'month' => now()->month,
        ]));

        $response->assertOk();
        $response->assertSee('Overall Medicines Used');
        $response->assertSee('Monitoring Status Per Infant');
        $response->assertSee('Nutritional Status Per Infant');
        $response->assertSee('Iron');
        $response->assertSee('Vitamin A');
        $response->assertSee('MNP');
        $response->assertSee('BCG');
        $response->assertSee('Under Monitoring');
        $response->assertSee('Normal');
    }

    private function createCommitteeHead(string $committeeSlug, string $committeeName): User
    {
        $this->seed(PermissionSeeder::class);

        $committee = Committee::create([
            'name' => $committeeName,
            'slug' => $committeeSlug,
        ]);

        $official = Official::create([
            'name' => $committeeName . ' Head',
            'position' => 'Committee Head',
            'committee_id' => $committee->id,
            'term_start' => now()->subYear()->toDateString(),
            'term_end' => now()->addYear()->toDateString(),
        ]);

        $user = User::factory()->create([
            'official_id' => $official->id,
        ]);

        $user->assignRole('committee_head');

        return $user;
    }
}
