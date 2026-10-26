<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\Admin;
use App\Support\AdminOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminAuthController extends Controller
{
    public function showLoginForm()
{
    return view('admin.login');
}

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


    $this->sendLoginCode($admin);
    session(['otp_admin_id' => $admin->id]);

    return redirect()->route('admin.otp.form');
    }



    public function showOtpForm()
{
    if (! session('otp_admin_id')) {
        return redirect()->route('admin.login');
    }

    $admin = Admin::find(session('otp_admin_id'));

    return view('admin.verify-otp', [
        'resendWait' => AdminOtp::waitSeconds($admin?->otp_expires_at),
    ]);
}

public function resendOtp()
{
    $admin = Admin::find(session('otp_admin_id'));

    if (! $admin) {
        return redirect()->route('admin.login');
    }

    if ($wait = AdminOtp::waitSeconds($admin->otp_expires_at)) {
        return redirect()->route('admin.otp.form')->withErrors(['otp' => "Please wait {$wait} seconds before asking for a new code."]);
    }

    $this->sendLoginCode($admin);

    return redirect()->route('admin.otp.form')->with('success', 'A new code has been sent to your email. The old code no longer works.');
}

private function sendLoginCode(Admin $admin): void
{
    $otp = AdminOtp::generate($admin->otp_code);

    $admin->update([
        'otp_code' => $otp,
        'otp_expires_at' => AdminOtp::expiresAt(),
    ]);

    Mail::to($admin->email)->send(new OtpMail($otp));
}


public function verifyOtp(Request $request)
{
    $request->validate(['otp' => 'required|digits:6']);

    $admin = Admin::find(session('otp_admin_id'));

    if (! $admin || ! $admin->otp_code || (string) $admin->otp_code !== $request->otp || ! $admin->otp_expires_at || $admin->otp_expires_at->isPast()) {
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

    public function showChangePasswordForm()
    {
        return view('admin.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = Auth::guard('admin')->user();

        $admin->password = Hash::make($request->password);
        $admin->save();

        return redirect()->route('admin.password.change')
            ->with('success', 'Password changed successfully.');
    }
}
