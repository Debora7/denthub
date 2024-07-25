<?php

use Inertia\Inertia;
use App\Models\County;
use App\Models\Doctor;
use App\Models\Review;
use App\Models\Consult;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicController;
use App\Http\Controllers\ConsultController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ConsultClientController;

Route::get('/', function () {
    return Inertia::render('Auth/Login');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    $counties = County::with('cities')->get();
    $doctors = Doctor::where('user_id', $user->id)->get();

    return Inertia::render('Dashboard', [
        'consults' => Consult::with('city', 'county', 'user', 'reviews')
            ->where('user_id', $user->id)
            ->get(),
        'counties' => $counties,
        'doctors' => $doctors,
    ]);
})->middleware(['auth'])->name('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/welcome', function () {
    return Inertia::render('Welcome');
})->middleware(['auth'])->name('welcome');
// })->middleware(['auth', 'verified'])->name('welcome');

Route::middleware(['auth'])->group(function () {
// Route::middleware(['auth', 'verified'])->group(function () {
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
            Route::post('/consult-store', [MedicController::class, 'consultStore'])->name('consult.store');
            Route::get('/index', [MedicController::class, 'index'])->name('index');
            Route::put('/update', [MedicController::class, 'update'])->name('update');
            Route::get('/allMedics', [MedicController::class, 'allMedics'])->name('allMedics');
            Route::delete('/delete', [MedicController::class, 'destroy'])->name('destroy');
            Route::get('/appointment-index', [AppointmentController::class, 'appointmentIndex'])->name('appointment.index');
            Route::post('/honored', [AppointmentController::class, 'honored'])->name('honored');
            Route::post('/missed', [AppointmentController::class, 'missed'])->name('missed');
        });

        Route::prefix('client')->name('client.')->group(function () {
            Route::get('/index', [ConsultClientController::class, 'index'])->name('index');

            Route::prefix('appointment')->name('appointment.')->group(function () {
                Route::post('/store', [AppointmentController::class, 'store'])->name('store');
                Route::get('/index', [AppointmentController::class, 'index'])->name('index');
                Route::post('/delete', [AppointmentController::class, 'destroy'])->name('destroy');

                Route::prefix('review')->name('review.')->group(function () {
                    Route::post('/store', [ReviewsController::class, 'store'])->name('store');
                });
            });
        });
    });
});

require __DIR__ . '/auth.php';
