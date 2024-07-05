<?php

use Inertia\Inertia;
use App\Models\County;
use App\Models\Doctor;
use App\Models\Consult;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicController;
use App\Http\Controllers\ConsultController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ConsultClientController;

Route::get('/', function () {
    return Inertia::render('Auth/Login');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    $counties = County::with('cities')->get();
    $doctors = Doctor::all();
    return Inertia::render('Dashboard', [
        'consults' => Consult::with('city', 'county', 'user')
            ->where('user_id', $user->id)
            ->get(),
        'counties' => $counties,
        'doctors' => $doctors,
    ]);
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
        Route::delete('/delete', [ConsultController::class, 'destroy'])->name('destroy');
        Route::put('/update', [ConsultController::class, 'update'])->name('update');

        Route::prefix('medic')->name('medic.')->group(function () {
            Route::post('/store', [MedicController::class, 'store'])->name('store');
            Route::get('/index', [MedicController::class, 'index'])->name('index');
        });

        Route::prefix('client')->name('client.')->group(function () {
            Route::get('/index', [ConsultClientController::class, 'index'])->name('index');

            Route::prefix('appointment')->name('appointment.')->group(function () {
                Route::post('/store', [AppointmentController::class, 'store'])->name('store');
                Route::get('/index', [AppointmentController::class, 'index'])->name('index');
            });
        });
    });
});

require __DIR__ . '/auth.php';
