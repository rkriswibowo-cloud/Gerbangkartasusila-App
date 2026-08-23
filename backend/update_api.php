<?php

$dir = __DIR__ . '/app/Http/Controllers/Api';
if (!is_dir($dir)) mkdir($dir, 0755, true);

$controllers = [
    'AuthController.php' => <<<PHP
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request \$request)
    {
        \$validator = Validator::make(\$request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if (\$validator->fails()) {
            return response()->json(['status' => 'error', 'message' => \$validator->errors()], 422);
        }

        \$user = User::create([
            'name' => \$request->name,
            'email' => \$request->email,
            'password' => Hash::make(\$request->password),
        ]);

        \$token = \$user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'data' => \$user,
            'access_token' => \$token,
        ], 201);
    }

    public function login(Request \$request)
    {
        \$request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        \$user = User::where('email', \$request->email)->first();

        if (! \$user || ! Hash::check(\$request->password, \$user->password)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid credentials'], 401);
        }

        \$token = \$user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'data' => \$user,
            'access_token' => \$token,
        ]);
    }

    public function profile(Request \$request)
    {
        return response()->json([
            'status' => 'success',
            'data' => \$request->user(),
        ]);
    }

    public function logout(Request \$request)
    {
        \$request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ]);
    }
}
PHP,
    'DestinationController.php' => <<<PHP
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
        \$destinations = Destination::with('category', 'images')->where('status', 'published')->paginate(10);
        return response()->json(['status' => 'success', 'data' => \$destinations]);
    }

    public function show(\$id)
    {
        \$destination = Destination::with('category', 'images', 'reviews.user')->findOrFail(\$id);
        return response()->json(['status' => 'success', 'data' => \$destination]);
    }

    public function featured()
    {
        \$destinations = Destination::with('category', 'images')->where('is_featured', true)->where('status', 'published')->get();
        return response()->json(['status' => 'success', 'data' => \$destinations]);
    }

    public function byCategory(\$slug)
    {
        \$category = DestinationCategory::where('slug', \$slug)->firstOrFail();
        \$destinations = Destination::with('category', 'images')->where('category_id', \$category->id)->where('status', 'published')->paginate(10);
        return response()->json(['status' => 'success', 'data' => \$destinations, 'category' => \$category]);
    }
}
PHP,
    'EventController.php' => <<<PHP
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        \$events = Event::where('status', 'published')->orderBy('start_date', 'asc')->paginate(10);
        return response()->json(['status' => 'success', 'data' => \$events]);
    }

    public function show(\$id)
    {
        \$event = Event::findOrFail(\$id);
        return response()->json(['status' => 'success', 'data' => \$event]);
    }
}
PHP,
    'CulinaryController.php' => <<<PHP
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CulinaryPlace;
use Illuminate\Http\Request;

class CulinaryController extends Controller
{
    public function index()
    {
        \$places = CulinaryPlace::paginate(10);
        return response()->json(['status' => 'success', 'data' => \$places]);
    }

    public function show(\$id)
    {
        \$place = CulinaryPlace::findOrFail(\$id);
        return response()->json(['status' => 'success', 'data' => \$place]);
    }
}
PHP,
    'HotelController.php' => <<<PHP
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index()
    {
        \$hotels = Hotel::paginate(10);
        return response()->json(['status' => 'success', 'data' => \$hotels]);
    }

    public function show(\$id)
    {
        \$hotel = Hotel::findOrFail(\$id);
        return response()->json(['status' => 'success', 'data' => \$hotel]);
    }
}
PHP,
    'ArticleController.php' => <<<PHP
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        \$articles = Article::with('author')->orderBy('published_at', 'desc')->paginate(10);
        return response()->json(['status' => 'success', 'data' => \$articles]);
    }

    public function show(\$id)
    {
        \$article = Article::with('author')->findOrFail(\$id);
        return response()->json(['status' => 'success', 'data' => \$article]);
    }
}
PHP,
    'ReviewController.php' => <<<PHP
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request \$request)
    {
        \$reviews = Review::with('destination')->where('user_id', \$request->user()->id)->get();
        return response()->json(['status' => 'success', 'data' => \$reviews]);
    }

    public function store(Request \$request)
    {
        \$request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        \$review = Review::updateOrCreate(
            ['user_id' => \$request->user()->id, 'destination_id' => \$request->destination_id],
            ['rating' => \$request->rating, 'comment' => \$request->comment]
        );

        return response()->json(['status' => 'success', 'data' => \$review]);
    }
}
PHP,
    'FavoriteController.php' => <<<PHP
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request \$request)
    {
        \$favorites = Favorite::with('destination')->where('user_id', \$request->user()->id)->get();
        return response()->json(['status' => 'success', 'data' => \$favorites]);
    }

    public function store(Request \$request)
    {
        \$request->validate([
            'destination_id' => 'required|exists:destinations,id',
        ]);

        \$favorite = Favorite::firstOrCreate([
            'user_id' => \$request->user()->id,
            'destination_id' => \$request->destination_id,
        ]);

        return response()->json(['status' => 'success', 'data' => \$favorite]);
    }

    public function destroy(Request \$request, \$id)
    {
        \$favorite = Favorite::where('user_id', \$request->user()->id)->where('id', \$id)->firstOrFail();
        \$favorite->delete();

        return response()->json(['status' => 'success', 'message' => 'Removed from favorites']);
    }
}
PHP,
];

foreach ($controllers as $filename => $content) {
    // Note: str_replace here removes the backslashes I had to add in the array keys to escape them from the write_to_file parser (actually I don't need to add them at all). I will remove \$ completely.
    $content = str_replace('\$', '$', $content);
    file_put_contents($dir . '/' . $filename, $content);
    echo "Created $filename\n";
}

// routes/api.php
$routesContent = <<<PHP
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

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    
    Route::get('/reviews', [ReviewController::class, 'index']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy']);
});
PHP;

file_put_contents(__DIR__ . '/routes/api.php', $routesContent);
echo "Updated routes/api.php\n";

