<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormOrderController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('brands', BrandController::class);

    Route::resource('admin-users', AdminUserController::class)->except(['show']);
});

Route::middleware(['auth'])->group(function () {

    // Registered ahead of the resource route, and matched via any() so it still
    // resolves when submitted from the edit form's spoofed _method=PUT body.
    Route::any('invoices/preview', [InvoiceController::class, 'preview'])->name('invoices.preview');

    Route::resource('invoices', InvoiceController::class);

    Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
    Route::post('invoices/{invoice}/lock', [InvoiceController::class, 'lock'])->name('invoices.lock');
    Route::post('invoices/{invoice}/lunas', [InvoiceController::class, 'markLunas'])->name('invoices.lunas');

    Route::resource('leads', LeadController::class);

    Route::get('receipts', [ReceiptController::class, 'index'])->name('receipts.index');
    Route::get('receipts/{invoice}', [ReceiptController::class, 'show'])->name('receipts.show');
    Route::get('receipts/{invoice}/pdf', [ReceiptController::class, 'pdf'])->name('receipts.pdf');

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

    Route::resource('form-orders', FormOrderController::class);

    Route::get('form-orders/{form_order}/pdf', [FormOrderController::class, 'pdf'])->name('form-orders.pdf');
    Route::post('form-orders/{form_order}/finalize', [FormOrderController::class, 'finalize'])->name('form-orders.finalize');

});

require __DIR__.'/auth.php';
