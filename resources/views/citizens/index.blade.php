@extends('layouts.app')

@section('title', 'Citizens')
@section('page-title', 'Citizen Registry')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">All Citizens</h3>
        <a href="{{ route('citizens.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Register Citizen
        </a>
    </div>
    <div class="card-body border-bottom">
        <form method="GET" class="form-inline">
            <input type="search" name="q" class="form-control form-control-sm mr-2"
                   placeholder="Search name, passport, phone…" value="{{ $search }}">
            <button type="submit" class="btn btn-sm btn-outline-secondary">Filter</button>
            @if ($search->isNotEmpty())
                <a href="{{ route('citizens.index') }}" class="btn btn-sm btn-link">Clear</a>
            @endif
        </form>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Passport</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>Registered</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($citizens as $citizen)
                    <tr>
                        <td>{{ $citizen->full_name }}</td>
                        <td>{{ $citizen->passport_number }}</td>
                        <td>{{ $citizen->phone ?? '—' }}</td>
                        <td>{{ $citizen->city ?? '—' }}</td>
                        <td>{{ $citizen->registration_date->format('d M Y') }}</td>
                        <td class="text-right text-nowrap">
                            <a href="{{ route('citizens.show', $citizen) }}" class="btn btn-xs btn-default">View</a>
                            <a href="{{ route('citizens.edit', $citizen) }}" class="btn btn-xs btn-default">Edit</a>
                            <a href="{{ route('print.citizen', $citizen) }}" class="btn btn-xs btn-default" target="_blank">Print</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No citizens registered yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($citizens->hasPages())
        <div class="card-footer">{{ $citizens->links() }}</div>
    @endif
</div>
@endsection
