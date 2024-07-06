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

        $user_id = auth()->id();

        $doctor = new Doctor();
        $doctor->user_id = $user_id;
        $doctor->name = $request->doctor;
        $doctor->working_days = json_encode($request->workingDays);
        $doctor->save();

        return redirect()->route('consult.medic.allMedics');
    }

    public function allMedics()
    {
        $user_id = auth()->id();
        $doctors = Doctor::where('user_id', $user_id)->get()->map(function ($doctor) {
            $doctor->working_days_modified = json_decode($doctor->working_days);
            return $doctor;
        });

        return Inertia::render('Consult/Medic/AllMedics', ['doctors' => $doctors]);
    }
}
