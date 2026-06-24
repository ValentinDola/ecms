<?php

namespace App\Models;

use App\Models\Traits\HasHybridIdentifier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Visa extends Model
{
    use HasFactory;
    use HasHybridIdentifier;

    protected static string $refNoModule = 'VIS';
    protected array $syncReferenceToLegacyField = ['visa_number'];

    public const TYPES = [
        'tourist' => 'Tourist',
        'business' => 'Business',
        'transit' => 'Transit',
        'diplomatic' => 'Diplomatic',
        'other' => 'Other',
    ];

    public const STATUSES = [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'expired' => 'Expired',
    ];

    protected $fillable = [
        'ref_no',
        'citizen_id',
        'visa_number',
        'passport_number',
        'applicant_first_name',
        'applicant_last_name',
        'visa_type',
        'issue_date',
        'expiry_date',
        'status',
        'purpose_of_visit',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'expiry_date' => 'date',
        ];
    }

    public function citizen(): BelongsTo
    {
        return $this->belongsTo(Citizen::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function getApplicantFullNameAttribute(): string
    {
        return trim("{$this->applicant_first_name} {$this->applicant_last_name}");
    }

    public function getVisaNumberAttribute(?string $value): string
    {
        return $value ?: ($this->ref_no ?? '—');
    }
}
