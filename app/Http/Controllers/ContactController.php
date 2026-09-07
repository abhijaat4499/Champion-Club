<?php

namespace App\Http\Controllers;
use App\Models\Contact;

use Illuminate\Http\Request;


class ContactController extends Controller
{
    public function index(){
        return view ('contact-us');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255',
            'phone'    => 'nullable|digits:10|starts_with:6,7,8,9',
            'interest' => 'nullable|string',
            'message'  => 'required|string',
        ]);

        Contact::create($validated);

        return redirect()->route('gym.contact')->with('success', 'Thanks! We will get back to you soon.');
    }

}
