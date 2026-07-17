@if (! empty($invoice->rekening_config))
    <div>
        <h3 class="text-sm font-semibold text-navy-600 mb-2">Rekening Pembayaran</h3>
        <div class="space-y-1 text-sm text-slate-700">
            @foreach ($invoice->rekening_config as $rek)
                <div><span class="text-slate-500">{{ $rek['bank'] ?? '' }}</span> — <strong>{{ $rek['norek'] ?? '' }}</strong> a/n {{ $rek['nama'] ?? '' }}</div>
            @endforeach
        </div>
    </div>
@endif
