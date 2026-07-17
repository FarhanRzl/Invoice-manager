@php
    $kop = $invoice->kop_config ?? [];
    $theme = $invoice->theme;
    $forPdf = $forPdf ?? false;
    $src = fn ($path) => str_starts_with($path, 'data:')
        ? $path
        : ($forPdf
            ? 'file:///'.str_replace('\\', '/', public_path('storage/'.$path))
            : \Illuminate\Support\Facades\Storage::url($path));
@endphp

@if (! empty($kop['custom_image_path']))
    <div>
        <img src="{{ $src($kop['custom_image_path']) }}" alt="Kop surat" class="w-full max-h-40 object-contain bg-white">
    </div>
@else
    <div class="flex items-center justify-between gap-4 px-6 py-5" style="background-color: {{ $theme['hdr'] }}">
        <div class="flex items-center gap-3">
            @if (! empty($kop['logo_path']))
                <img src="{{ $src($kop['logo_path']) }}" alt="{{ $kop['name'] ?? '' }}" class="h-12 w-12 object-contain rounded bg-white p-1">
            @endif
            <div>
                <div class="font-bold text-white text-lg">{{ $kop['name'] ?? $invoice->brand->name }}</div>
                <div class="text-xs text-white/70">{{ $kop['address'] ?? '' }}</div>
                <div class="text-xs text-white/70">{{ $kop['phone'] ?? '' }} {{ ! empty($kop['email']) ? '· '.$kop['email'] : '' }}</div>
            </div>
        </div>
        <div class="text-right">
            <div class="text-2xl font-extrabold tracking-widest" style="color: {{ $theme['acc'] }}">INVOICE</div>
            <div class="text-xs text-white/80">{{ $invoice->nomor }}</div>
            <div class="text-xs text-white/70">Tgl: {{ $invoice->tanggal->format('d M Y') }} · Jth Tempo: {{ $invoice->jatuh_tempo->format('d M Y') }}</div>
        </div>
    </div>
@endif
