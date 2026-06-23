@extends('layouts.app')

@section('title', $visa->visa_number)
@section('page-title', 'Visa ' . $visa->visa_number)

@section('content')
<div class="mb-3">
    <a href="{{ route('visas.edit', $visa) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit</a>
    <a href="{{ route('print.visa', $visa) }}" class="btn btn-default btn-sm" target="_blank"><i class="fas fa-print"></i> Print</a>
    <form action="{{ route('visas.destroy', $visa) }}" method="POST" class="d-inline"
          data-confirm="This will permanently delete the visa record and cannot be undone."
          data-confirm-title="Delete visa record">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
    </form>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Visa Details</h3></div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <tr><th>Applicant</th><td>{{ $visa->applicant_full_name }}</td></tr>
                    <tr><th>Passport Number</th><td>{{ $visa->passport_number }}</td></tr>
                    <tr><th>Visa Number</th><td>{{ $visa->visa_number }}</td></tr>
                    <tr><th>Visa Type</th><td>{{ \App\Models\Visa::TYPES[$visa->visa_type] ?? $visa->visa_type }}</td></tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ \App\Models\Visa::STATUSES[$visa->status] ?? $visa->status }}</td>
                    </tr>
                    <tr><th>Issue Date</th><td>{{ $visa->issue_date->format('d M Y') }}</td></tr>
                    <tr><th>Expiry Date</th><td>{{ $visa->expiry_date->format('d M Y') }}</td></tr>
                    @if ($visa->purpose_of_visit)
                        <tr><th>Purpose of Visit</th><td>{{ $visa->purpose_of_visit }}</td></tr>
                    @endif
                    @if ($visa->notes)
                        <tr><th>Notes</th><td>{{ $visa->notes }}</td></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Linked Citizen</h3></div>
            <div class="card-body">
                @if ($visa->citizen)
                    <p class="mb-1"><strong>{{ $visa->citizen->full_name }}</strong></p>
                    <p class="mb-1 text-muted">{{ $visa->citizen->passport_number }}</p>
                    <p class="mb-0">{{ $visa->citizen->phone ?? '' }}</p>
                    <a href="{{ route('citizens.show', $visa->citizen) }}" class="btn btn-sm btn-outline-primary mt-3">View Citizen</a>
                @else
                    <p class="text-muted mb-0">No citizen linked to this visa record.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@include('documents.partials.section', ['parent' => $visa, 'documents' => $visa->documents])
@endsection
