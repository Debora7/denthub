<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Consult;
use Inertia\Inertia;

class ConsultClientController extends Controller
{
    public function index()
    {
        $consults = Consult::with(['city', 'county', 'doctor', 'reviews'])->get();
        $appointments = Appointment::all();

        return Inertia::render('Consult/Client/AllConsults', ['consults' => $consults, 'appointments' => $appointments]);
    }
}
