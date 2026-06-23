<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Citizen extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'full_name',
        'date_of_birth',
        'nationality',
        'passport_number',
        'phone',
        'email',
        'address_in_ghana',
        'city',
        'region',
        'registration_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'registration_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Citizen $citizen) {
            $citizen->full_name = trim("{$citizen->first_name} {$citizen->last_name}");
        });
    }

    public function visas(): HasMany
    {
        return $this->hasMany(Visa::class);
    }

    public function assistanceCases(): HasMany
    {
        return $this->hasMany(AssistanceCase::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
