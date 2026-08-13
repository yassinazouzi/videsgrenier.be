<?php

namespace App\Http\Controllers;

use App\Models\Article;

class BlogController extends Controller
{
    public function index()
    {
        return view('pages.blog', [
            'articles' => Article::publies()->paginate(10),
        ]);
    }

    public function show(Article $article)
    {
        abort_unless($article->statut === 'publie' && $article->publie_le?->isPast(), 404);

        return view('pages.article', [
            'article' => $article,
            'autres' => Article::publies()->where('id', '!=', $article->id)->limit(3)->get(),
        ]);
    }
}
