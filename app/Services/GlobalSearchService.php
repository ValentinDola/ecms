<?php

namespace App\Services;

use App\Models\AssistanceCase;
use App\Models\Citizen;
use App\Models\Visa;
use Illuminate\Support\Collection;

class GlobalSearchService
{
    public function search(?string $query, int $limit = 20): array
    {
        $query = trim($query ?? '');

        if ($query === '') {
            return [
                'query' => '',
                'citizens' => collect(),
                'visas' => collect(),
                'cases' => collect(),
                'total' => 0,
            ];
        }

        $citizens = $this->searchCitizens($query, $limit);
        $visas = $this->searchVisas($query, $limit);
        $cases = $this->searchCases($query, $limit);

        return [
            'query' => $query,
            'citizens' => $citizens,
            'visas' => $visas,
            'cases' => $cases,
            'total' => $citizens->count() + $visas->count() + $cases->count(),
        ];
    }

    private function searchCitizens(string $query, int $limit): Collection
    {
        return Citizen::query()
            ->where(function ($q) use ($query) {
                $q->where('full_name', 'like', "%{$query}%")
                    ->orWhere('passport_number', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%");
            })
            ->orderBy('last_name')
            ->limit($limit)
            ->get();
    }

    private function searchVisas(string $query, int $limit): Collection
    {
        return Visa::query()
            ->where(function ($q) use ($query) {
                $q->where('visa_number', 'like', "%{$query}%")
                    ->orWhere('passport_number', 'like', "%{$query}%")
                    ->orWhere('applicant_first_name', 'like', "%{$query}%")
                    ->orWhere('applicant_last_name', 'like', "%{$query}%");
            })
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    private function searchCases(string $query, int $limit): Collection
    {
        return AssistanceCase::query()
            ->with('citizen')
            ->where('case_number', 'like', "%{$query}%")
            ->orderByDesc('opened_at')
            ->limit($limit)
            ->get();
    }
}
