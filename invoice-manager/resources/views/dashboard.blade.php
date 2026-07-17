<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h2 class="text-xl font-bold text-navy-600">Dashboard</h2>
                <p class="text-sm text-slate-500 mt-0.5">
                    Selamat datang, {{ auth()->user()->name }}
                    @if(!$isAdmin) &middot; {{ $brandCount }} brand Anda kelola @endif
                </p>
            </div>

            @if (\Illuminate\Support\Facades\Route::has('invoices.create'))
                <a href="{{ route('invoices.create') }}"
                   class="inline-flex items-center gap-2 bg-gold-400 hover:bg-gold-500 text-navy-700 text-sm font-bold px-4 py-2 rounded-lg transition">
                    <x-icon name="plus-circle" class="w-4 h-4" />
                    Buat Invoice
                </a>
            @endif
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- STAT CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wide">Total Invoice</div>
                <div class="text-2xl font-bold text-navy-600 mt-1">{{ $stats['total_invoice'] }}</div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wide">Lunas</div>
                <div class="text-2xl font-bold text-emerald-600 mt-1">{{ $stats['lunas'] }}</div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wide">Menunggu</div>
                <div class="text-2xl font-bold text-amber-500 mt-1">{{ $stats['menunggu'] }}</div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wide">Total Pendapatan</div>
                <div class="text-lg font-bold text-navy-600 mt-1">
                    Rp {{ number_format($stats['pendapatan'], 0, ',', '.') }}
                </div>
            </div>
        </div>

        @if (!\Illuminate\Support\Facades\Schema::hasTable('invoices'))
            <div class="rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3">
                💡 Modul Invoice belum dibangun &mdash; angka di atas masih placeholder (Fase 0: Auth &amp; Brand sudah aktif).
                Anda mengelola <strong>{{ $brandCount }}</strong> brand saat ini.
            </div>
        @endif

        {{-- CHARTS --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-white rounded-xl border border-slate-200">
                <div class="px-5 py-3 border-b border-slate-100 text-sm font-semibold text-navy-600">
                    Pendapatan per Brand
                </div>
                <div class="p-5">
                    @if($brandCount)
                        <div class="flex items-end justify-around gap-2 h-40 border-l-2 border-b-2 border-slate-100 px-2">
                            @foreach(range(1, min($brandCount, 6)) as $i)
                                <div class="flex flex-col items-center gap-1 flex-1">
                                    <div class="w-full max-w-8 bg-navy-600 rounded-t" style="height:{{ rand(20, 130) }}px"></div>
                                    <span class="text-[9px] text-slate-400">B{{ $i }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-slate-400 text-center py-14">Belum ada data</p>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200">
                <div class="px-5 py-3 border-b border-slate-100 text-sm font-semibold text-navy-600">
                    Pendapatan Bulanan
                </div>
                <div class="p-5">
                    <div class="flex items-end justify-around gap-1 h-40 border-l-2 border-b-2 border-slate-100 px-2">
                        @foreach(['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'] as $i => $m)
                            <div class="flex flex-col items-center gap-1 flex-1">
                                <div class="w-full max-w-5 bg-gold-400 rounded-t" style="height:{{ max(2, $monthlyRevenue[$i]) }}px"></div>
                                <span class="text-[8px] text-slate-400">{{ $m }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- RECENT INVOICES --}}
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-5 py-3 border-b border-slate-100 text-sm font-semibold text-navy-600">
                Invoice Terbaru
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500">
                            <th class="text-left px-5 py-2.5">No.</th>
                            <th class="text-left px-5 py-2.5">Brand</th>
                            <th class="text-left px-5 py-2.5">Klien</th>
                            <th class="text-left px-5 py-2.5">Total</th>
                            <th class="text-left px-5 py-2.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentInvoices as $inv)
                            <tr>
                                <td class="px-5 py-2.5 font-semibold text-navy-600">{{ $inv->nomor }}</td>
                                <td class="px-5 py-2.5">{{ $inv->brand->name ?? '-' }}</td>
                                <td class="px-5 py-2.5">{{ $inv->klien }}</td>
                                <td class="px-5 py-2.5 font-medium">Rp {{ number_format($inv->total, 0, ',', '.') }}</td>
                                <td class="px-5 py-2.5">
                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                        {{ $inv->status === 'lunas' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ ucfirst($inv->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-10 text-slate-400">Belum ada invoice</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
