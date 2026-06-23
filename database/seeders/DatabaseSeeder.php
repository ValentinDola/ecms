<?php

namespace Database\Seeders;

use App\Models\AssistanceCase;
use App\Models\Citizen;
use App\Models\Document;
use App\Models\Visa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $koffi = Citizen::create([
            'first_name' => 'Koffi',
            'last_name' => 'Mensah',
            'full_name' => 'Koffi Mensah',
            'date_of_birth' => '1985-03-15',
            'nationality' => 'Togolese',
            'passport_number' => 'TG1234567',
            'phone' => '+233 24 123 4567',
            'email' => 'koffi.mensah@example.com',
            'address_in_ghana' => '12 Independence Avenue, Accra',
            'city' => 'Accra',
            'region' => 'Greater Accra',
            'registration_date' => now()->subMonths(2)->toDateString(),
        ]);

        $ama = Citizen::create([
            'first_name' => 'Ama',
            'last_name' => 'Agbeko',
            'full_name' => 'Ama Agbeko',
            'date_of_birth' => '1992-07-22',
            'nationality' => 'Togolese',
            'passport_number' => 'TG7654321',
            'phone' => '+233 55 987 6543',
            'address_in_ghana' => '45 Ring Road, Kumasi',
            'city' => 'Kumasi',
            'region' => 'Ashanti',
            'registration_date' => now()->subWeek()->toDateString(),
        ]);

        $koffiVisa = Visa::create([
            'citizen_id' => $koffi->id,
            'visa_number' => 'V-2026-00142',
            'passport_number' => $koffi->passport_number,
            'applicant_first_name' => $koffi->first_name,
            'applicant_last_name' => $koffi->last_name,
            'visa_type' => 'business',
            'issue_date' => now()->subMonth()->toDateString(),
            'expiry_date' => now()->addMonths(5)->toDateString(),
            'status' => 'approved',
            'purpose_of_visit' => 'Business meetings in Lomé',
        ]);

        Visa::create([
            'citizen_id' => null,
            'visa_number' => 'V-2026-00143',
            'passport_number' => 'GH9988776',
            'applicant_first_name' => 'Jean',
            'applicant_last_name' => 'Dupont',
            'visa_type' => 'tourist',
            'issue_date' => now()->subWeeks(2)->toDateString(),
            'expiry_date' => now()->addMonths(1)->toDateString(),
            'status' => 'pending',
            'purpose_of_visit' => 'Tourism',
        ]);

        Visa::create([
            'citizen_id' => $ama->id,
            'visa_number' => 'V-2026-00144',
            'passport_number' => $ama->passport_number,
            'applicant_first_name' => $ama->first_name,
            'applicant_last_name' => $ama->last_name,
            'visa_type' => 'transit',
            'issue_date' => now()->subDays(5)->toDateString(),
            'expiry_date' => now()->addDays(10)->toDateString(),
            'status' => 'approved',
        ]);

        AssistanceCase::create([
            'case_number' => 'CA-2026-00001',
            'citizen_id' => $koffi->id,
            'case_type' => 'lost_passport',
            'status' => 'in_progress',
            'opened_at' => now()->subDays(10),
            'description' => 'Citizen reported passport lost in Accra. Police report filed.',
            'actions_taken' => 'Issued temporary travel document. Advised on replacement process.',
        ]);

        AssistanceCase::create([
            'case_number' => 'CA-2026-00002',
            'citizen_id' => $ama->id,
            'case_type' => 'medical',
            'status' => 'open',
            'opened_at' => now()->subDays(2),
            'description' => 'Medical emergency requiring evacuation coordination.',
            'actions_taken' => 'Contacted family in Togo. Hospital liaison in progress.',
        ]);

        AssistanceCase::create([
            'case_number' => 'CA-2026-00003',
            'citizen_id' => $koffi->id,
            'case_type' => 'arrest',
            'status' => 'closed',
            'opened_at' => now()->subMonths(3),
            'closed_at' => now()->subMonths(2),
            'description' => 'Temporary detention following documentation issue.',
            'actions_taken' => 'Provided consular visit and legal referral. Case resolved.',
        ]);

        $passportScanPath = 'documents/'.now()->format('Y').'/sample-passport-scan.png';
        Storage::disk('public')->put($passportScanPath, base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg=='
        ));

        Document::create([
            'documentable_type' => Citizen::class,
            'documentable_id' => $koffi->id,
            'title' => 'Passport Scan',
            'category' => 'passport',
            'file_path' => $passportScanPath,
            'mime_type' => 'image/png',
            'file_size' => Storage::disk('public')->size($passportScanPath),
            'uploaded_at' => now()->subDays(30),
        ]);

        $visaDocPath = 'documents/'.now()->format('Y').'/sample-visa-supporting.pdf';
        Storage::disk('public')->put($visaDocPath, '%PDF-1.4 sample visa supporting document');

        Document::create([
            'documentable_type' => Visa::class,
            'documentable_id' => $koffiVisa->id,
            'title' => 'Invitation Letter',
            'category' => 'supporting',
            'file_path' => $visaDocPath,
            'mime_type' => 'application/pdf',
            'file_size' => Storage::disk('public')->size($visaDocPath),
            'uploaded_at' => now()->subDays(20),
        ]);
    }
}
