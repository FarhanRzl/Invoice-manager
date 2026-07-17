@php
    $topItems = $invoice->items->whereNull('parent_item_id')->sortBy('urutan');
@endphp

<table class="w-full text-sm">
    <thead>
        <tr class="bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500">
            <th class="text-left px-5 py-2.5">Deskripsi</th>
            <th class="text-right px-5 py-2.5">Volume</th>
            <th class="text-left px-5 py-2.5">Satuan</th>
            <th class="text-right px-5 py-2.5">Harga Satuan</th>
            <th class="text-right px-5 py-2.5">Jumlah</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-slate-100">
        @foreach ($topItems as $item)
            @if ($item->type === 'group')
                <tr class="bg-sky-50">
                    <td class="px-5 py-2.5 font-semibold text-sky-700" colspan="4">{{ $item->deskripsi ?: 'Sub Item' }}</td>
                    <td class="px-5 py-2.5 text-right font-semibold text-sky-700">Rp {{ number_format($item->subItems->sum('jumlah'), 0, ',', '.') }}</td>
                </tr>
                @foreach ($item->subItems->sortBy('urutan') as $sub)
                    <tr>
                        <td class="px-5 py-3 pl-9 text-slate-600">{{ $sub->deskripsi }}</td>
                        <td class="px-5 py-3 text-right">{{ rtrim(rtrim($sub->volume, '0'), '.') }}</td>
                        <td class="px-5 py-3">{{ $sub->satuan ?: '-' }}</td>
                        <td class="px-5 py-3 text-right">Rp {{ number_format($sub->harga_satuan, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-right font-medium">Rp {{ number_format($sub->jumlah, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="px-5 py-3 {{ $item->type === 'paket' ? 'whitespace-pre-line' : '' }}">{{ $item->deskripsi }}</td>
                    <td class="px-5 py-3 text-right">{{ rtrim(rtrim($item->volume, '0'), '.') }}</td>
                    <td class="px-5 py-3">{{ $item->satuan ?: '-' }}</td>
                    <td class="px-5 py-3 text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 text-right font-medium">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
