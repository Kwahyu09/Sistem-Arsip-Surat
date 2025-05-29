<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncomingLetterController;
use App\Http\Controllers\OutgoingLetterController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect('/login');
});

// Dashboard, hanya 1 route saja, middleware auth + verified
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Profile routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
});

// Resource routes for incoming and outgoing letters with auth middleware
Route::middleware(['auth'])->group(function () {
    Route::resource('incomingletter', IncomingLetterController::class);
    Route::resource('outgoingletter', OutgoingLetterController::class);
});

// Incoming Letter routes with role-based access control
Route::middleware(['auth'])->group(function () {

    // Admin & Staff can create new incoming letters
    Route::middleware('role:admin,staff')->group(function () {
        Route::get('/incomingletter/create', [IncomingLetterController::class, 'create'])->name('incomingletter.create');
        Route::post('/incomingletter', [IncomingLetterController::class, 'store'])->name('incomingletter.store');
    });

    // Admin & Staff Bidang can mark as read and update disposition
    Route::middleware('role:admin,staff_bidang')->group(function () {
        Route::patch('/incomingletter/{slug}/mark-read', [IncomingLetterController::class, 'markRead'])->name('incomingletter.markRead');
        Route::patch('/incomingletter/{slug}/update-disposition', [IncomingLetterController::class, 'updateDisposition'])->name('incomingletter.updateDisposition');
    });
Route::patch('incomingletter/{slug}/mark-read', [IncomingLetterController::class, 'markRead'])->name('incomingletter.markRead');
Route::patch('incomingletter/{slug}/update-disposition', [IncomingLetterController::class, 'updateDisposition'])->name('incomingletter.updateDisposition');
Route::get('incomingletter/{slug}/view', [IncomingLetterController::class, 'viewFile'])->name('incomingletter.viewFile');
Route::get('incomingletter/{slug}/download', [IncomingLetterController::class, 'downloadFile'])->name('incomingletter.downloadFile');

    // Admin can edit and delete incoming letters
    Route::middleware('role:admin')->group(function () {
        Route::get('/incomingletter/{slug}/edit', [IncomingLetterController::class, 'edit'])->name('incomingletter.edit');
        Route::put('/incomingletter/{slug}', [IncomingLetterController::class, 'update'])->name('incomingletter.update');
        Route::delete('/incomingletter/{slug}', [IncomingLetterController::class, 'destroy'])->name('incomingletter.destroy');
    });

    // Everyone authenticated can view list of incoming letters
    Route::get('/incomingletter', [IncomingLetterController::class, 'index'])->name('incomingletter.index');
});

// Outgoing Letter routes with role-based access control
Route::middleware(['auth'])->group(function () {

    // Admin & Staff can create new outgoing letters
    Route::middleware('role:admin,staff')->group(function () {
        Route::get('/outgoingletter/create', [OutgoingLetterController::class, 'create'])->name('outgoingletter.create');
        Route::post('/outgoingletter', [OutgoingLetterController::class, 'store'])->name('outgoingletter.store');
    });

    // Admin can edit and delete outgoing letters
    Route::middleware('role:admin')->group(function () {
        Route::get('/outgoingletter/{slug}/edit', [OutgoingLetterController::class, 'edit'])->name('outgoingletter.edit');
        Route::put('/outgoingletter/{slug}', [OutgoingLetterController::class, 'update'])->name('outgoingletter.update');
        Route::delete('/outgoingletter/{slug}', [OutgoingLetterController::class, 'destroy'])->name('outgoingletter.destroy');
    });

    // Everyone authenticated can view list of outgoing letters
    Route::get('/outgoingletter', [OutgoingLetterController::class, 'index'])->name('outgoingletter.index');
});

require __DIR__ . '/auth.php';
