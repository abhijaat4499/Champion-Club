<?php

namespace App\Http\Controllers;

use App\Models\Plan;

class MembershipController extends Controller
{
    public function index()
    {
        $plans = Plan::orderBy('display_order')->get();

        return view('membership', compact('plans'));
    }
}