<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\StripeService;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function payment(Request $request)
    {
        $amount = $request->input('amount');
        $token = $request->input('token');

        $this->stripeService->charge($amount, $token);

        $userId = Auth::user()->id;

        $user = User::where('id', $userId)->first();
        $user->subscription = 'Plătit';
        $user->update();

        return redirect()->route('consult.medic.appointment.index');
    }
}
