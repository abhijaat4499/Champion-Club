<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Support\Enquiry;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::visible()->get();

        return view('services', compact('services'));
    }

    public function show(Service $service)
    {
        abort_unless($service->is_active, 404);

        $others = Service::visible()->whereKeyNot($service->id)->take(3)->get();

        return view('service', [
            'service' => $service,
            'booking' => Enquiry::for($service),
            'others' => $others,
        ]);
    }
}
