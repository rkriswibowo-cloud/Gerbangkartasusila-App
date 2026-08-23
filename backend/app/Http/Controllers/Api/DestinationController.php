<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\DestinationCategory;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::with('category', 'images')->where('status', 'published')->paginate(10);
        return response()->json(['status' => 'success', 'data' => $destinations]);
    }

    public function show($id)
    {
        $destination = Destination::with('category', 'images', 'reviews.user')->where('slug', $id)->firstOrFail();
        return response()->json(['status' => 'success', 'data' => $destination]);
    }

    public function featured()
    {
        $destinations = Destination::with('category', 'images')->where('is_featured', true)->where('status', 'published')->get();
        return response()->json(['status' => 'success', 'data' => $destinations]);
    }

    public function byCategory($slug)
    {
        $category = DestinationCategory::where('slug', $slug)->firstOrFail();
        $destinations = Destination::with('category', 'images')->where('category_id', $category->id)->where('status', 'published')->paginate(10);
        return response()->json(['status' => 'success', 'data' => $destinations, 'category' => $category]);
    }
}