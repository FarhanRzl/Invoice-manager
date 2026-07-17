<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Services\RekapService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request, RekapService $rekap)
    {
        $this->authorize('viewAny', Invoice::class);

        $user = auth()->user();

        $tab = in_array($request->query('tab'), ['bulanan', 'tahunan', 'brand', 'leads'], true)
            ? $request->query('tab')
            : 'bulanan';

        $year = (int) $request->query('year', now()->year);

        $data = match ($tab) {
            'tahunan' => [
                'yearlyTotals' => $rekap->yearlyTotals($user),
            ],
            'brand' => [
                'revenueByBrand' => $rekap->revenueByBrand($user),
                'leadsByBrand' => $rekap->leadsByBrand($user),
            ],
            'leads' => [
                'leadsByStatus' => $rekap->leadsByStatus($user),
                'leadsBySumber' => $rekap->leadsBySumber($user),
                'leadsMonthlyTrend' => $rekap->leadsMonthlyTrend($user, $year),
            ],
            default => [
                'monthlyRevenue' => $rekap->monthlyRevenue($user, $year),
                'monthlyLeadsDeals' => $rekap->monthlyLeadsDeals($user, $year),
            ],
        };

        return view('reports.index', compact('tab', 'year', 'data'));
    }
}
