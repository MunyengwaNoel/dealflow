<?php

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
});

require __DIR__.'/auth.php';
