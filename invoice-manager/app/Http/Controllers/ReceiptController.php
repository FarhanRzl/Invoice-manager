<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Invoice::class);

        $user = auth()->user();

        $invoices = Invoice::lunas()
            ->with('brand')
            ->when(
                ! $user->hasRole('admin'),
                fn ($query) => $query->whereIn('brand_id', $user->brands()->pluck('brands.id'))
            )
            ->latest('tanggal_lunas')
            ->paginate(15);

        return view('receipts.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $this->authorize('viewReceipt', $invoice);

        $invoice->load('brand');

        return view('receipts.show', compact('invoice'));
    }

    public function pdf(Invoice $invoice)
    {
        $this->authorize('viewReceipt', $invoice);

        $invoice->load('brand');

        $filename = str_replace(['/', '\\'], '-', "Kwitansi-{$invoice->nomor_kwitansi}").'.pdf';

        return Pdf::loadView('receipts.pdf', ['invoice' => $invoice, 'forPdf' => true])
            ->stream($filename);
    }
}
