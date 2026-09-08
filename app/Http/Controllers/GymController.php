<?php

namespace App\Http\Controllers;

use App\Models\Plan;

class GymController extends Controller
{
    public function index()
    {
        $plans = Plan::orderBy('display_order')->get();

        return view('home_page', compact('plans'));
    }
}
