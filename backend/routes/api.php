<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DestinationController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\CulinaryController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\TicketController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/destinations', [DestinationController::class, 'index']);
Route::get('/destinations/featured', [DestinationController::class, 'featured']);
Route::get('/destinations/category/{slug}', [DestinationController::class, 'byCategory']);
Route::get('/destinations/{id}', [DestinationController::class, 'show']);

Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'show']);

Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{id}', [ArticleController::class, 'show']);

Route::get('/hotels', [HotelController::class, 'index']);
Route::get('/hotels/{id}', [HotelController::class, 'show']);

Route::get('/culinary', [CulinaryController::class, 'index']);
Route::get('/culinary/{id}', [CulinaryController::class, 'show']);

Route::get('/tickets', [TicketController::class, 'index']);
Route::post('/tickets', [TicketController::class, 'store']);
Route::get('/tickets/{id}', [TicketController::class, 'show']);

Route::get('/media/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        $filePath = public_path($path);
    }
    if (!file_exists($filePath)) {
        return response()->json(['error' => 'File not found: ' . $path], 404);
    }
    $file = file_get_contents($filePath);
    $type = mime_content_type($filePath);
    return response($file, 200)
        ->header('Content-Type', $type)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, OPTIONS');
})->where('path', '.*');

Route::post('/profile/update', [AuthController::class, 'updateProfile']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    
    Route::get('/reviews', [ReviewController::class, 'index']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy']);
});