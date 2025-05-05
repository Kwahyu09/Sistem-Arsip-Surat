<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\IncomingLetterController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboardrole', function () {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return 'Role anda Admin';
        } elseif ($user->role === 'staff') {
            return 'Role anda Staff';
        } elseif ($user->role === 'staff_bidang') {
            return 'Role anda Staff Bidang';
        } else {
            abort(403, 'Unauthorized'); // Kalau role tidak dikenali
        }
    })->name('rolelogin');

    Route::middleware(['role:admin'])->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    //route surat masuk
    // Route::controller(IncomingLetterController::class)->group(function () {
    //     Route::get('surat-masuk', 'index')->name('incomingletter.index');    
    //     Route::get('surat-masuk/create', 'create')->name('incomingletter.create');
    //     Route::post('surat-masuk', 'store')->name('incomingletter.store');
    //     Route::get('surat-masuk/{incomingletter}', 'show')->name('incomingletter.show');
    //     Route::get('surat-masuk/{incomingletter}/edit', 'edit')->name('incomingletter.edit');
    //     Route::put('surat-masuk/{incomingletter}', 'update')->name('incomingletter.update');
    //     Route::delete('surat-masuk/{incomingletter}', 'destroy')->name('incomingletter.destroy');
    // });
    Route::resource('surat-masuk', IncomingLetterController::class, [
        'parameters' => ['surat-masuk' => 'incomingletter'] // penting: parameter ganti incomingletter
    ]);
    Route::get('surat-masuk-lihat/{slug}', [IncomingLetterController::class, 'viewFile'])->name('surat-masuk.view');
    Route::get('surat-masuk-download/{slug}', [IncomingLetterController::class, 'downloadFile'])->name('surat-masuk.download');
    
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
