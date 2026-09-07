<?php

namespace App\Http\Controllers;

use App\Models\Contact;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalQueries = Contact::count();
        $enquiries = Contact::latest()->get();

        return view('admin.dashboard', compact('totalQueries', 'enquiries'));
    }
}