<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Doctor;
use App\Models\Appointment;
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

    public function update(Request $request)
    {
        $request->validate([
            'doctor' => 'string|max:255',
            'workingDays' => 'array',
            'workingDays.*.enabled' => 'required|boolean',
            'workingDays.*.start_time' => 'nullable|date_format:H:i',
            'workingDays.*.end_time' => 'nullable|date_format:H:i',
        ]);

        $doctor = Doctor::find($request->id);
        $doctor->name = $request->doctor;
        $doctor->working_days = json_encode($request->workingDays);

        $doctor->update();

        return redirect()->route('consult.medic.allMedics');
    }

    public function destroy(Request $request)
    {
        $doctor = Doctor::find($request->id);
        $doctor->delete();
        return redirect()->route('consult.medic.allMedics');
    }

    public function consultStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'doctor' => 'required|exists:doctors,id',
            'consult' => 'required|exists:consults,id',
            'date' => 'required',
            'time' => 'required',
        ]);

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone ? $request->phone : null;
        $user->password = '12345678';

        $user->save();

        $userId = $user->id;

        $appointment = new Appointment();

        $appointment->doctor_id = $request->doctor;
        $appointment->consult_id  = $request->consult;
        $appointment->user_id  = $userId;
        $appointment->status  = 'Confirmată';
        $appointment->appointment_date = Carbon::parse("{$request->date} {$request->time}")->format('Y-m-d H:i');
        $appointment->appointment_date_end = Carbon::parse("{$request->date} {$request->time}")
            ->addMinutes((int)explode(':', $request->consult_time)[1])
            ->format('Y-m-d H:i');

        $appointment->save();

        return redirect()->route('consult.medic.appointment.index');
    }
}
