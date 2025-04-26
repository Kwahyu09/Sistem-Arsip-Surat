<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
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
    
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
