<?php


namespace App\Http\Controllers;
use App\Models\Plan;




use Illuminate\Http\Request;

class AdminPlanController extends Controller
{
      public function index()
    {
        $plans = Plan::orderBy('display_order')->get();

        return view('admin.plans.index', compact('plans'));
    }
}
