<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::with('destination')->where('user_id', $request->user()->id)->get();
        return response()->json(['status' => 'success', 'data' => $reviews]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        $review = Review::updateOrCreate(
            ['user_id' => $request->user()->id, 'destination_id' => $request->destination_id],
            ['rating' => $request->rating, 'comment' => $request->comment]
        );

        return response()->json(['status' => 'success', 'data' => $review]);
    }
}