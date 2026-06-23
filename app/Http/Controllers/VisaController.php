<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVisaRequest;
use App\Http\Requests\UpdateVisaRequest;
use App\Models\Citizen;
use App\Models\Visa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VisaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('q')->trim();

        $visas = Visa::query()
            ->with('citizen')
            ->when($search->isNotEmpty(), function ($query) use ($search) {
                $term = $search->toString();
                $query->where(function ($q) use ($term) {
                    $q->where('visa_number', 'like', "%{$term}%")
                        ->orWhere('passport_number', 'like', "%{$term}%")
                        ->orWhere('applicant_first_name', 'like', "%{$term}%")
                        ->orWhere('applicant_last_name', 'like', "%{$term}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        return view('visas.index', compact('visas', 'search'));
    }

    public function create(Request $request)
    {
        $citizens = Citizen::orderBy('last_name')->orderBy('first_name')->get();
        $selectedCitizen = $request->filled('citizen_id')
            ? Citizen::find($request->integer('citizen_id'))
            : null;

        return view('visas.create', compact('citizens', 'selectedCitizen'));
    }

    public function store(StoreVisaRequest $request)
    {
        $visa = Visa::create($request->validated());

        return redirect()
            ->route('visas.show', $visa)
            ->with('success', 'Visa record created successfully.');
    }

    public function show(Visa $visa)
    {
        $visa->load(['citizen', 'documents']);

        return view('visas.show', compact('visa'));
    }

    public function edit(Visa $visa)
    {
        $citizens = Citizen::orderBy('last_name')->orderBy('first_name')->get();

        return view('visas.edit', compact('visa', 'citizens'));
    }

    public function update(UpdateVisaRequest $request, Visa $visa)
    {
        $visa->update($request->validated());

        return redirect()
            ->route('visas.show', $visa)
            ->with('success', 'Visa record updated.');
    }

    public function destroy(Visa $visa)
    {
        $visa->delete();

        return redirect()
            ->route('visas.index')
            ->with('success', 'Visa record deleted.');
    }

    public function citizenLookup(Request $request): JsonResponse
    {
        $query = $request->string('q')->trim();

        if ($query->isEmpty()) {
            return response()->json([]);
        }

        $term = $query->toString();

        $citizens = Citizen::query()
            ->where(function ($q) use ($term) {
                $q->where('full_name', 'like', "%{$term}%")
                    ->orWhere('passport_number', 'like', "%{$term}%");
            })
            ->orderBy('last_name')
            ->limit(10)
            ->get(['id', 'first_name', 'last_name', 'passport_number', 'full_name']);

        return response()->json($citizens);
    }
}
