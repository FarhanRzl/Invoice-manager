<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');

        // Brand milik user (admin melihat semua, brand_user hanya miliknya)
        $brands = $isAdmin ? Brand::all() : $user->brands;

        // NOTE: Modul Invoice belum dibangun (masih Fase 0 - Auth & Brand).
        // Statistik berikut sengaja diberi nilai aman (0) agar dashboard tidak error
        // dan tinggal disambungkan begitu model Invoice tersedia.
        $stats = [
            'total_invoice' => 0,
            'lunas'         => 0,
            'menunggu'      => 0,
            'pendapatan'    => 0,
        ];

        $recentInvoices = collect();

        $monthlyRevenue = array_fill(0, 12, 0);

        return view('dashboard', [
            'isAdmin'        => $isAdmin,
            'brandCount'     => $brands->count(),
            'stats'          => $stats,
            'recentInvoices' => $recentInvoices,
            'monthlyRevenue' => $monthlyRevenue,
            'notifications'  => [],
        ]);
    }
}
