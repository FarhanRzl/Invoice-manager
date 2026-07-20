<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h2 class="text-xl font-bold text-navy-600">{{ $formOrder->nomor }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">{{ $formOrder->brand->name ?? '-' }} &middot; {{ $formOrder->nama_klien }}</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('form-orders.pdf', $formOrder) }}" target="_blank"
                   class="inline-flex items-center gap-2 bg-gold-400 hover:bg-gold-500 text-navy-700 text-sm font-bold px-4 py-2 rounded-lg transition">
                    Download PDF
                </a>
                @if (! $formOrder->is_locked)
                    <form action="{{ route('form-orders.finalize', $formOrder) }}" method="POST"
                          onsubmit="return confirm('Tandai form order ini selesai? Setelah selesai, form tidak bisa diedit lagi.');">
                        @csrf
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold px-4 py-2 rounded-lg transition">
                            Tandai Selesai
                        </button>
                    </form>
                    <a href="{{ route('form-orders.edit', $formOrder) }}"
                       class="inline-flex items-center gap-2 bg-navy-600 hover:bg-navy-700 text-white text-sm font-bold px-4 py-2 rounded-lg transition">
                        Edit
                    </a>
                @endif
                <a href="{{ route('form-orders.index') }}" class="text-sm text-slate-500 hover:text-slate-700">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-navy-600">Data Proyek</h3>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                    {{ $formOrder->is_locked ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $formOrder->is_locked ? 'Selesai' : 'Draft' }}
                </span>
            </div>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                <div>
                    <dt class="text-slate-400 text-xs uppercase tracking-wide">Tanggal Order</dt>
                    <dd class="mt-0.5 text-slate-700">{{ $formOrder->tanggal_order->format('d M Y') }}</dd>
                </div>
                <div>
                    <dt class="text-slate-400 text-xs uppercase tracking-wide">Lokasi Project</dt>
                    <dd class="mt-0.5 text-slate-700">{{ $formOrder->lokasi_project ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-400 text-xs uppercase tracking-wide">Jenis Pekerjaan</dt>
                    <dd class="mt-0.5 text-slate-700">{{ $formOrder->jenis_pekerjaan ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-400 text-xs uppercase tracking-wide">Ukuran Bangunan</dt>
                    <dd class="mt-0.5 text-slate-700">{{ $formOrder->ukuran_bangunan ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-400 text-xs uppercase tracking-wide">Arah Mata Angin</dt>
                    <dd class="mt-0.5 text-slate-700">{{ $formOrder->arah_mata_angin ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-400 text-xs uppercase tracking-wide">Share Location</dt>
                    <dd class="mt-0.5 text-slate-700">
                        @if ($formOrder->share_location)
                            <a href="{{ $formOrder->share_location }}" target="_blank" class="text-navy-500 hover:underline">Buka Peta &rarr;</a>
                        @else
                            -
                        @endif
                    </dd>
                </div>
                @if ($formOrder->invoice)
                    <div class="sm:col-span-2">
                        <dt class="text-slate-400 text-xs uppercase tracking-wide">Disalin dari Invoice</dt>
                        <dd class="mt-0.5 text-slate-700">{{ $formOrder->invoice->nomor }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="text-sm font-semibold text-navy-600 mb-4">Lingkup Pekerjaan</h3>
            @if (! empty($formOrder->lingkup_pekerjaan))
                <ol class="list-decimal list-inside space-y-1 text-sm text-slate-700">
                    @foreach ($formOrder->lingkup_pekerjaan as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ol>
            @else
                <p class="text-sm text-slate-400">Belum ada lingkup pekerjaan.</p>
            @endif
        </div>

        @if ($formOrder->catatan_klien)
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h3 class="text-sm font-semibold text-navy-600 mb-4">Catatan dari Klien</h3>
                <p class="text-sm text-slate-700 whitespace-pre-line">{{ $formOrder->catatan_klien }}</p>
            </div>
        @endif

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="text-sm font-semibold text-navy-600 mb-4">Lampiran Gambar</h3>
            @if ($formOrder->images->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach ($formOrder->images as $img)
                        <div class="border border-slate-200 rounded-lg overflow-hidden">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($img->path) }}" class="w-full h-40 object-cover">
                            @if ($img->caption)
                                <div class="p-2 text-xs text-slate-600 bg-slate-50">{{ $img->caption }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-slate-400">Belum ada lampiran gambar.</p>
            @endif
        </div>

        @if ($formOrder->revisions->isNotEmpty())
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h3 class="text-sm font-semibold text-navy-600 mb-4">Revisi</h3>
                <div class="space-y-4">
                    @foreach ($formOrder->revisions as $rev)
                        <div class="border border-slate-200 rounded-lg p-4 flex gap-4">
                            @if ($rev->path)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($rev->path) }}" class="h-24 w-24 object-cover rounded border border-slate-200 shrink-0">
                            @endif
                            <div>
                                <div class="text-xs text-slate-400 mb-1">{{ $rev->created_at->translatedFormat('d M Y, H:i') }}</div>
                                <p class="text-sm text-slate-700 whitespace-pre-line">{{ $rev->catatan ?: '-' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if (! $formOrder->is_locked)
            <form action="{{ route('form-orders.destroy', $formOrder) }}" method="POST"
                  onsubmit="return confirm('Hapus form order {{ $formOrder->nomor }}? Tindakan ini tidak dapat dibatalkan.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium">
                    Hapus Form Order
                </button>
            </form>
        @endif
    </div>
</x-app-layout>
