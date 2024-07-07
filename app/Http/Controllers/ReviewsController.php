<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    public function store(Request $request)
    {
        $review = new Review();

        $review->consult_id = $request->consult_id;
        $review->rating = $request->rating;
        $review->experience = $request->review;

        $review->save();

        return redirect()->route('consult.client.appointment.index');
    }
}
