<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentCancellation;
use App\Mail\AppointmentConfirmation;
use App\Mail\AppointmentCancellationMedic;
use App\Mail\AppointmentReview;

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
        $request->validate([
            'date' => 'required',
            'time' => 'required',
        ]);

        $user_id = Auth::id();
        $existingAppointments = Appointment::where('consult_id', $request->consult_id)->get();

        foreach ($existingAppointments as $appointment) {
            if (Carbon::parse($appointment->appointment_date)->format('Y-m-d H:i') == Carbon::parse($request->date)->addHours(3)->format('Y-m-d H:i')) {
                return redirect()->back()->with('error', 'Există deja o programare.');
            }
        }

        $appointment = new Appointment();
        $appointment->user_id = $user_id;
        $appointment->doctor_id = $request->doctor_id;
        $appointment->consult_id = $request->consult_id;
        $appointment->appointment_date = Carbon::parse("{$request->date} {$request->time}")->format('Y-m-d H:i');
        $appointment->appointment_date_end = Carbon::parse("{$request->date} {$request->time}")
            ->addMinutes((int)explode(':', $request->consult_time)[1])
            ->format('Y-m-d H:i');
        $appointment->status = 'Confirmată';
        $appointment->save();

        Mail::to(Auth::user()->email)->send(new AppointmentConfirmation($appointment));

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
        $doctos = Doctor::with('consults')->where('user_id', $userId)->get();
        $allAppointments = Appointment::all();

        return Inertia::render('Consult/Medic/AllAppointmentsMedic', ['appointments' => $appointments, 'doctors' => $doctos, 'allAppointments' => $allAppointments]);
    }

    public function honored(Request $request)
    {
        $appointment = Appointment::with('user')->find($request->id);
        $appointment->status = 'Onorată';

        $appointment->save();

        Mail::to($appointment->user->email)->send(new AppointmentReview($appointment));

        return redirect()->route('consult.medic.appointment.index');
    }


    public function missed(Request $request)
    {
        $appointment = Appointment::with('user')->find($request->id);

        $appointment->status = 'Anulată';
        $appointment->save();

        Mail::to($appointment->user->email)->send(new AppointmentCancellationMedic($appointment));

        return redirect()->route('consult.medic.appointment.index');
    }

    public function destroy(Request $request)
    {
        $appointment = Appointment::find($request->id);
        if ($appointment) {
            $appointment->status = 'Anulată';
            $appointment->save();
            $appointment->delete();
        }

        Mail::to(Auth::user()->email)->send(new AppointmentCancellation($appointment));

        return redirect()->route('consult.client.appointment.index');
    }
}
