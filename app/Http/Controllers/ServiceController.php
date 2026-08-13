<?php

namespace App\Http\Controllers;

use App\Models\Commune;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        return view('pages.services', [
            'services' => Service::actifs()->get(),
        ]);
    }

    public function show(Service $service)
    {
        abort_unless($service->actif, 404);

        return view('pages.service', [
            'service' => $service,
            'communes' => Commune::actives()->get(),
        ]);
    }
}
