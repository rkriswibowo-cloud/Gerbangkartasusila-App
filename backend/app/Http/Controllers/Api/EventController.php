<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('status', 'published')->orderBy('start_date', 'asc')->paginate(10);
        return response()->json(['status' => 'success', 'data' => $events]);
    }

    public function show($id)
    {
        $event = Event::where('status', 'published')->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $event]);
    }
}
