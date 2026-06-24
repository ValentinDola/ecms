<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Print') — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    
    <style>
        @page { size: A4; margin: 15mm; }
        * { box-sizing: border-box; font-family: 'Source Code Pro', ui-sans-serif, system-ui, sans-serif !important; }
        body {
            padding: 20px;
        }
        .letterhead {
            text-align: center;
            border-bottom: 2px solid #006233;
            padding-bottom: 12px;
            margin-bottom: 24px;
        }
        .letterhead h1 {
            font-size: 16pt;
            margin: 0 0 4px;
            color: #006233;
        }
        .letterhead p { margin: 0; font-size: 10pt; color: #444; }
        .section { margin-bottom: 20px; }
        .section h2 {
            font-size: 12pt;
            border-bottom: 1px solid #ccc;
            padding-bottom: 4px;
            margin: 0 0 10px;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 6px 8px; vertical-align: top; }
        th { width: 35%; font-weight: bold; color: #333; }
        .footer {
            margin-top: 40px;
            padding-top: 12px;
            border-top: 1px solid #ccc;
            font-size: 9pt;
            color: #666;
        }
        .no-print {
            margin-bottom: 20px;
            text-align: right;
        }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- <div class="no-print">
        <button onclick="window.print()">Print (Ctrl+P)</button>
        <a href="javascript:history.back()">Back</a>
    </div> -->

    <button class="btn btn-sm btn-outline-secondary no-print mb-3" onclick="window.print()">
        <i data-lucide="printer"></i> Print
    </button>

    <!-- <div class="letterhead">
        <h1>Embassy of the Republic of Togo</h1>
        <p>Accra, Ghana — Consular Section</p>
    </div> -->

    @yield('content')

    <!-- <div class="footer">
        Printed on {{ now()->format('d M Y H:i') }} — {{ config('app.name') }}
    </div> -->

        <script src="https://cdn.jsdelivr.net/npm/lucide@1.21.0/dist/umd/lucide.min.js"></script>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
