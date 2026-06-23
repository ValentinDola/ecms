<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class AssistanceCase extends Model
{
    use HasFactory;

    public const TYPES = [
        'lost_passport'          => 'Lost Passport',
        'theft'                  => 'Theft',
        'robbery'                => 'Robbery',
        'arrest'                 => 'Arrest',
        'detention'              => 'Detention',
        'legal_assistance'       => 'Legal Assistance',
        'medical'                => 'Medical Emergency',
        'hospitalization'        => 'Hospitalization',
        'accident'               => 'Accident',
        'death'                  => 'Death',
        'repatriation'           => 'Repatriation',
        'missing_person'         => 'Missing Person',
        'family_dispute'         => 'Family Dispute',
        'other' => 'Other',
    ];

    public const STATUSES = [
        'open' => 'Open',
        'in_progress' => 'In Progress',
        'closed' => 'Closed',
    ];

    protected $fillable = [
        'case_number',
        'citizen_id',
        'case_type',
        'status',
        'opened_at',
        'closed_at',
        'description',
        'actions_taken',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
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
}
