<?php

namespace App\Models;

use App\Models\Traits\HasHybridIdentifier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;
    use HasHybridIdentifier;

    protected static string $refNoModule = 'DOC';

    protected $fillable = [
        'ref_no',
        'documentable_type',
        'documentable_id',
        'title',
        'category',
        'file_path',
        'mime_type',
        'file_size',
        'uploaded_at',
    ];

    public const CATEGORIES = [
        'passport' => 'Passport',
        'certificate' => 'Certificate',
        'supporting' => 'Supporting',
        'other' => 'Other',
    ];

    protected function casts(): array
    {
        return [
            'uploaded_at' => 'datetime',
        ];
    }

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->file_path);
    }

    public function getFormattedSizeAttribute(): string
    {
        if (! $this->file_size) {
            return '—';
        }

        if ($this->file_size >= 1048576) {
            return number_format($this->file_size / 1048576, 1).' MB';
        }

        return number_format($this->file_size / 1024, 1).' KB';
    }

    public function getParentTypeLabelAttribute(): string
    {
        return match ($this->documentable_type) {
            Citizen::class => 'Citizen',
            Visa::class => 'Visa',
            AssistanceCase::class => 'Assistance Case',
            default => 'Record',
        };
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'image/');
    }

    public function isPdf(): bool
    {
        return ($this->mime_type ?? '') === 'application/pdf';
    }

    public function parentShowUrl(): ?string
    {
        if (! $this->documentable) {
            return null;
        }

        return match ($this->documentable_type) {
            Citizen::class => route('citizens.show', $this->documentable),
            Visa::class => route('visas.show', $this->documentable),
            AssistanceCase::class => route('assistance.show', $this->documentable),
            default => null,
        };
    }

    public function parentLabel(): string
    {
        if (! $this->documentable) {
            return '—';
        }

        return match ($this->documentable_type) {
            Citizen::class => $this->documentable->full_name,
            Visa::class => $this->documentable->ref_no,
            AssistanceCase::class => $this->documentable->ref_no,
            default => '#'.$this->documentable_id,
        };
    }
}
