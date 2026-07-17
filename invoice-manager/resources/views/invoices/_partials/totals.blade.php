<dl class="w-full sm:w-64 space-y-1 text-sm ml-auto">
    <div class="flex justify-between">
        <dt class="text-slate-500">Subtotal</dt>
        <dd class="font-medium text-slate-700">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</dd>
    </div>
    <div class="flex justify-between">
        <dt class="text-slate-500">Diskon</dt>
        <dd class="text-slate-700">{{ rtrim(rtrim($invoice->diskon_persen, '0'), '.') }}%</dd>
    </div>
    <div class="flex justify-between">
        <dt class="text-slate-500">PPN</dt>
        <dd class="text-slate-700">{{ rtrim(rtrim($invoice->ppn_persen, '0'), '.') }}%</dd>
    </div>
    <div class="flex justify-between text-base font-bold text-navy-600 pt-1 border-t border-slate-200">
        <dt>Total</dt>
        <dd>Rp {{ number_format($invoice->total, 0, ',', '.') }}</dd>
    </div>
</dl>
