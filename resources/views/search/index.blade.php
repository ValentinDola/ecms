@extends('layouts.app')

@section('title', 'Global Search')
@section('page-title', 'Global Search')

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('search') }}">
            <div class="input-group">
                <input type="search" name="q" class="form-control form-control-lg"
                       placeholder="Search by name, passport, visa number, phone, or case ID…"
                       value="{{ $query }}" autofocus>
                <div class="input-group-append">
                    <button class="btn btn-primary btn-lg" type="submit">Search</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if ($query !== '')
    <p class="text-muted">{{ $total }} result(s) for "<strong>{{ $query }}</strong>"</p>

    @if ($citizens->isNotEmpty())
        <div class="card mb-3">
            <div class="card-header"><h3 class="card-title">Citizens ({{ $citizens->count() }})</h3></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    @foreach ($citizens as $citizen)
                        <tr>
                            <td>{{ $citizen->full_name }}</td>
                            <td>{{ $citizen->passport_number }}</td>
                            <td>{{ $citizen->phone ?? '—' }}</td>
                            <td class="text-right">
                                <a href="{{ route('citizens.show', $citizen) }}">View</a>
                                ·
                                <a href="{{ route('print.citizen', $citizen) }}" target="_blank">Print</a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    @endif

    @if ($visas->isNotEmpty())
        <div class="card mb-3">
            <div class="card-header"><h3 class="card-title">Visas ({{ $visas->count() }})</h3></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    @foreach ($visas as $visa)
                        <tr>
                            <td>{{ $visa->applicant_full_name }}</td>
                            <td>{{ $visa->ref_no }}</td>
                            <td>{{ $visa->passport_number }}</td>
                            <td>{{ \App\Models\Visa::STATUSES[$visa->status] ?? ucfirst($visa->status) }}</td>
                            <td class="text-right">
                                <a href="{{ route('visas.show', $visa) }}">View</a>
                                ·
                                <a href="{{ route('print.visa', $visa) }}" target="_blank">Print</a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    @endif

    @if ($cases->isNotEmpty())
        <div class="card mb-3">
            <div class="card-header"><h3 class="card-title">Assistance Cases ({{ $cases->count() }})</h3></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    @foreach ($cases as $case)
                        <tr>
                            <td>{{ $case->ref_no }}</td>
                            <td>{{ $case->citizen->full_name ?? '—' }}</td>
                            <td>{{ \App\Models\AssistanceCase::TYPES[$case->case_type] ?? $case->case_type }}</td>
                            <td>{{ \App\Models\AssistanceCase::STATUSES[$case->status] ?? ucfirst(str_replace('_', ' ', $case->status)) }}</td>
                            <td class="text-right">
                                <a href="{{ route('assistance.show', $case) }}">View</a>
                                ·
                                <a href="{{ route('print.case', $case) }}" target="_blank">Print</a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    @endif

    @if ($total === 0)
        <div class="alert alert-info">No results found. Try a different search term.</div>
    @endif
@else
    <div class="alert alert-light border">
        Enter a search term above to search across citizens, visas, and assistance cases.
    </div>
@endif
@endsection
