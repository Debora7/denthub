<?php

namespace App\Http\Controllers;

use App\Models\Consult;
use App\Models\County;
use App\Models\Doctor;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ConsultController extends Controller
{
    public function index()
    {
        $user_id = auth()->id();
        $counties = County::with('cities')->get();
        $doctors = Doctor::where('user_id', $user_id)->get();

        return Inertia::render('Consult/NewConsult', [
            'counties' => $counties,
            'doctors' => $doctors,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor' => 'required|integer|exists:doctors,id',
            'address' => 'required|string|max:255',
            'city' => 'required|integer|exists:cities,id',
            'county' => 'required|integer|exists:counties,id',
            'services.*.service' => 'required|string|max:255',
            'services.*.description' => 'required|string',
            'services.*.price' => 'required|numeric',
            'services.*.time' => 'required|integer|min:1|max:60',
        ]);

        $user = auth()->user();
        $doctor = Doctor::find($request->doctor);

        foreach ($request->services as $serviceData) {
            // Convert minutes to HH:MM:SS format
            $hours = floor($serviceData['time'] / 60);
            $minutes = $serviceData['time'] % 60;
            $seconds = 0;
            $consultTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

            $consultData = [
                'doctor_id' => $request->doctor,
                'doctor' => $doctor->name,
                'service' => $serviceData['service'],
                'description' => $serviceData['description'],
                'price' => $serviceData['price'],
                'consult_time' => $consultTime, // Save time in HH:MM:SS format
                'address' => $request->address,
                'city_id' => $request->city,
                'county_id' => $request->county,
                'user_id' => $user->id,
            ];

            Consult::create($consultData);
        }

        return redirect()->route('dashboard');
    }

    public function destroy(Request $request)
    {
        $consult = Consult::find($request->id);
        $consult->delete();
        return redirect()->route('dashboard');
    }

    public function update(Request $request)
    {
        $request->validate([
            'doctor' => 'integer|exists:doctors,id',
            'address' => 'string|max:255',
            'city' => 'integer|exists:cities,id',
            'county' => 'integer|exists:counties,id',
            'service' => 'string|max:255',
            'description' => 'string',
            'price' => 'numeric',
            'time' => 'integer|min:1|max:60',
        ]);

        $user = auth()->user();
        $doctor = Doctor::find($request->doctor);

        $hours = floor($request->time / 60);
        $minutes = $request->time % 60;
        $seconds = 0;
        $consultTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        $consultData = [
            'doctor_id' => $request->doctor,
            'doctor' => $doctor->name,
            'service' => $request->service,
            'description' => $request->description,
            'price' => $request->price,
            'consult_time' => $consultTime,
            'address' => $request->address,
            'city_id' => $request->city,
            'county_id' => $request->county,
            'user_id' => $user->id,
        ];

        $consult = Consult::find($request->id);
        if ($consult) {
            $consult->update($consultData);
        } else {
            return redirect()->route('dashboard')->withErrors('Consult not found.');
        }

        return redirect()->route('dashboard');
    }
}
