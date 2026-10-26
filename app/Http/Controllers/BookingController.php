<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Plan;
use App\Models\Service;
use App\Models\Trainer;
use App\Support\Enquiry;

class BookingController extends Controller
{
    public function plan(Plan $plan)
    {
        return view('book', ['booking' => Enquiry::for($plan)]);
    }

    public function offer(Offer $offer)
    {
        abort_unless($offer->isLive(), 404);

        return view('book', ['booking' => Enquiry::for($offer)]);
    }

    public function service(Service $service)
    {
        abort_unless($service->is_active, 404);

        return view('book', ['booking' => Enquiry::for($service)]);
    }

    public function trainer(Trainer $trainer)
    {
        return view('book', ['booking' => Enquiry::for($trainer)]);
    }
}
