@extends('layouts.app')

@section('title', $case->ref_no)
@section('page-title', $case->ref_no)

@section('content')
<div class="mb-3">
    <a href="{{ route('assistance.edit', $case) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit</a>
    <a href="{{ route('print.case', $case) }}" class="btn btn-default btn-sm" target="_blank"><i class="fas fa-print"></i> Print</a>
    <form action="{{ route('assistance.destroy', $case) }}" method="POST" class="d-inline"
          data-confirm="This will permanently delete this assistance case and cannot be undone."
          data-confirm-title="Delete assistance case">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
    </form>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Case Details</h3></div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <tr><th>Reference Number</th><td>{{ $case->ref_no }}</td></tr>
                    <tr><th>Case Type</th><td>{{ \App\Models\AssistanceCase::TYPES[$case->case_type] ?? $case->case_type }}</td></tr>
                    <tr><th>Status</th><td>{{ \App\Models\AssistanceCase::STATUSES[$case->status] ?? $case->status }}</td></tr>
                    <tr><th>Opened</th><td>{{ $case->opened_at->format('d M Y H:i') }}</td></tr>
                    @if ($case->closed_at)
                        <tr><th>Closed</th><td>{{ $case->closed_at->format('d M Y H:i') }}</td></tr>
                    @endif
                    <tr><th>Description</th><td>{{ $case->description }}</td></tr>
                    @if ($case->actions_taken)
                        <tr><th>Actions Taken</th><td>{{ $case->actions_taken }}</td></tr>
                    @endif
                    @if ($case->notes)
                        <tr><th>Notes</th><td>{{ $case->notes }}</td></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header"><h3 class="card-title">Citizen</h3></div>
            <div class="card-body">
                @if ($case->citizen)
                    <p class="mb-1"><strong>{{ $case->citizen->full_name }}</strong></p>
                    <p class="mb-1 text-muted">{{ $case->citizen->passport_number }}</p>
                    <p class="mb-1">{{ $case->citizen->phone ?? '—' }}</p>
                    <a href="{{ route('citizens.show', $case->citizen) }}" class="btn btn-sm btn-outline-primary mt-2">View Citizen</a>
                @else
                    <p class="text-muted mb-0">Citizen record unavailable.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@include('documents.partials.section', ['parent' => $case, 'documents' => $case->documents])
@endsection
