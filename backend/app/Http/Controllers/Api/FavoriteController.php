<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favorites = Favorite::with('destination')->where('user_id', $request->user()->id)->get();
        return response()->json(['status' => 'success', 'data' => $favorites]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'destination_id' => 'required|exists:destinations,id',
        ]);

        $favorite = Favorite::firstOrCreate([
            'user_id' => $request->user()->id,
            'destination_id' => $request->destination_id,
        ]);

        return response()->json(['status' => 'success', 'data' => $favorite]);
    }

    public function destroy(Request $request, $id)
    {
        $favorite = Favorite::where('user_id', $request->user()->id)->where('id', $id)->firstOrFail();
        $favorite->delete();

        return response()->json(['status' => 'success', 'message' => 'Removed from favorites']);
    }
}