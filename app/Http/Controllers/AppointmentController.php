<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $user_id = Auth::id();
        $existingAppointments = Appointment::where('consult_id', $request->consult_id)->get();

        // Check for conflicts
        foreach ($existingAppointments as $appointment) {
            if (Carbon::parse($appointment->appointment_date)->format('Y-m-d H:i') == Carbon::parse($request->date)->addHours(3)->format('Y-m-d H:i')) {
                return response()->json(['message' => 'Există deja o programare pentru această zi. Alege altă oră!'], 400);
            }
        }

        // No conflicts, create the appointment
        $appointment = new Appointment();
        $appointment->user_id = $user_id;
        $appointment->consult_id = $request->consult_id;
        $appointment->appointment_date = Carbon::parse($request->date)->addHours(3)->format('Y-m-d H:i');
        $appointment->save();

        return response()->json(['message' => 'Programarea a fost realizată cu succes!'], 200);
    }
}
