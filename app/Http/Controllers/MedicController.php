<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Doctor;
use Illuminate\Http\Request;

class MedicController extends Controller
{
    public function index()
    {
        return Inertia::render('Consult/Medic/NewMedic');
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor' => 'required|string|max:255',
            'workingDays' => 'required|array',
            'workingDays.*.enabled' => 'required|boolean',
            'workingDays.*.start_time' => 'nullable|date_format:H:i',
            'workingDays.*.end_time' => 'nullable|date_format:H:i',
        ]);

        $doctor = new Doctor();
        $doctor->name = $request->doctor;
        $doctor->working_days = json_encode($request->workingDays);
        $doctor->save();

        return redirect()->route('dashboard');
    }
}
