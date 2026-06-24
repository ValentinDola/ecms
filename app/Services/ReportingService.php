<?php

namespace App\Services;

use App\Models\AssistanceCase;
use App\Models\Citizen;
use App\Models\Visa;
use Carbon\Carbon;

class ReportingService
{
    public function getVisaReport(?Carbon $startDate = null, ?Carbon $endDate = null, ?string $query = null): array
    {
        $qry = Visa::query();

        if ($startDate) {
            $qry->whereDate('issue_date', '>=', $startDate);
        }
        if ($endDate) {
            $qry->whereDate('issue_date', '<=', $endDate);
        }

        if ($query) {
            $qry->where(function ($q) use ($query) {
                $q->where('ref_no', 'like', "%{$query}%")
                    ->orWhere('visa_number', 'like', "%{$query}%")
                    ->orWhere('passport_number', 'like', "%{$query}%")
                    ->orWhere('applicant_first_name', 'like', "%{$query}%")
                    ->orWhere('applicant_last_name', 'like', "%{$query}%");
            });
        }

        $visas = $qry->orderByDesc('issue_date')->get();

        return [
            'report' => 'Visa Report',
            'type' => 'visas',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'query' => $query,
            'records' => $visas,
            'count' => $visas->count(),
            'total' => $visas->count(),
        ];
    }

    public function getCitizenReport(?Carbon $startDate = null, ?Carbon $endDate = null, ?string $query = null): array
    {
        $qry = Citizen::query();

        if ($startDate) {
            $qry->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $qry->whereDate('created_at', '<=', $endDate);
        }

        if ($query) {
            $qry->where(function ($q) use ($query) {
                $q->where('full_name', 'like', "%{$query}%")
                    ->orWhere('ref_no', 'like', "%{$query}%")
                    ->orWhere('passport_number', 'like', "%{$query}%");
            });
        }

        $citizens = $qry->orderByDesc('created_at')->get();

        return [
            'report' => 'Citizen Report',
            'type' => 'citizens',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'query' => $query,
            'records' => $citizens,
            'count' => $citizens->count(),
            'total' => $citizens->count(),
        ];
    }

    public function getAssistanceReport(?Carbon $startDate = null, ?Carbon $endDate = null, ?string $query = null): array
    {
        $qry = AssistanceCase::query()->with('citizen');

        if ($startDate) {
            $qry->whereDate('opened_at', '>=', $startDate);
        }
        if ($endDate) {
            $qry->whereDate('opened_at', '<=', $endDate);
        }

        if ($query) {
            $qry->where(function ($q) use ($query) {
                $q->where('ref_no', 'like', "%{$query}%")
                    ->orWhere('case_number', 'like', "%{$query}%");
            });
        }

        $cases = $qry->orderByDesc('opened_at')->get();

        return [
            'report' => 'Assistance Report',
            'type' => 'cases',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'query' => $query,
            'records' => $cases,
            'count' => $cases->count(),
            'total' => $cases->count(),
        ];
    }
}
