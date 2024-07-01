<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConsultController;
use App\Http\Controllers\ProfileController;
use App\Models\Consult;

Route::get('/', function () {
    return Inertia::render('Auth/Login');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', ['consults' => Consult::with('city', 'county')->get()]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/welcome', function () {
    return Inertia::render('Welcome');
})->middleware(['auth', 'verified'])->name('welcome');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('consult')->name('consult.')->group(function () {
        Route::get('/index', [ConsultController::class, 'index'])->name('index');
        Route::post('/store', [ConsultController::class, 'store'])->name('store');
        Route::delete('/{consult}', [ConsultController::class, 'destroy'])->name('destroy');
        Route::put('/{consult}', [ConsultController::class, 'update'])->name('update');
    });
});

require __DIR__ . '/auth.php';
