<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/lang/{locale}', function ($locale) {
    session(['locale' => $locale]);

    return back();
})->name('lang.switch');

// Route::middleware(['auth'])->group(function () {
//     Route::get('/ticket', [TicketController::class, 'index'])->name('ticket.index');
//     Route::get('/tickets/create', [TicketController::class, 'create'])->name('ticket.create');
//     Route::post('/tickets', [TicketController::class, 'store'])->name('ticket.store');
//     Route::post('/ticket/{ticket}/reply', [TicketController::class, 'reply'])->name('ticket.reply');
//     Route::put('/ticket/{ticket}', [TicketController::class, 'update'])->name('ticket.update');
//     Route::delete('/ticket/{ticket}', [TicketController::class, 'destroy'])->name('ticket.destroy');
// });


ROUTE::middleware(['auth'])->group(function () {
    Route::get('/ticket', [TicketController::class, 'index'])->name('ticket.index');

    Route::get('/ticket/{ticket}/reply', [TicketController::class, 'show'])->name('ticket.reply');

    Route::get('/ticket/create', [TicketController::class, 'create'])->name('ticket.create');
    Route::post('/ticket', [TicketController::class, 'store'])->name('ticket.store');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
