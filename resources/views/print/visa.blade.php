@extends('layouts.print')

@section('title', $visa->ref_no)

@section('content')
<h2 style="font-size: 14pt; margin-bottom: 16px;">Visa Record</h2>

<div class="section">
    <h2>Applicant Information</h2>
    <table>
        <tr><th>Full Name</th><td>{{ $visa->applicant_full_name }}</td></tr>
        <tr><th>Passport Number</th><td>{{ $visa->passport_number }}</td></tr>
        @if ($visa->citizen)
            <tr><th>Registered Citizen</th><td>{{ $visa->citizen->full_name }}</td></tr>
        @endif
    </table>
</div>

<div class="section">
    <h2>Visa Details</h2>
    <table>
        <tr><th>Reference Number</th><td>{{ $visa->ref_no }}</td></tr>
        <tr><th>Visa Type</th><td>{{ \App\Models\Visa::TYPES[$visa->visa_type] ?? $visa->visa_type }}</td></tr>
        <tr><th>Status</th><td>{{ \App\Models\Visa::STATUSES[$visa->status] ?? $visa->status }}</td></tr>
        <tr><th>Issue Date</th><td>{{ $visa->issue_date->format('d M Y') }}</td></tr>
        <tr><th>Expiry Date</th><td>{{ $visa->expiry_date->format('d M Y') }}</td></tr>
        @if ($visa->purpose_of_visit)
            <tr><th>Purpose of Visit</th><td>{{ $visa->purpose_of_visit }}</td></tr>
        @endif
    </table>
</div>

@if ($visa->notes)
    <div class="section">
        <h2>Notes</h2>
        <p>{{ $visa->notes }}</p>
    </div>
@endif
@endsection
