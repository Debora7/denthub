<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Consult;
use App\Models\County;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ConsultController extends Controller
{
    public function index()
    {
        $counties = County::with('cities')->get();

        return Inertia::render('Consult/NewConsult', [
            'counties' => $counties,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|integer|exists:cities,id',
            'county' => 'required|integer|exists:counties,id',
            'services.*.service' => 'required|string|max:255',
            'services.*.description' => 'required|string',
            'services.*.price' => 'required|numeric',
        ]);

        $user = auth()->user();

        foreach ($request->services as $serviceData) {
            $consultData = [
                'doctor' => $request->doctor,
                'service' => $serviceData['service'],
                'description' => $serviceData['description'],
                'price' => $serviceData['price'],
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
            'doctor' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|integer|exists:cities,id',
            'county' => 'required|integer|exists:counties,id',
            'service' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $user = auth()->user();

        $consultData = [
            'doctor' => $request->doctor,
            'service' => $request->service,
            'description' => $request->description,
            'price' => $request->price,
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
