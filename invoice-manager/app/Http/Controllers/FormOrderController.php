<?php

namespace App\Http\Controllers;

use App\Actions\FormOrder\CreateFormOrderAction;
use App\Actions\FormOrder\DeleteFormOrderAction;
use App\Actions\FormOrder\UpdateFormOrderAction;
use App\Http\Requests\StoreFormOrderRequest;
use App\Http\Requests\UpdateFormOrderRequest;
use App\Models\Brand;
use App\Models\FormOrder;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class FormOrderController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', FormOrder::class);

        $user = auth()->user();

        $formOrders = FormOrder::with('brand')
            ->when(
                ! $user->hasRole('admin'),
                fn ($query) => $query->whereIn('brand_id', $user->brands()->pluck('brands.id'))
            )
            ->when(
                $user->hasRole('admin') && $request->filled('brand_id'),
                fn ($query) => $query->where('brand_id', $request->input('brand_id'))
            )
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where('status', $request->input('status'))
            )
            ->latest('tanggal_order')
            ->paginate(15)
            ->withQueryString();

        $brands = $this->brandsForUser();

        return view('form-orders.index', compact('formOrders', 'brands'));
    }

    public function create()
    {
        $this->authorize('create', FormOrder::class);

        $brands = $this->brandsForUser();
        $invoices = $this->invoicesForLingkupPrefill($brands);

        return view('form-orders.create', compact('brands', 'invoices'));
    }

    public function store(StoreFormOrderRequest $request, CreateFormOrderAction $action)
    {
        $this->authorize('create', FormOrder::class);

        $formOrder = $action->execute($request->validated(), auth()->id());

        return redirect()
            ->route('form-orders.show', $formOrder)
            ->with('success', 'Form Order berhasil dibuat.');
    }

    public function show(FormOrder $form_order)
    {
        $this->authorize('view', $form_order);

        $form_order->load('brand', 'creator', 'invoice', 'images');

        return view('form-orders.show', ['formOrder' => $form_order]);
    }

    public function edit(FormOrder $form_order)
    {
        $this->authorize('update', $form_order);

        $form_order->load('images');

        $brands = $this->brandsForUser();
        $invoices = $this->invoicesForLingkupPrefill($brands);

        return view('form-orders.edit', ['formOrder' => $form_order, 'brands' => $brands, 'invoices' => $invoices]);
    }

    public function update(UpdateFormOrderRequest $request, FormOrder $form_order, UpdateFormOrderAction $action)
    {
        $this->authorize('update', $form_order);

        $action->execute($form_order, $request->validated());

        return redirect()
            ->route('form-orders.show', $form_order)
            ->with('success', 'Form Order berhasil diperbarui.');
    }

    public function destroy(FormOrder $form_order, DeleteFormOrderAction $action)
    {
        $this->authorize('delete', $form_order);

        $action->execute($form_order);

        return redirect()
            ->route('form-orders.index')
            ->with('success', 'Form Order berhasil dihapus.');
    }

    public function pdf(FormOrder $form_order)
    {
        $this->authorize('view', $form_order);

        $form_order->load('brand', 'images');

        $filename = str_replace(['/', '\\'], '-', "FormOrder-{$form_order->nomor}").'.pdf';

        return Pdf::loadView('form-orders.pdf', ['formOrder' => $form_order, 'forPdf' => true])
            ->stream($filename);
    }

    public function finalize(FormOrder $form_order)
    {
        $this->authorize('update', $form_order);

        $form_order->update(['status' => 'selesai']);

        return redirect()
            ->route('form-orders.show', $form_order)
            ->with('success', 'Form Order ditandai selesai.');
    }

    private function brandsForUser(): Collection
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return Brand::orderBy('name')->get();
        }

        return $user->brands()->orderBy('name')->get();
    }

    private function invoicesForLingkupPrefill(Collection $brands): Collection
    {
        return Invoice::with('items.subItems')
            ->whereIn('brand_id', $brands->pluck('id'))
            ->latest()
            ->get()
            ->map(function (Invoice $invoice) {
                $descriptions = [];

                foreach ($invoice->items as $item) {
                    if ($item->parent_item_id) {
                        continue;
                    }

                    $descriptions[] = $item->deskripsi;

                    if ($item->type === 'group') {
                        foreach ($item->subItems as $sub) {
                            $descriptions[] = $sub->deskripsi;
                        }
                    }
                }

                return [
                    'id' => $invoice->id,
                    'brand_id' => $invoice->brand_id,
                    'label' => $invoice->nomor.' — '.$invoice->klien,
                    'items' => $descriptions,
                ];
            })
            ->values();
    }
}
