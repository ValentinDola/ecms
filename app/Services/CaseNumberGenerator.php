<?php

namespace App\Services;

use App\Models\AssistanceCase;
use Illuminate\Support\Facades\DB;

class CaseNumberGenerator
{
    public function generate(?int $year = null): string
    {
        $year ??= (int) now()->format('Y');
        $prefix = "TGO-ASC-{$year}-";

        return DB::transaction(function () use ($prefix) {
            $lastCase = AssistanceCase::query()
                ->where('ref_no', 'like', $prefix.'%')
                ->orderByDesc('ref_no')
                ->lockForUpdate()
                ->first();

            $sequence = 1;

            if ($lastCase && preg_match('/(\d{5})$/', $lastCase->ref_no, $matches)) {
                $sequence = (int) $matches[1] + 1;
            }

            return $prefix.str_pad((string) $sequence, 5, '0', STR_PAD_LEFT);
        });
    }
}
