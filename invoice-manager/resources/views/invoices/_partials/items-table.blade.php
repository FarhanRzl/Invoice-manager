@php
    $topItems = $invoice->items->whereNull('parent_item_id')->sortBy('urutan');
@endphp

<table class="w-full text-sm">
    <thead>
        <tr class="bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500">
            <th class="text-left px-5 py-2.5" style="text-align:left">Deskripsi</th>
            <th class="text-right px-5 py-2.5" style="text-align:right;white-space:nowrap">Volume</th>
            <th class="text-left px-5 py-2.5" style="text-align:left;white-space:nowrap">Satuan</th>
            <th class="text-right px-5 py-2.5" style="text-align:right;white-space:nowrap">Harga Satuan</th>
            <th class="text-right px-5 py-2.5" style="text-align:right;white-space:nowrap">Jumlah</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-slate-100">
        @foreach ($topItems as $item)
            @if ($item->type === 'group')
                <tr class="bg-sky-50" style="background:#f0f9ff">
                    <td class="px-5 py-2.5 font-semibold text-sky-700" style="font-weight:700;color:#0369a1" colspan="4">{{ $item->deskripsi ?: 'Sub Item' }}</td>
                    <td class="px-5 py-2.5 text-right font-semibold text-sky-700" style="text-align:right;white-space:nowrap;font-weight:700;color:#0369a1">Rp {{ number_format($item->subItems->sum('jumlah'), 0, ',', '.') }}</td>
                </tr>
                @foreach ($item->subItems->sortBy('urutan') as $sub)
                    <tr>
                        <td class="px-5 py-3 pl-9 text-slate-600" style="padding-left:36px;color:#475569">{{ $sub->deskripsi }}</td>
                        <td class="px-5 py-3 text-right" style="text-align:right;white-space:nowrap">{{ rtrim(rtrim($sub->volume, '0'), '.') }}</td>
                        <td class="px-5 py-3" style="white-space:nowrap">{{ $sub->satuan ?: '-' }}</td>
                        <td class="px-5 py-3 text-right" style="text-align:right;white-space:nowrap">Rp {{ number_format($sub->harga_satuan, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-right font-medium" style="text-align:right;white-space:nowrap;font-weight:600">Rp {{ number_format($sub->jumlah, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @elseif ($item->type === 'paket')
                @php
                    $lines = preg_split('/\r\n|\r|\n/', trim((string) $item->deskripsi));
                    $paketLabel = trim(array_shift($lines) ?? '') ?: 'Paket';
                    $paketBullets = implode("\n", $lines);
                @endphp
                <tr>
                    <td class="px-5 py-3" style="vertical-align:top">
                        <div style="font-weight:700;color:#1a365d;margin-bottom:4px">{{ $paketLabel }}</div>
                        @if ($paketBullets !== '')
                            <div class="whitespace-pre-line" style="white-space:pre-line;line-height:1.7;color:#374151;font-size:12.5px">{{ $paketBullets }}</div>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-right" style="text-align:right;white-space:nowrap;vertical-align:top">{{ rtrim(rtrim($item->volume, '0'), '.') }}</td>
                    <td class="px-5 py-3" style="white-space:nowrap;vertical-align:top">{{ $item->satuan ?: '-' }}</td>
                    <td class="px-5 py-3 text-right" style="text-align:right;white-space:nowrap;vertical-align:top">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 text-right font-medium" style="text-align:right;white-space:nowrap;font-weight:600;vertical-align:top">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                </tr>
            @else
                <tr>
                    <td class="px-5 py-3">{{ $item->deskripsi }}</td>
                    <td class="px-5 py-3 text-right" style="text-align:right;white-space:nowrap">{{ rtrim(rtrim($item->volume, '0'), '.') }}</td>
                    <td class="px-5 py-3" style="white-space:nowrap">{{ $item->satuan ?: '-' }}</td>
                    <td class="px-5 py-3 text-right" style="text-align:right;white-space:nowrap">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 text-right font-medium" style="text-align:right;white-space:nowrap;font-weight:600">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
