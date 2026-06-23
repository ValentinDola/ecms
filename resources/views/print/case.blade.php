@extends('layouts.print')

@section('title', $case->case_number)

@section('content')
<h2 style="font-size: 14pt; margin-bottom: 16px;">Consular Assistance Case Summary</h2>

<div class="section">
    <h2>Case Information</h2>
    <table>
        <tr><th>Case Number</th><td>{{ $case->case_number }}</td></tr>
        <tr><th>Case Type</th><td>{{ \App\Models\AssistanceCase::TYPES[$case->case_type] ?? $case->case_type }}</td></tr>
        <tr><th>Status</th><td>{{ \App\Models\AssistanceCase::STATUSES[$case->status] ?? $case->status }}</td></tr>
        <tr><th>Opened</th><td>{{ $case->opened_at->format('d M Y H:i') }}</td></tr>
        @if ($case->closed_at)
            <tr><th>Closed</th><td>{{ $case->closed_at->format('d M Y H:i') }}</td></tr>
        @endif
    </table>
</div>

@if ($case->citizen)
    <div class="section">
        <h2>Citizen</h2>
        <table>
            <tr><th>Full Name</th><td>{{ $case->citizen->full_name }}</td></tr>
            <tr><th>Passport Number</th><td>{{ $case->citizen->passport_number }}</td></tr>
            <tr><th>Phone</th><td>{{ $case->citizen->phone ?? '—' }}</td></tr>
        </table>
    </div>
@endif

<div class="section">
    <h2>Description</h2>
    <p>{{ $case->description }}</p>
</div>

@if ($case->actions_taken)
    <div class="section">
        <h2>Actions Taken</h2>
        <p>{{ $case->actions_taken }}</p>
    </div>
@endif

@if ($case->notes)
    <div class="section">
        <h2>Notes</h2>
        <p>{{ $case->notes }}</p>
    </div>
@endif
@endsection
