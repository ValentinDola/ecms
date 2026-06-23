<?php

namespace Tests\Unit;

use App\Models\Citizen;
use App\Models\Document;
use App\Services\DocumentStorageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentStorageServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_stores_file_and_creates_document_record(): void
    {
        Storage::fake('public');

        $citizen = Citizen::factory()->create();
        $file = UploadedFile::fake()->create('passport.pdf', 120, 'application/pdf');
        $service = app(DocumentStorageService::class);

        $document = $service->store($citizen, $file, [
            'title' => 'Passport Scan',
            'category' => 'passport',
        ]);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertSame('Passport Scan', $document->title);
        $this->assertSame('passport', $document->category);
        $this->assertDatabaseHas('documents', ['id' => $document->id, 'title' => 'Passport Scan']);
        Storage::disk('public')->assertExists($document->file_path);
    }

    public function test_it_deletes_file_and_database_record(): void
    {
        Storage::fake('public');

        $citizen = Citizen::factory()->create();
        $file = UploadedFile::fake()->create('certificate.pdf', 120, 'application/pdf');
        $service = app(DocumentStorageService::class);
        $document = $service->store($citizen, $file, [
            'title' => 'Birth Certificate',
            'category' => 'certificate',
        ]);

        $path = $document->file_path;
        $service->delete($document);

        $this->assertDatabaseMissing('documents', ['id' => $document->id]);
        Storage::disk('public')->assertMissing($path);
    }
}
