<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCitizenRequest;
use App\Http\Requests\UpdateCitizenRequest;
use App\Models\Citizen;
use Illuminate\Http\Request;

class CitizenController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('q')->trim();

        $citizens = Citizen::query()
            ->when($search->isNotEmpty(), function ($query) use ($search) {
                $term = $search->toString();
                $query->where(function ($q) use ($term) {
                    $q->where('full_name', 'like', "%{$term}%")
                        ->orWhere('passport_number', 'like', "%{$term}%")
                        ->orWhere('phone', 'like', "%{$term}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        return view('citizens.index', compact('citizens', 'search'));
    }

    public function create()
    {
        return view('citizens.create');
    }

    public function store(StoreCitizenRequest $request)
    {
        $citizen = Citizen::create($request->validated());

        return redirect()
            ->route('citizens.show', $citizen)
            ->with('success', 'Citizen registered successfully.');
    }

    public function show(Citizen $citizen)
    {
        $citizen->load(['visas', 'assistanceCases', 'documents']);

        return view('citizens.show', compact('citizen'));
    }

    public function edit(Citizen $citizen)
    {
        return view('citizens.edit', compact('citizen'));
    }

    public function update(UpdateCitizenRequest $request, Citizen $citizen)
    {
        $citizen->update($request->validated());

        return redirect()
            ->route('citizens.show', $citizen)
            ->with('success', 'Citizen record updated.');
    }

    public function destroy(Citizen $citizen)
    {
        $citizen->delete();

        return redirect()
            ->route('citizens.index')
            ->with('success', 'Citizen record deleted.');
    }
}
