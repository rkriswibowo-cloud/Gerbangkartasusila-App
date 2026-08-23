<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('author')->orderBy('published_at', 'desc')->paginate(10);
        return response()->json(['status' => 'success', 'data' => $articles]);
    }

    public function show($id)
    {
        $article = Article::with('author')->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $article]);
    }
}