<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait HasHybridIdentifier
{
    public function getIncrementing(): bool
    {
        return false;
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    protected static function bootHasHybridIdentifier(): void
    {
        static::creating(function (Model $model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }

            if (empty($model->ref_no)) {
                $model->ref_no = $model->generateRefNo();
            }

            if (property_exists($model, 'syncReferenceToLegacyField')) {
                foreach ($model->syncReferenceToLegacyField as $field) {
                    if (empty($model->{$field})) {
                        $model->{$field} = $model->ref_no;
                    }
                }
            }
        });
    }

    protected function generateRefNo(): string
    {
        $year = now()->format('Y');
        $prefix = 'TGO-'.$this->getRefNoModule().'-'.$year.'-';

        return DB::transaction(function () use ($prefix) {
            $last = static::query()
                ->where('ref_no', 'like', $prefix.'%')
                ->orderByDesc('ref_no')
                ->lockForUpdate()
                ->first();

            $sequence = 1;
            if ($last && preg_match('/(\d{5})$/', $last->ref_no, $matches)) {
                $sequence = (int) $matches[1] + 1;
            }

            return $prefix.str_pad((string) $sequence, 5, '0', STR_PAD_LEFT);
        });
    }

    protected function getRefNoModule(): string
    {
        return property_exists(static::class, 'refNoModule')
            ? static::$refNoModule
            : strtoupper(class_basename(static::class));
    }

    public function getRouteKeyName(): string
    {
        return 'ref_no';
    }
}
