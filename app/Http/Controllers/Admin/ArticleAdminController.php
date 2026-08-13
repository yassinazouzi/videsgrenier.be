<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArticleAdminController extends Controller
{
    public function index()
    {
        return view('admin.articles.index', [
            'articles' => Article::latest('cree_le')->paginate(20),
        ]);
    }

    public function create()
    {
        return view('admin.articles.form', ['article' => new Article()]);
    }

    public function store(Request $request)
    {
        Article::create($this->valider($request));

        return redirect()->route('admin.articles.index')->with('succes', 'Article créé.');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.form', ['article' => $article]);
    }

    public function update(Request $request, Article $article)
    {
        $article->update($this->valider($request, $article));

        return redirect()->route('admin.articles.index')->with('succes', 'Article mis à jour.');
    }

    public function destroy(Article $article)
    {
        if ($article->image_une && str_starts_with($article->image_une, 'storage/')) {
            Storage::disk('public')->delete(Str::after($article->image_une, 'storage/'));
        }

        $article->delete();

        return redirect()->route('admin.articles.index')->with('succes', 'Article supprimé.');
    }

    private function valider(Request $request, ?Article $article = null): array
    {
        $donnees = $request->validate([
            'titre' => ['required', 'string', 'max:190'],
            'slug' => ['nullable', 'string', 'max:190', 'alpha_dash', Rule::unique('articles', 'slug')->ignore($article?->id)],
            'extrait' => ['nullable', 'string', 'max:320'],
            'contenu' => ['nullable', 'string'],
            'categorie' => ['nullable', 'string', 'max:80'],
            'statut' => ['required', 'in:brouillon,publie'],
            'publie_le' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:190'],
            'meta_description' => ['nullable', 'string', 'max:320'],
            'image_une' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $donnees['slug'] = $donnees['slug'] ?: Str::slug($donnees['titre']);

        // Un article publié sans date ne remonterait jamais : le scope filtre sur publie_le <= now().
        if ($donnees['statut'] === 'publie' && empty($donnees['publie_le'])) {
            $donnees['publie_le'] = now();
        }

        if ($request->hasFile('image_une')) {
            if ($article?->image_une && str_starts_with($article->image_une, 'storage/')) {
                Storage::disk('public')->delete(Str::after($article->image_une, 'storage/'));
            }

            $nom = $donnees['slug'].'-'.Str::random(6).'.'.$request->file('image_une')->extension();
            $request->file('image_une')->storeAs('articles', $nom, 'public');
            $donnees['image_une'] = 'storage/articles/'.$nom;
        } else {
            unset($donnees['image_une']);
        }

        return $donnees;
    }
}
