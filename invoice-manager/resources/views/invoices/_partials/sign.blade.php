@php
    $sign = $invoice->sign_config ?? [];
    $hasSign = ! empty($sign['ttd_path']) || ! empty($sign['stempel_path']) || ! empty($sign['materai_path']);
    $forPdf = $forPdf ?? false;
    $src = fn ($path) => str_starts_with($path, 'data:')
        ? $path
        : ($forPdf
            ? 'file:///'.str_replace('\\', '/', public_path('storage/'.$path))
            : \Illuminate\Support\Facades\Storage::url($path));
@endphp

@if ($invoice->qris_path)
    <div style="text-align:center">
        <div style="font-size:11px;font-weight:700;color:#64748b;margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px">Scan QRIS untuk pembayaran</div>
        <img src="{{ $src($invoice->qris_path) }}" alt="QRIS" style="margin:0 auto;height:160px;width:160px;object-fit:contain;border-radius:8px;border:1px solid #e2e8f0;background:#fff;padding:8px">
    </div>
@endif

@if ($hasSign)
    <table style="width:100%;border-collapse:collapse">
        <tr>
            <td style="text-align:right">
                <div style="display:inline-block;text-align:center;min-width:160px">
                    <div style="font-size:11px;color:#64748b;margin-bottom:8px">{{ $sign['ttd_nama'] ?? $invoice->brand->name }},</div>
                    <div style="position:relative;height:80px;text-align:center">
                        @if (! empty($sign['stempel_path']))
                            <img src="{{ $src($sign['stempel_path']) }}" style="position:absolute;top:0;left:50%;margin-left:-48px;opacity:.6;height:96px;width:96px;object-fit:contain">
                        @endif
                        @if (! empty($sign['ttd_path']))
                            <img src="{{ $src($sign['ttd_path']) }}" style="position:relative;height:64px;max-width:120px;object-fit:contain">
                        @endif
                        @if (! empty($sign['materai_path']))
                            <img src="{{ $src($sign['materai_path']) }}" style="position:absolute;bottom:0;right:0;height:48px;width:48px;object-fit:contain">
                        @endif
                    </div>
                    <div style="border-top:1px solid #334155;padding-top:4px;margin-top:4px;font-size:13px;font-weight:700">{{ $sign['ttd_nama'] ?? $invoice->brand->name }}</div>
                    @if (! empty($sign['ttd_jabatan']))
                        <div style="font-size:11px;color:#64748b">{{ $sign['ttd_jabatan'] }}</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>
@endif
