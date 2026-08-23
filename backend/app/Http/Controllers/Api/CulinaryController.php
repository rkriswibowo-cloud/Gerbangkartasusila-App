<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CulinaryPlace;
use Illuminate\Http\Request;

class CulinaryController extends Controller
{
    public function index()
    {
        $places = CulinaryPlace::paginate(10);
        return response()->json(['status' => 'success', 'data' => $places]);
    }

    public function show($id)
    {
        $place = CulinaryPlace::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $place]);
    }
}