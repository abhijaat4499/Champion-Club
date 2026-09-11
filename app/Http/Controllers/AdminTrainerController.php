<?php

namespace App\Http\Controllers;

use App\Models\Trainer;

class AdminTrainerController extends Controller
{
    public function index()
    {
        $trainers = Trainer::orderBy('display_order')->get();

        return view('admin.trainers.index', compact('trainers'));
    }

    public function create()
{
    return view('admin.trainers.create');
}
}