<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::paginate(10);
        return response()->json(['status' => 'success', 'data' => $hotels]);
    }

    public function show($id)
    {
        $hotel = Hotel::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $hotel]);
    }
}