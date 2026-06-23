<?php

namespace App\Http\Controllers;

use App\Models\AssistanceCase;
use App\Models\Citizen;
use App\Models\Document;
use App\Models\Visa;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'citizenCount' => Citizen::count(),
            'visaCount' => Visa::count(),
            'caseCount' => AssistanceCase::count(),
            'openCaseCount' => AssistanceCase::where('status', '!=', 'closed')->count(),
            'documentCount' => Document::count(),
        ]);
    }
}
