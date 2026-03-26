<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\User;
use App\PasswordOtp;
use App\Mail\PasswordOtpMail;
use Mail;
use Carbon\Carbon;

class ForgotPasswordOtpController extends Controller
{
    // STEP 1: SEND OTP
    public function sendOtp(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
        ]);

        $record = PasswordOtp::where('email', $request->email)->first();

        // Rate limit 60 sec
        if ($record && $record->last_sent_at && Carbon::now()->diffInSeconds($record->last_sent_at) < 60) {
            return back()->withErrors(['email' => 'Please wait before requesting another OTP.']);
        }

        $otp = rand(100000, 999999);
        // $otp = Hash::make($plainOtp);

        if ($record) {
            $record->update([
                'otp' => $otp,
                'attempts' => 0,
                'expires_at' => Carbon::now()->addMinutes(5),
                'last_sent_at' => Carbon::now()
            ]);
        } else {
            PasswordOtp::create([
                'email' => $request->email,
                'otp' => $otp,
                'attempts' => 0,
                'expires_at' => Carbon::now()->addMinutes(5),
                'last_sent_at' => Carbon::now()
            ]);
        }

        Mail::to($request->email)->send(new PasswordOtpMail($otp));

        session(['reset_email' => $request->email]);

        return redirect()->route('password.otp')->with('status', 'OTP sent to your email.');
    }

    // STEP 2: VERIFY OTP
    public function verifyOtp(Request $request)
    {
        $this->validate($request, [
            'otp' => 'required|digits:6'
        ]);

        $email = session('reset_email');
        if (!$email) return redirect()->route('password.request');

        $otpRecord = PasswordOtp::where('email', $email)->first();

        if (!$otpRecord || Carbon::now()->gt($otpRecord->expires_at)) {
            return back()->withErrors(['otp' => 'OTP expired.']);
        }

        if ($otpRecord->attempts >= 5) {
            return back()->withErrors(['otp' => 'Too many attempts. Request a new OTP.']);
        }

        if ((string)$otpRecord->otp !== (string)$request->otp) {
            $otpRecord->increment('attempts');
            return back()->withErrors(['otp' => 'Invalid OTP.']);
        }

        session(['otp_verified' => true]);

        return redirect()->route('password.customreset');
    }

    // STEP 3: RESET PASSWORD
    public function resetPassword(Request $request)
    {
        if (!session('otp_verified') || !session('reset_email')) {
            abort(403);
        }

        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::where('email', session('reset_email'))->first();

        if (!$user) {
            return back()->withErrors(['password' => 'User not found']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        PasswordOtp::where('email', session('reset_email'))->delete();

        session()->forget(['reset_email', 'otp_verified']);

        return redirect()->route('login')
            ->with('success', 'Password reset successfully!');
    }
}
