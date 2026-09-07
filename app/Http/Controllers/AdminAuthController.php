<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminAuthController extends Controller
//login page kholne ke lie
{
    public function showLoginForm()
{
    return view('admin.login');
}

//login karte time saare steps
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);
     $admin = Admin::where('email', $credentials['email'])->first();

        if (! $admin || ! Hash::check($credentials['password'], $admin->password)) {
            return back()->withErrors(['email' => 'Invalid email or password.'])->onlyInput('email');
        }


        $otp = rand(100000, 999999);

    $admin->update([
        'otp_code' => $otp,
        'otp_expires_at' => now()->addMinutes(10),
    ]);

    Mail::to($admin->email)->send(new OtpMail($otp));
    session(['otp_admin_id' => $admin->id]);

    return redirect()->route('admin.otp.form');
    }



    public function showOtpForm()
{
    if (! session('otp_admin_id')) {
        return redirect()->route('admin.login');
    }

    return view('admin.verify-otp');
}


public function verifyOtp(Request $request)
{
    $request->validate(['otp' => 'required|digits:6']);

    $admin = Admin::find(session('otp_admin_id'));

    if (! $admin || $admin->otp_code != $request->otp || now()->greaterThan($admin->otp_expires_at)) {
        return back()->withErrors(['otp' => 'Invalid or expired code.']);
    }

    $admin->update(['otp_code' => null, 'otp_expires_at' => null]);

    Auth::guard('admin')->login($admin);

    session()->forget('otp_admin_id');

    return redirect()->route('admin.dashboard');
}



 public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }


}
