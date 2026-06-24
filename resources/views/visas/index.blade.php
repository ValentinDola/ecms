@extends('layouts.app')

@section('title', 'Visas')
@section('page-title', 'Visa Management')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">All Visa Records</h3>
        <a href="{{ route('visas.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> New Visa Record
        </a>
    </div>
    <div class="card-body border-bottom">
        <form method="GET" class="form-inline">
            <input type="search" name="q" class="form-control form-control-sm mr-2"
                   placeholder="Search visa number, passport, name…" value="{{ $search }}">
            <button type="submit" class="btn btn-sm btn-outline-secondary">Filter</button>
            @if ($search->isNotEmpty())
                <a href="{{ route('visas.index') }}" class="btn btn-sm btn-link">Clear</a>
            @endif
        </form>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Applicant</th>
                    <th>Visa No.</th>
                    <th>Passport</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Expires</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($visas as $visa)
                    <tr>
                        <td>
                            {{ $visa->applicant_full_name }}
                            @if ($visa->citizen)
                                <br><small class="text-muted">Linked: {{ $visa->citizen->full_name }}</small>
                            @endif
                        </td>
                        <td>{{ $visa->ref_no }}</td>
                        <td>{{ $visa->passport_number }}</td>
                        <td>{{ \App\Models\Visa::TYPES[$visa->visa_type] ?? $visa->visa_type }}</td>
                        <td>
                            @php
                                $badge = match ($visa->status) {
                                    'approved' => 'success',
                                    'pending' => 'warning',
                                    'rejected' => 'danger',
                                    'expired' => 'secondary',
                                    default => 'light',
                                };
                            @endphp
                            <span class="badge badge-{{ $badge }}">{{ \App\Models\Visa::STATUSES[$visa->status] ?? $visa->status }}</span>
                        </td>
                        <td>{{ $visa->expiry_date->format('d M Y') }}</td>
                        <td class="text-right text-nowrap">
                            <a href="{{ route('visas.show', $visa) }}" class="btn btn-xs btn-default">View</a>
                            <a href="{{ route('visas.edit', $visa) }}" class="btn btn-xs btn-default">Edit</a>
                            <a href="{{ route('print.visa', $visa) }}" class="btn btn-xs btn-default" target="_blank">Print</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No visa records yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($visas->hasPages())
        <div class="card-footer">{{ $visas->links() }}</div>
    @endif
</div>
@endsection
