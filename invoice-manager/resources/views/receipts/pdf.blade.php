<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $invoice->nomor_kwitansi }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #fff;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
        }
        table { border-collapse: collapse; width: 100%; }
        img { max-width: 100%; }
        .page { max-width: 760px; margin: 0 auto; padding: 24px; }
        .kop-wrap { max-width: 760px; margin: 0 auto; }
        .rekening-sign-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 24px; }
        @page { size: A4; margin: 12mm; }
    </style>
</head>
<body>

    <div class="kop-wrap">
        @include('invoices._partials.kop', ['forPdf' => true])
    </div>

    <div class="page">
        <div style="text-align:center;margin:24px 0">
            <div style="font-size:26px;font-weight:800;letter-spacing:4px;color:{{ $invoice->theme['hdr'] }}">KWITANSI</div>
            <div style="font-size:13px;color:#718096;margin-top:4px">{{ $invoice->nomor_kwitansi }}</div>
        </div>

        <table style="margin-bottom:20px">
            <tr>
                <td style="width:35%;padding:6px 0;color:#718096;font-size:13px">Telah Diterima Dari</td>
                <td style="padding:6px 0;font-size:14px;font-weight:700;color:#1a365d">: {{ $invoice->klien }}</td>
            </tr>
            <tr>
                <td style="padding:6px 0;color:#718096;font-size:13px">Tanggal Lunas</td>
                <td style="padding:6px 0;font-size:13px">: {{ $invoice->tanggal_lunas?->format('d M Y') ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding:6px 0;color:#718096;font-size:13px">Untuk Pembayaran</td>
                <td style="padding:6px 0;font-size:13px">: Invoice {{ $invoice->nomor }}</td>
            </tr>
        </table>

        <div style="margin-bottom:20px">
            @include('invoices._partials.totals')
        </div>

        <div class="rekening-sign-grid">
            <div>@include('invoices._partials.rekening', ['forPdf' => true])</div>
            <div>@include('invoices._partials.sign', ['forPdf' => true])</div>
        </div>
    </div>

</body>
</html>
