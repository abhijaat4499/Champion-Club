<?php

namespace App\Http\Controllers;

use App\Models\Offer;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::visible()->get();

        return view('offers', compact('offers'));
    }
}
