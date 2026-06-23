@extends('layouts.print')

@section('title', $citizen->full_name)

@section('content')
<h2 style="font-size: 14pt; margin-bottom: 16px;">Citizen Registry Record</h2>

<div class="section">
    <h2>Personal Details</h2>
    <table>
        <tr><th>Full Name</th><td>{{ $citizen->full_name }}</td></tr>
        <tr><th>Passport Number</th><td>{{ $citizen->passport_number }}</td></tr>
        <tr><th>Date of Birth</th><td>{{ $citizen->date_of_birth?->format('d M Y') ?? '—' }}</td></tr>
        <tr><th>Nationality</th><td>{{ $citizen->nationality }}</td></tr>
        <tr><th>Phone</th><td>{{ $citizen->phone ?? '—' }}</td></tr>
        <tr><th>Email</th><td>{{ $citizen->email ?? '—' }}</td></tr>
    </table>
</div>

<div class="section">
    <h2>Residence in Ghana</h2>
    <table>
        <tr><th>Address</th><td>{{ $citizen->address_in_ghana ?? '—' }}</td></tr>
        <tr><th>City</th><td>{{ $citizen->city ?? '—' }}</td></tr>
        <tr><th>Region</th><td>{{ $citizen->region ?? '—' }}</td></tr>
        <tr><th>Registration Date</th><td>{{ $citizen->registration_date->format('d M Y') }}</td></tr>
    </table>
</div>

@if ($citizen->visas->isNotEmpty())
    <div class="section">
        <h2>Linked Visa Records ({{ $citizen->visas->count() }})</h2>
        <table>
            @foreach ($citizen->visas as $visa)
                <tr>
                    <th>{{ $visa->visa_number }}</th>
                    <td>{{ ucfirst($visa->visa_type) }} — {{ $visa->issue_date->format('d M Y') }} to {{ $visa->expiry_date->format('d M Y') }}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endif

@if ($citizen->assistanceCases->isNotEmpty())
    <div class="section">
        <h2>Assistance Cases ({{ $citizen->assistanceCases->count() }})</h2>
        <table>
            @foreach ($citizen->assistanceCases as $case)
                <tr>
                    <th>{{ $case->case_number }}</th>
                    <td>{{ \App\Models\AssistanceCase::TYPES[$case->case_type] ?? $case->case_type }} — {{ ucfirst(str_replace('_', ' ', $case->status)) }}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endif

@if ($citizen->notes)
    <div class="section">
        <h2>Notes</h2>
        <p>{{ $citizen->notes }}</p>
    </div>
@endif
@endsection
