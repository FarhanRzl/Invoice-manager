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
    <div class="text-center">
        <div class="text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wide">Scan QRIS untuk pembayaran</div>
        <img src="{{ $src($invoice->qris_path) }}" alt="QRIS" class="mx-auto h-40 w-40 object-contain rounded border border-slate-200 bg-white p-2">
    </div>
@endif

@if ($hasSign)
    <div class="flex justify-end">
        <div class="text-center min-w-[160px]">
            <div class="text-xs text-slate-500 mb-2">{{ $sign['ttd_nama'] ?? $invoice->brand->name }},</div>
            <div class="relative h-20 flex items-center justify-center">
                @if (! empty($sign['stempel_path']))
                    <img src="{{ $src($sign['stempel_path']) }}" class="absolute opacity-60 h-24 w-24 object-contain">
                @endif
                @if (! empty($sign['ttd_path']))
                    <img src="{{ $src($sign['ttd_path']) }}" class="relative h-16 max-w-[120px] object-contain">
                @endif
                @if (! empty($sign['materai_path']))
                    <img src="{{ $src($sign['materai_path']) }}" class="absolute bottom-0 right-0 h-12 w-12 object-contain">
                @endif
            </div>
            <div class="border-t border-slate-700 pt-1 mt-1 text-sm font-bold">{{ $sign['ttd_nama'] ?? $invoice->brand->name }}</div>
            @if (! empty($sign['ttd_jabatan']))
                <div class="text-xs text-slate-500">{{ $sign['ttd_jabatan'] }}</div>
            @endif
        </div>
    </div>
@endif
