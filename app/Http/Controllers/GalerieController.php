<?php

namespace App\Http\Controllers;

use App\Models\Galerie;

class GalerieController extends Controller
{
    public function index()
    {
        return view('pages.galeries', [
            'galeries' => Galerie::publiees()->withCount('photos')->get(),
        ]);
    }

    public function show(Galerie $galerie)
    {
        abort_unless($galerie->publie, 404);

        return view('pages.galerie', [
            'galerie' => $galerie->load('photos'),
            'autres' => Galerie::publiees()->where('id', '!=', $galerie->id)->limit(3)->get(),
        ]);
    }
}
