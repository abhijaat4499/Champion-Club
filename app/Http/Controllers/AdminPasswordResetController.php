<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\Admin;
use App\Support\AdminOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminPasswordResetController extends Controller
{
    public function showEmailForm()
    {
        return view('admin.forgot-password');
    }

    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            return back()
                ->withErrors(['email' => 'This email does not exist.'])
                ->withInput();
        }

        $this->sendResetCode($admin);

        session(['reset_email' => $admin->email]);
        session()->forget('reset_verified');

        return redirect()->route('admin.password.otp')
            ->with('success', 'A code has been sent to your email.');
    }

    public function showOtpForm()
    {
        if (!session('reset_email')) {
            return redirect()->route('admin.password.request');
        }

        $admin = Admin::where('email', session('reset_email'))->first();

        return view('admin.reset-otp', [
            'resendWait' => AdminOtp::waitSeconds($admin?->reset_otp_expires_at),
        ]);
    }

    public function resendCode()
    {
        $admin = session('reset_email') ? Admin::where('email', session('reset_email'))->first() : null;

        if (! $admin) {
            return redirect()->route('admin.password.request');
        }

        if ($wait = AdminOtp::waitSeconds($admin->reset_otp_expires_at)) {
            return redirect()->route('admin.password.otp')->withErrors(['otp' => "Please wait {$wait} seconds before asking for a new code."]);
        }

        $this->sendResetCode($admin);

        return redirect()->route('admin.password.otp')->with('success', 'A new code has been sent to your email. The old code no longer works.');
    }

    private function sendResetCode(Admin $admin): void
    {
        $otp = AdminOtp::generate($admin->reset_otp_code);

        $admin->reset_otp_code = $otp;
        $admin->reset_otp_expires_at = AdminOtp::expiresAt();
        $admin->save();

        Mail::to($admin->email)->send(new OtpMail($otp, 'Reset Password'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $email = session('reset_email');

        if (!$email) {
            return redirect()->route('admin.password.request');
        }

        $admin = Admin::where('email', $email)->first();

        if (
            !$admin ||
            (string) $admin->reset_otp_code !== $request->otp ||
            !$admin->reset_otp_expires_at ||
            $admin->reset_otp_expires_at->isPast()
        ) {
            return back()->withErrors(['otp' => 'Invalid or expired code.']);
        }

        $admin->reset_otp_code = null;
        $admin->reset_otp_expires_at = null;
        $admin->save();

        session(['reset_verified' => true]);

        return redirect()->route('admin.password.reset');
    }

    public function showResetForm()
    {
        if (!session('reset_email') || !session('reset_verified')) {
            return redirect()->route('admin.password.request');
        }

        return view('admin.reset-password');
    }

    public function resetPassword(Request $request)
    {
        if (!session('reset_email') || !session('reset_verified')) {
            return redirect()->route('admin.password.request');
        }

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $admin = Admin::where('email', session('reset_email'))->first();

        if (!$admin) {
            return redirect()->route('admin.password.request');
        }

        $admin->password = Hash::make($request->password);
        $admin->save();

        session()->forget(['reset_email', 'reset_verified']);

        return redirect()->route('admin.login')
            ->with('success', 'Password updated. You can now log in.');
    }
}