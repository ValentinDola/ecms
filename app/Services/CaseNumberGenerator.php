<?php

namespace App\Services;

use App\Models\AssistanceCase;
use Illuminate\Support\Facades\DB;

class CaseNumberGenerator
{
    public function generate(?int $year = null): string
    {
        $year ??= (int) now()->format('Y');
        $prefix = "CA-{$year}-";

        return DB::transaction(function () use ($prefix) {
            $lastCase = AssistanceCase::query()
                ->where('case_number', 'like', $prefix.'%')
                ->orderByDesc('case_number')
                ->lockForUpdate()
                ->first();

            $sequence = 1;

            if ($lastCase) {
                $sequence = (int) substr($lastCase->case_number, -5) + 1;
            }

            return $prefix.str_pad((string) $sequence, 5, '0', STR_PAD_LEFT);
        });
    }
}
