<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $appointments = Appointment::withTrashed()
            ->where('user_id', $user->id)
            ->with([
                'consult' => function ($query) {
                    $query->with(['city', 'county']);
                }
            ])
            ->orderBy('appointment_date', 'desc')
            ->get()
            ->map(function ($appointment) {
                $appointment->status = $appointment->trashed() ? 'Anulată' : 'Confirmată';
                return $appointment;
            });

        return Inertia::render('Consult/Client/AllAppointments', ['appointments' => $appointments]);
    }


    public function store(Request $request)
    {
        $user_id = Auth::id();
        $existingAppointments = Appointment::where('consult_id', $request->consult_id)->get();

        foreach ($existingAppointments as $appointment) {
            if (Carbon::parse($appointment->appointment_date)->format('Y-m-d H:i') == Carbon::parse($request->date)->addHours(3)->format('Y-m-d H:i')) {
                return redirect()->back()->with('error', 'Există deja o programare.');
            }
        }

        $appointment = new Appointment();
        $appointment->user_id = $user_id;
        $appointment->consult_id = $request->consult_id;
        $appointment->appointment_date = Carbon::parse($request->date)->addHours(3)->format('Y-m-d H:i');
        $appointment->save();

        return redirect()->route('consult.client.appointment.index');
    }
}
