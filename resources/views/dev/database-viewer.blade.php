@extends('layouts.app')

@section('title', 'Database Viewer')
@section('page-title', 'Database Viewer')

@section('content')
<div class="alert alert-warning d-flex justify-content-between align-items-center">
    <div>
        <strong>Development only:</strong> this page is intended for local debugging and should not be exposed in production.
    </div>
    @if ($databasePath)
        <a href="{{ route('dev.database-viewer') }}?open={{ urlencode($databasePath) }}" class="btn btn-secondary btn-sm text-decoration-none">
            Open database folder
        </a>
    @endif
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tables</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach ($tables as $table)
                        <li class="list-group-item {{ $selectedTable === $table ? 'active' : '' }}">
                            <a href="{{ route('dev.database-viewer', ['table' => $table]) }}" class="text-decoration-none {{ $selectedTable === $table ? 'text-white' : '' }}">
                                {{ $table }}
                                <span class="badge badge-secondary float-right">
                                    {{ $tableStats[$table]['count'] ?? 0 }} row(s)
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        @if ($selectedTable)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $selectedTable }}</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Columns: {{ is_array($columns) && count($columns) ? implode(', ', $columns) : 'None' }}
                    </p>

                    @if ($tableData->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        @foreach ($columns as $column)
                                            <th>{{ $column }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tableData as $row)
                                        <tr>
                                            @foreach ($columns as $column)
                                                <td>{{ $row->{$column} ?? '—' }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">No rows available in this table.</div>
                    @endif
                </div>
            </div>
        @else
            <div class="alert alert-info">No tables found.</div>
        @endif
    </div>
</div>
@endsection
