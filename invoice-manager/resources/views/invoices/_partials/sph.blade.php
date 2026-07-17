@php
    $sph = $invoice->sph_config ?? [];
@endphp

@if (! empty($sph['aktif']))
    <div class="sph-page" style="padding: 32px">
        <div style="margin-bottom: 20px">
            <div style="display:flex;justify-content:space-between;margin-bottom:8px">
                <div>
                    <div style="font-size:11px;color:#718096;font-weight:700;text-transform:uppercase">No. Ref</div>
                    <div style="font-size:13px;font-weight:600;color:#1a365d">{{ str_replace('INV/', 'SPH/', $invoice->nomor) }}</div>
                </div>
                <div style="text-align:right">
                    <div style="font-size:11px;color:#718096;font-weight:700;text-transform:uppercase">Tanggal</div>
                    <div style="font-size:13px;color:#2d3748">{{ $sph['tempat_tanggal'] ?? $invoice->tanggal->format('d M Y') }}</div>
                </div>
            </div>
            <div style="background:#f0f7ff;border-left:3px solid #1a365d;border-radius:0 8px 8px 0;padding:10px 16px;margin-top:8px">
                <span style="font-size:11px;font-weight:700;color:#718096;text-transform:uppercase">Perihal: </span>
                <span style="font-size:13px;font-weight:700;color:#1a365d">{{ $sph['perihal'] ?? 'Penawaran Harga' }}</span>
            </div>
        </div>

        <div style="font-size:13px;color:#2d3748;line-height:1.8;white-space:pre-wrap;margin-bottom:24px">{{ $sph['narasi'] ?? '' }}</div>

        <div style="display:flex;justify-content:flex-end;margin-top:24px">
            <div style="text-align:center;min-width:200px">
                <div style="font-size:12px;color:#718096;margin-bottom:60px">Hormat Kami,</div>
                <div style="border-top:1.5px solid #2d3748;padding-top:6px">
                    <div style="font-size:13px;font-weight:700;color:#1a365d">{{ $sph['pengirim'] ?? $invoice->brand->name }}</div>
                </div>
            </div>
        </div>
    </div>

    <div style="page-break-after: always"></div>
@endif
