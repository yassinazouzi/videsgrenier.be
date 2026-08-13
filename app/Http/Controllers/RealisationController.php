<?php

namespace App\Http\Controllers;

use App\Models\Realisation;

class RealisationController extends Controller
{
    public function index()
    {
        return view('pages.realisations', [
            'realisations' => Realisation::publiees()->paginate(12),
        ]);
    }

    public function show(Realisation $realisation)
    {
        abort_unless($realisation->publie, 404);

        return view('pages.realisation', [
            'realisation' => $realisation,
            'autres' => Realisation::publiees()->where('id', '!=', $realisation->id)->limit(3)->get(),
        ]);
    }
}
