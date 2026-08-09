<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrainingController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/lang/{locale}', function ($locale) {
    session(['locale' => $locale]);

    return back();
})->name('lang.switch');

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/ticket', [TicketController::class, 'index'])->name('ticket.index');

    Route::get('/ticket/{ticket}/reply', [TicketController::class, 'show'])->name('ticket.reply');

    Route::get('/ticket/create', [TicketController::class, 'create'])->name('ticket.create');
    Route::post('/ticket', [TicketController::class, 'store'])->name('ticket.store');
    Route::post('/ticket/search', [TicketController::class, 'search'])->name('ticket.search');
    Route::Post('/ticket/{ticket}/close', [TicketController::class, 'update'])->name('ticket.update');
    // ------------------------------------
    // reply
    // ------------------------------------
    Route::post('/reply/{ticket}', [ReplyController::class, 'store'])
        ->name('reply.store');

    // ------------------------------------
    // training center
    // ------------------------------------
    Route::resource('/training', TrainingController::class);

    Route::get('/training/{training}/{module}', [TrainingController::class, 'showModule'])
        ->name('training.module_show');
    // ------------------------------------
    // module
    // ------------------------------------
    Route::Post('/module', [\App\Http\Controllers\ModuleController::class, 'store'])->name('module.store');

    // ------------------------------------
    // chapter
    // ------------------------------------
    Route::Post('/chapter', [\App\Http\Controllers\ChpaterController::class, 'store'])->name('chapter.store');

    // ------------------------------------
    // users
    // ------------------------------------
    Route::get('/users', [\App\Http\Controllers\UsersController::class, 'index'])->name('users.index');
    Route::get('/users/add', [\App\Http\Controllers\UsersController::class, 'create'])->name('users.create');
    Route::post('/users', [\App\Http\Controllers\UsersController::class, 'store'])->name('users.store');
    Route::get('/user/{user}', [\App\Http\Controllers\UsersController::class, 'edit'])->name('users.edit');
    Route::put('/user/{user}', [\App\Http\Controllers\UsersController::class, 'update'])->name('users.update');
    Route::patch('/users/status/{user}', [\App\Http\Controllers\UsersController::class, 'toggleStatus'])
        ->name('users.toggle-status');

    // ------------------------------------
    // company
    // ------------------------------------
    Route::get('/company', [\App\Http\Controllers\CompanyController::class, 'index'])->name('company.index');
    Route::post('/company', [\App\Http\Controllers\CompanyController::class, 'store'])->name('company.store');
    Route::get('/company/{company}/edit', [\App\Http\Controllers\CompanyController::class, 'edit'])->name('company.edit');
    Route::put('/company/{company}', [\App\Http\Controllers\CompanyController::class, 'update'])->name('company.update');
    Route::get('/company/{company}', [\App\Http\Controllers\CompanyController::class, 'show'])->name('company.show');

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
