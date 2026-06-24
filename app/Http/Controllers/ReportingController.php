<?php

namespace App\Http\Controllers;

use App\Services\ReportingService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportingController extends Controller
{
    public function __construct(private ReportingService $reportingService) {}

    public function index(Request $request)
    {
        $report = $request->string('report')->toString() ?? 'visas';
        $startDate = $request->string('start_date')->toString() ? Carbon::parse($request->string('start_date')->toString()) : null;
        $endDate = $request->string('end_date')->toString() ? Carbon::parse($request->string('end_date')->toString()) : null;
        $query = $request->string('q')->toString();

        $reportData = match ($report) {
            'citizens' => $this->reportingService->getCitizenReport($startDate, $endDate, $query),
            'cases' => $this->reportingService->getAssistanceReport($startDate, $endDate, $query),
            default => $this->reportingService->getVisaReport($startDate, $endDate, $query),
        };

        return view('reports.index', $reportData);
    }

    public function print(Request $request)
    {
        $report = $request->string('report')->toString() ?? 'visas';
        $startDate = $request->string('start_date')->toString() ? Carbon::parse($request->string('start_date')->toString()) : null;
        $endDate = $request->string('end_date')->toString() ? Carbon::parse($request->string('end_date')->toString()) : null;
        $query = $request->string('q')->toString();

        $reportData = match ($report) {
            'citizens' => $this->reportingService->getCitizenReport($startDate, $endDate, $query),
            'cases' => $this->reportingService->getAssistanceReport($startDate, $endDate, $query),
            default => $this->reportingService->getVisaReport($startDate, $endDate, $query),
        };

        return view('reports.print', $reportData);
    }
}
