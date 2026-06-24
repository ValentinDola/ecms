<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $report }} — ECMS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        * {
            font-family: 'Source Code Pro', ui-sans-serif, system-ui, sans-serif !important;
        }
        body {
            padding: 20px;
        }
        @media print {
            body {
                margin: 0;
                padding: 10mm;
            }
            .no-print {
                display: none !important;
            }
            table {
                page-break-inside: avoid;
            }
            tr {
                page-break-inside: avoid;
            }
        }
        .embassy-header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .embassy-header h2 {
            margin-bottom: 5px;
            font-weight: bold;
        }
        .embassy-header p {
            margin: 0;
            font-size: 0.9rem;
        }
        .report-title {
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 20px 0;
        }
        .report-meta {
            text-align: right;
            font-size: 0.9rem;
            margin-bottom: 20px;
            color: #666;
        }
        .table-small {
            font-size: 0.85rem;
        }
        .page-break {
            page-break-after: always;
        }
        .badge {
            font-size: 0.75rem;
        }
    </style>
</head>
<body>
    <button class="btn btn-sm btn-outline-secondary no-print mb-3" onclick="window.print()">
        <i data-lucide="printer"></i> Print
    </button>

    <!-- <div class="embassy-header">
        <h2>Embassy of the Republic of Togo in Ghana</h2>
        <p>Consular Management System</p>
        <p>Official Report</p>
    </div> -->

    <div class="report-title">{{ $report }}</div>

    <div class="report-meta">
        @if ($startDate && $endDate)
            <strong>Period:</strong> {{ $startDate->format('d M Y') }} to {{ $endDate->format('d M Y') }}<br>
        @endif
        <strong>Generated:</strong> {{ now()->format('d M Y \a\t H:i') }}<br>
        <strong>Total Records:</strong> {{ $total }}
    </div>

    <table class="table table-sm table-hover table-small border">
        @if ($type === 'visas')
            <thead class="bg-light">
                <tr>
                    <th style="width: 12%">Ref. No.</th>
                    <th style="width: 20%">Applicant Name</th>
                    <th style="width: 15%">Passport No.</th>
                    <th style="width: 12%">Issue Date</th>
                    <th style="width: 12%">Status</th>
                    <th style="width: 29%">Notes</th>
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
                        <td>{{ $visa->notes ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        @elseif ($type === 'citizens')
            <thead class="bg-light">
                <tr>
                    <th style="width: 12%">Ref. No.</th>
                    <th style="width: 20%">Full Name</th>
                    <th style="width: 15%">Passport No.</th>
                    <th style="width: 15%">Nationality</th>
                    <th style="width: 12%">Reg. Date</th>
                    <th style="width: 26%">Contact</th>
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
                        <td>{{ $citizen->phone ?? $citizen->email ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        @elseif ($type === 'cases')
            <thead class="bg-light">
                <tr>
                    <th style="width: 12%">Ref. No.</th>
                    <th style="width: 15%">Citizen Name</th>
                    <th style="width: 15%">Case Type</th>
                    <th style="width: 12%">Status</th>
                    <th style="width: 12%">Opened</th>
                    <th style="width: 34%">Notes</th>
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
                        <td>{{ $case->case_description ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        @endif
    </table>

    <!-- <footer class="mt-5 pt-3 border-top text-center text-muted" style="font-size: 0.8rem;">
        <p>Embassy of the Republic of Togo in Ghana — Consular Management System</p>
        <p>This is an official document. Printed on {{ now()->format('d F Y \a\t H:i') }}</p>
    </footer> -->

    <script src="https://cdn.jsdelivr.net/npm/lucide@1.21.0/dist/umd/lucide.min.js"></script>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
