@extends('layouts.app')

@section('title', 'Consular Assistance')
@section('page-title', 'Consular Assistance')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Assistance Cases</h3>
        <a href="{{ route('assistance.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Open New Case
        </a>
    </div>
    <div class="card-body border-bottom">
        <form method="GET" class="form-inline flex-wrap">
            <input type="search" name="q" class="form-control form-control-sm mr-2 mb-2"
                   placeholder="Search case ID, citizen, description…" value="{{ $search }}">
            <select name="status" class="form-control form-control-sm mr-2 mb-2">
                <option value="">All statuses</option>
                @foreach (\App\Models\AssistanceCase::STATUSES as $value => $label)
                    <option value="{{ $value }}" @selected($status->toString() === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-outline-secondary mb-2">Filter</button>
            @if ($search->isNotEmpty() || $status->isNotEmpty())
                <a href="{{ route('assistance.index') }}" class="btn btn-sm btn-link mb-2">Clear</a>
            @endif
        </form>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Case ID</th>
                    <th>Citizen</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Opened</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cases as $case)
                    <tr>
                        <td><strong>{{ $case->ref_no }}</strong></td>
                        <td>
                            {{ $case->citizen->full_name ?? '—' }}
                            @if ($case->citizen)
                                <br><small class="text-muted">{{ $case->citizen->passport_number }}</small>
                            @endif
                        </td>
                        <td>{{ \App\Models\AssistanceCase::TYPES[$case->case_type] ?? $case->case_type }}</td>
                        <td>
                            @php
                                $badge = match ($case->status) {
                                    'open' => 'danger',
                                    'in_progress' => 'warning',
                                    'closed' => 'success',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge badge-{{ $badge }}">{{ \App\Models\AssistanceCase::STATUSES[$case->status] ?? $case->status }}</span>
                        </td>
                        <td>{{ $case->opened_at->format('d M Y') }}</td>
                        <td class="text-right text-nowrap">
                            <a href="{{ route('assistance.show', $case) }}" class="btn btn-xs btn-default">View</a>
                            <a href="{{ route('assistance.edit', $case) }}" class="btn btn-xs btn-default">Edit</a>
                            <a href="{{ route('print.case', $case) }}" class="btn btn-xs btn-default" target="_blank">Print</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No assistance cases yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($cases->hasPages())
        <div class="card-footer">{{ $cases->links() }}</div>
    @endif
</div>
@endsection
