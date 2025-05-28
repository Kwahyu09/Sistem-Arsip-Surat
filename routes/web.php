<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncomingLetterController;
use App\Http\Controllers\OutgoingLetterController;



Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('incomingletter', IncomingLetterController::class);
});
Route::middleware(['auth'])->group(function () {
    Route::resource('outgoingletter', OutgoingLetterController::class);
});

require __DIR__ . '/auth.php';
