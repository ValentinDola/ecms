<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssistanceCaseRequest;
use App\Http\Requests\UpdateAssistanceCaseRequest;
use App\Models\AssistanceCase;
use App\Models\Citizen;
use App\Services\CaseNumberGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AssistanceCaseController extends Controller
{
    public function __construct(private CaseNumberGenerator $caseNumberGenerator) {}

    public function index(Request $request)
    {
        $search = $request->string('q')->trim();
        $status = $request->string('status')->trim();

        $cases = AssistanceCase::query()
            ->with('citizen')
            ->when($search->isNotEmpty(), function ($query) use ($search) {
                $term = $search->toString();
                $query->where(function ($q) use ($term) {
                    $q->where('case_number', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%")
                        ->orWhereHas('citizen', function ($citizenQuery) use ($term) {
                            $citizenQuery->where('full_name', 'like', "%{$term}%")
                                ->orWhere('passport_number', 'like', "%{$term}%");
                        });
                });
            })
            ->when($status->isNotEmpty(), fn ($query) => $query->where('status', $status->toString()))
            ->orderByDesc('opened_at')
            ->paginate(25)
            ->withQueryString();

        return view('assistance.index', compact('cases', 'search', 'status'));
    }

    public function create(Request $request)
    {
        $citizens = Citizen::orderBy('last_name')->orderBy('first_name')->get();
        $selectedCitizen = $request->filled('citizen_id')
            ? Citizen::find($request->integer('citizen_id'))
            : null;
        $nextCaseNumber = $this->caseNumberGenerator->generate();

        return view('assistance.create', compact('citizens', 'selectedCitizen', 'nextCaseNumber'));
    }

    public function store(StoreAssistanceCaseRequest $request)
    {
        $data = $request->validated();
        $data['case_number'] = $this->caseNumberGenerator->generate();
        $data['opened_at'] = Carbon::parse($data['opened_at'])->setTimeFromTimeString(now()->format('H:i:s'));

        if ($data['status'] === 'closed') {
            $data['closed_at'] = now();
        }

        $case = AssistanceCase::create($data);

        return redirect()
            ->route('assistance.show', $case)
            ->with('success', "Assistance case {$case->case_number} opened successfully.");
    }

    public function show(AssistanceCase $assistance)
    {
        $assistance->load(['citizen', 'documents']);

        return view('assistance.show', ['case' => $assistance]);
    }

    public function edit(AssistanceCase $assistance)
    {
        $citizens = Citizen::orderBy('last_name')->orderBy('first_name')->get();

        return view('assistance.edit', ['case' => $assistance, 'citizens' => $citizens]);
    }

    public function update(UpdateAssistanceCaseRequest $request, AssistanceCase $assistance)
    {
        $data = $request->validated();
        $data['opened_at'] = Carbon::parse($data['opened_at'])
            ->setTimeFromTimeString($assistance->opened_at?->format('H:i:s') ?? now()->format('H:i:s'));

        if ($data['status'] === 'closed') {
            $data['closed_at'] = ! empty($data['closed_at'])
                ? Carbon::parse($data['closed_at'])->setTimeFromTimeString(now()->format('H:i:s'))
                : now();
        } else {
            $data['closed_at'] = null;
        }

        $assistance->update($data);

        return redirect()
            ->route('assistance.show', $assistance)
            ->with('success', 'Assistance case updated.');
    }

    public function destroy(AssistanceCase $assistance)
    {
        $caseNumber = $assistance->case_number;
        $assistance->delete();

        return redirect()
            ->route('assistance.index')
            ->with('success', "Assistance case {$caseNumber} deleted.");
    }
}
