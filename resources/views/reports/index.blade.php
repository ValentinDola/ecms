@extends('layouts.app')

@section('title', 'Reports')
@section('page-title', 'Generate Reports')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Report Filters</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('reports.index') }}" class="row">
                    <div class="form-group col-md-3">
                        <label for="report">Report Type</label>
                        <select name="report" id="report" class="form-control" onchange="this.form.submit()">
                            <option value="visas" {{ $type === 'visas' ? 'selected' : '' }}>Visa Report</option>
                            <option value="citizens" {{ $type === 'citizens' ? 'selected' : '' }}>Citizen Report</option>
                            <option value="cases" {{ $type === 'cases' ? 'selected' : '' }}>Assistance Report</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="start_date">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" 
                               value="{{ $startDate ? $startDate->format('Y-m-d') : '' }}">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" 
                               value="{{ $endDate ? $endDate->format('Y-m-d') : '' }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="q">Search</label>
                        <input type="search" name="q" id="q" class="form-control" 
                               placeholder="Reference, name, passport…" value="{{ $query }}">
                    </div>
                    <div class="form-group col-md-2">
                        <label>&nbsp;</label>
                        <div class="btn-group btn-block" role="group">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('reports.print') }}?report={{ $type }}&start_date={{ $startDate ? $startDate->format('Y-m-d') : '' }}&end_date={{ $endDate ? $endDate->format('Y-m-d') : '' }}&q={{ $query }}" 
                               target="_blank" class="btn btn-info">
                                <i data-lucide="printer"></i> Print
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if ($startDate && $endDate)
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $report }} 
                        <span class="badge badge-primary float-right ml-2">{{ $count }} record(s)</span>
                    </h3>
                </div>
                <div class="card-body p-0">
                    @if ($count > 0)
                        @if ($type === 'visas')
                            <table class="table table-sm table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Reference No.</th>
                                        <th>Applicant Name</th>
                                        <th>Passport Number</th>
                                        <th>Issue Date</th>
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($records as $visa)
                                        <tr>
                                            <td><strong>{{ $visa->ref_no }}</strong></td>
                                            <td>{{ $visa->applicant_full_name }}</td>
                                            <td>{{ $visa->passport_number }}</td>
                                            <td>{{ $visa->issue_date?->format('d M Y') ?? '—' }}</td>
                                            <td>
                                                <span class="badge badge-{{ $visa->status === 'approved' ? 'success' : ($visa->status === 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ \App\Models\Visa::STATUSES[$visa->status] ?? ucfirst($visa->status) }}
                                                </span>
                                            </td>
                                            <td class="text-right">
                                                <a href="{{ route('visas.show', $visa) }}" class="text-info">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @elseif ($type === 'citizens')
                            <table class="table table-sm table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Reference No.</th>
                                        <th>Full Name</th>
                                        <th>Passport Number</th>
                                        <th>Nationality</th>
                                        <th>Registration Date</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($records as $citizen)
                                        <tr>
                                            <td><strong>{{ $citizen->ref_no }}</strong></td>
                                            <td>{{ $citizen->full_name }}</td>
                                            <td>{{ $citizen->passport_number }}</td>
                                            <td>{{ $citizen->nationality ?? '—' }}</td>
                                            <td>{{ $citizen->created_at->format('d M Y') }}</td>
                                            <td class="text-right">
                                                <a href="{{ route('citizens.show', $citizen) }}" class="text-info">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @elseif ($type === 'cases')
                            <table class="table table-sm table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Reference No.</th>
                                        <th>Citizen Name</th>
                                        <th>Assistance Type</th>
                                        <th>Status</th>
                                        <th>Date Opened</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($records as $case)
                                        <tr>
                                            <td><strong>{{ $case->ref_no }}</strong></td>
                                            <td>{{ $case->citizen->full_name ?? '—' }}</td>
                                            <td>{{ \App\Models\AssistanceCase::TYPES[$case->case_type] ?? $case->case_type }}</td>
                                            <td>
                                                <span class="badge badge-{{ $case->status === 'resolved' ? 'success' : ($case->status === 'closed' ? 'secondary' : 'warning') }}">
                                                    {{ \App\Models\AssistanceCase::STATUSES[$case->status] ?? ucfirst(str_replace('_', ' ', $case->status)) }}
                                                </span>
                                            </td>
                                            <td>{{ $case->opened_at->format('d M Y') }}</td>
                                            <td class="text-right">
                                                <a href="{{ route('assistance.show', $case) }}" class="text-info">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    @else
                        <div class="alert alert-info m-3">No records found for the selected date range and filters.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-light border">
        Select a date range and report type above to generate a report.
    </div>
@endif
@endsection

@push('scripts')
<script>
    lucide.createIcons();
</script>
@endpush
