<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Charge;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function charge($amount, $token)
    {
        return Charge::create([
            'amount' => $amount,
            'currency' => 'ron',
            'source' => $token,
        ]);
    }
}
