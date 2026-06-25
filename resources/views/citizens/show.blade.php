@extends('layouts.app')

@section('title', $citizen->full_name)
@section('page-title', $citizen->full_name)

@section('content')
<div class="mb-3">
    <a href="{{ route('citizens.edit', $citizen) }}" class="btn btn-primary btn-sm"><i width="16" height="16" data-lucide="square-pen" class="mr-2"></i>Edit</a>
    <a href="{{ route('print.citizen', $citizen) }}" class="btn btn-default btn-sm" target="_blank"><i width="16" height="16" data-lucide="printer" class="mr-2"></i> Print</a>
    <form action="{{ route('citizens.destroy', $citizen) }}" method="POST" class="d-inline"
          data-confirm="This will permanently delete the citizen record and cannot be undone."
          data-confirm-title="Delete citizen record">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm"><i width="16" height="16" data-lucide="trash-2" class="mr-2"></i> Delete</button>
    </form>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Personal Information</h3></div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <tr><th>Reference Number</th><td>{{ $citizen->ref_no }}</td></tr>
                    <tr><th>Passport</th><td>{{ $citizen->passport_number }}</td></tr>
                    <tr><th>Date of Birth</th><td>{{ $citizen->date_of_birth?->format('d M Y') ?? '—' }}</td></tr>
                    <tr><th>Nationality</th><td>{{ $citizen->nationality }}</td></tr>
                    <tr><th>Phone</th><td>{{ $citizen->phone ?? '—' }}</td></tr>
                    <tr><th>Email</th><td>{{ $citizen->email ?? '—' }}</td></tr>
                    <tr><th>Address</th><td>{{ $citizen->address_in_ghana ?? '—' }}</td></tr>
                    <tr><th>City / Region</th><td>{{ collect([$citizen->city, $citizen->region])->filter()->join(', ') ?: '—' }}</td></tr>
                    <tr><th>Registered</th><td>{{ $citizen->registration_date->format('d M Y') }}</td></tr>
                    @if ($citizen->notes)
                        <tr><th>Notes</th><td>{{ $citizen->notes }}</td></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header"><h3 class="card-title">Linked Records</h3></div>
            <div class="card-body">
                <p><strong>{{ $citizen->visas->count() }}</strong> visa record(s)</p>
                <p><strong>{{ $citizen->assistanceCases->count() }}</strong> assistance case(s)</p>
                <p class="mb-0"><strong>{{ $citizen->documents->count() }}</strong> document(s)</p>
            </div>
        </div>
    </div>
</div>

@if ($citizen->visas->isNotEmpty())
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Visa Records</h3>
            <a href="{{ route('visas.create', ['citizen_id' => $citizen->id]) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Visa
            </a>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>Reference No.</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Expires</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($citizen->visas as $visa)
                        <tr>
                            <td>{{ $visa->ref_no }}</td>
                            <td>{{ \App\Models\Visa::TYPES[$visa->visa_type] ?? $visa->visa_type }}</td>
                            <td>{{ \App\Models\Visa::STATUSES[$visa->status] ?? $visa->status }}</td>
                            <td>{{ $visa->expiry_date->format('d M Y') }}</td>
                            <td class="text-right">
                                <a href="{{ route('visas.show', $visa) }}">View</a>
                                ·
                                <a href="{{ route('print.visa', $visa) }}" target="_blank">Print</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
            <span class="text-muted">No visa records linked to this citizen.</span>
            <a href="{{ route('visas.create', ['citizen_id' => $citizen->id]) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Visa
            </a>
        </div>
    </div>
@endif

@if ($citizen->assistanceCases->isNotEmpty())
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Assistance Cases</h3>
            <a href="{{ route('assistance.create', ['citizen_id' => $citizen->id]) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Open Case
            </a>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>Case ID</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Opened</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($citizen->assistanceCases as $case)
                        <tr>
                            <td>{{ $case->ref_no }}</td>
                            <td>{{ \App\Models\AssistanceCase::TYPES[$case->case_type] ?? $case->case_type }}</td>
                            <td>{{ \App\Models\AssistanceCase::STATUSES[$case->status] ?? $case->status }}</td>
                            <td>{{ $case->opened_at->format('d M Y') }}</td>
                            <td class="text-right">
                                <a href="{{ route('assistance.show', $case) }}">View</a>
                                ·
                                <a href="{{ route('print.case', $case) }}" target="_blank">Print</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-body d-flex justify-content-between align-items-center">
            <span class="text-muted">No assistance cases for this citizen.</span>
            <a href="{{ route('assistance.create', ['citizen_id' => $citizen->id]) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Open Case
            </a>
        </div>
    </div>
@endif

@include('documents.partials.section', ['parent' => $citizen, 'documents' => $citizen->documents])
@endsection
