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
            'service' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'address' => 'required|string|max:255',
            'city' => 'required|integer|exists:cities,id',
            'county' => 'required|integer|exists:counties,id',
        ]);

        $consultData = [
            'doctor' => $request->doctor,
            'service' => $request->service,
            'description' => $request->description,
            'price' => $request->price,
            'address' => $request->address,
            'city_id' => $request->city,
            'county_id' => $request->county,
        ];

        Consult::create($consultData);

        return redirect()->route('dashboard');
    }
}
