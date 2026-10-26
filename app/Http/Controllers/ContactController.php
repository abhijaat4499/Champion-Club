<?php

namespace App\Http\Controllers;
use App\Models\Contact;
use App\Support\Enquiry;

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
            'email'    => 'nullable|required_without:phone|email|max:255',
            'phone'    => 'nullable|required_without:email|digits:10|starts_with:6,7,8,9',
            'interest' => 'nullable|in:' . implode(',', array_keys(Contact::INTERESTS)),
            'message'  => 'nullable|string|max:2000',
            'ref_type' => 'nullable|in:' . implode(',', Enquiry::TYPES),
            'ref_id'   => 'nullable|integer',
        ], [
            'phone.required_without' => 'Please share a phone number or an email so we can reach you.',
            'email.required_without' => 'Please share an email or a phone number so we can reach you.',
            'phone.digits' => 'Please enter a 10-digit mobile number.',
            'phone.starts_with' => 'Please enter a valid Indian mobile number.',
        ]);

        $enquiry = Enquiry::find($validated['ref_type'] ?? null, $validated['ref_id'] ?? null);
        unset($validated['ref_type'], $validated['ref_id']);

        if ($enquiry) {
            $validated['interest'] = $enquiry['interest'];
            $validated['subject'] = $enquiry['subject'];
        }

        Contact::create($validated);

        return redirect()->route('gym.thankyou')->with([
            'lead_submitted' => true,
            'lead_subject' => $enquiry['subject'] ?? null,
        ]);
    }

    public function thankYou()
    {
        if (! session('lead_submitted')) {
            return redirect()->route('gym.home');
        }

        return view('thank-you');
    }

}
