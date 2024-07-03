<?php

namespace App\Http\Controllers;

use App\Models\Consult;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ConsultClientController extends Controller
{
    public function index()
    {
        $consults = Consult::with(['city', 'county'])->get();
        return Inertia::render('Consult/Client/AllConsults', ['consults' => $consults]);
    }
}