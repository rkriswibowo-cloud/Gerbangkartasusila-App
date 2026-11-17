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
use Illuminate\Support\Facades\Log;

Route::matched(function () {
    Log::info('API ' . request()->method() . ' ' . request()->path() . ' from ' . request()->ip() . ' [Origin: ' . request()->header('origin') . ']');
});

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');

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

Route::get('/media/{path}', function ($path) {
    $filePath = false;
    foreach ([storage_path('app/public'), public_path('images')] as $directory) {
        $root = realpath($directory);
        $candidate = $root ? realpath($root . DIRECTORY_SEPARATOR . $path) : false;
        if ($candidate && is_file($candidate) && str_starts_with($candidate, $root . DIRECTORY_SEPARATOR)) {
            $filePath = $candidate;
            break;
        }
    }
    if (! $filePath) {
        $fallback = public_path('images/destinations/slg.png');
        if (file_exists($fallback)) {
            $filePath = $fallback;
        } else {
            abort(404);
        }
    }
    $type = mime_content_type($filePath);
    abort_unless(in_array($type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/avif']), 404);
    return response()->file($filePath, ['Content-Type' => $type, 'X-Content-Type-Options' => 'nosniff']);
})->where('path', '.*');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);
    Route::get('/tickets', [TicketController::class, 'index']);
    Route::post('/tickets', [TicketController::class, 'store']);
    Route::get('/tickets/{id}', [TicketController::class, 'show']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    
    Route::get('/reviews', [ReviewController::class, 'index']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy']);
});
