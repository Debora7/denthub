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
            ->get();

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
        $appointment->status = 'Confirmată';
        $appointment->save();

        return redirect()->route('consult.client.appointment.index');
    }

    public function appointmentIndex()
    {
        $userId = auth()->id();
        $appointments = Appointment::whereHas('consult', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->where('status', 'Confirmată')
            ->with(['consult', 'user'])
            ->orderBy('appointment_date')
            ->get();
        return Inertia::render('Consult/Medic/AllAppointmentsMedic', ['appointments' => $appointments]);
    }

    public function honored(Request $request)
    {
        $appointment = Appointment::find($request->id);
        $appointment->status = 'Onorată';

        $appointment->save();

        return redirect()->route('consult.medic.appointment.index');
    }


    public function missed(Request $request)
    {
        $appointment = Appointment::find($request->id);
        $appointment->status = 'Anulată';

        $appointment->save();

        return redirect()->route('consult.medic.appointment.index');
    }

    public function destroy(Request $request)
    {
        $appointment = Appointment::find($request->id);
        if ($appointment) {
            $appointment->status = 'Anulată';
            $appointment->save();
            $appointment->delete(); // Perform the soft delete after saving the status change
        }
        return redirect()->route('consult.client.appointment.index');
    }
}
