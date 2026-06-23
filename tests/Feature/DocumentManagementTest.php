<?php

namespace Tests\Feature;

use App\Models\AssistanceCase;
use App\Models\Citizen;
use App\Models\Document;
use App\Models\Visa;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentManagementTest extends TestCase
{
    public function test_documents_index_page_loads(): void
    {
        Document::factory()->create(['title' => 'Indexed Document']);

        $response = $this->get(route('documents.index'));

        $response->assertOk();
        $response->assertSee('Indexed Document');
    }

    public function test_document_can_be_uploaded_for_citizen(): void
    {
        Storage::fake('public');

        $citizen = Citizen::factory()->create();
        $file = UploadedFile::fake()->create('passport.pdf', 100, 'application/pdf');

        $response = $this->post(route('documents.store'), [
            'documentable_type' => Citizen::class,
            'documentable_id' => $citizen->id,
            'title' => 'Passport Copy',
            'category' => 'passport',
            'file' => $file,
        ]);

        $response->assertRedirect(route('citizens.show', $citizen));
        $this->assertDatabaseHas('documents', [
            'title' => 'Passport Copy',
            'documentable_type' => Citizen::class,
            'documentable_id' => $citizen->id,
        ]);
    }

    public function test_document_can_be_uploaded_for_visa(): void
    {
        Storage::fake('public');

        $visa = Visa::factory()->create();
        $file = UploadedFile::fake()->image('scan.jpg');

        $response = $this->post(route('documents.store'), [
            'documentable_type' => Visa::class,
            'documentable_id' => $visa->id,
            'title' => 'Supporting Scan',
            'category' => 'supporting',
            'file' => $file,
        ]);

        $response->assertRedirect(route('visas.show', $visa));
        $this->assertDatabaseHas('documents', ['title' => 'Supporting Scan']);
    }

    public function test_document_upload_rejects_invalid_file_type(): void
    {
        Storage::fake('public');

        $citizen = Citizen::factory()->create();
        $file = UploadedFile::fake()->create('virus.exe', 100, 'application/x-msdownload');

        $response = $this->post(route('documents.store'), [
            'documentable_type' => Citizen::class,
            'documentable_id' => $citizen->id,
            'title' => 'Bad File',
            'category' => 'other',
            'file' => $file,
        ]);

        $response->assertSessionHasErrors('file');
    }

    public function test_document_can_be_downloaded(): void
    {
        Storage::fake('public');

        $citizen = Citizen::factory()->create();
        $path = 'documents/2026/test.pdf';
        Storage::disk('public')->put($path, 'sample pdf content');

        $document = Document::factory()->create([
            'documentable_type' => Citizen::class,
            'documentable_id' => $citizen->id,
            'title' => 'Download Me',
            'file_path' => $path,
        ]);

        $response = $this->get(route('documents.download', $document));

        $response->assertOk();
        $response->assertDownload('download-me.pdf');
    }

    public function test_document_can_be_deleted(): void
    {
        Storage::fake('public');

        $citizen = Citizen::factory()->create();
        $path = 'documents/2026/delete-me.pdf';
        Storage::disk('public')->put($path, 'content');

        $document = Document::factory()->create([
            'documentable_type' => Citizen::class,
            'documentable_id' => $citizen->id,
            'file_path' => $path,
        ]);

        $response = $this->delete(route('documents.destroy', $document));

        $response->assertRedirect(route('citizens.show', $citizen));
        $this->assertDatabaseMissing('documents', ['id' => $document->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_document_upload_requires_valid_parent(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('passport.pdf', 100, 'application/pdf');

        $response = $this->post(route('documents.store'), [
            'documentable_type' => AssistanceCase::class,
            'documentable_id' => 99999,
            'title' => 'Orphan Document',
            'category' => 'other',
            'file' => $file,
        ]);

        $response->assertSessionHasErrors('documentable_id');
    }
}
