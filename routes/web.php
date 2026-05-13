<?php

use App\Http\Controllers\InvoiceDocumentController;
use App\Http\Controllers\OrderQuotePreviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicQuoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/q/{token}', [PublicQuoteController::class, 'show'])->name('quote.portal');
Route::post('/q/{token}/accept', [PublicQuoteController::class, 'accept'])->name('quote.portal.accept');
Route::post('/q/{token}/decline', [PublicQuoteController::class, 'decline'])->name('quote.portal.decline');
Route::post('/q/{token}/demo-viewed', [PublicQuoteController::class, 'demoViewed'])->name('quote.portal.demo-viewed');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/documents/invoices/{invoice}/print', [InvoiceDocumentController::class, 'print'])
        ->name('documents.invoice.print');
    Route::get('/documents/invoices/{invoice}/pdf', [InvoiceDocumentController::class, 'pdf'])
        ->name('documents.invoice.pdf');
    Route::get('/documents/invoices/{invoice}/export.csv', [InvoiceDocumentController::class, 'csv'])
        ->name('documents.invoice.csv');
    Route::get('/documents/invoices/{invoice}/export.xlsx', [InvoiceDocumentController::class, 'xlsx'])
        ->name('documents.invoice.xlsx');

    Route::get('/documents/orders/{order}/quote-preview/print', [OrderQuotePreviewController::class, 'print'])
        ->name('documents.order.quote-preview.print');
    Route::get('/documents/orders/{order}/quote-preview.pdf', [OrderQuotePreviewController::class, 'pdf'])
        ->name('documents.order.quote-preview.pdf');
    Route::get('/documents/orders/{order}/quote-preview.csv', [OrderQuotePreviewController::class, 'csv'])
        ->name('documents.order.quote-preview.csv');
    Route::get('/documents/orders/{order}/quote-preview.xlsx', [OrderQuotePreviewController::class, 'xlsx'])
        ->name('documents.order.quote-preview.xlsx');
});

require __DIR__.'/auth.php';
