<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentStorageService
{
    public function store(Model $parent, UploadedFile $file, array $attributes): Document
    {
        $year = now()->format('Y');
        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid().'.'.strtolower($extension);
        $path = "documents/{$year}/{$filename}";

        Storage::disk('public')->putFileAs(
            "documents/{$year}",
            $file,
            $filename
        );

        return $parent->documents()->create([
            'title' => $attributes['title'],
            'category' => $attributes['category'],
            'file_path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_at' => now(),
        ]);
    }

    public function delete(Document $document): void
    {
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();
    }
}
