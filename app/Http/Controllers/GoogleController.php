<?php

namespace App\Http\Controllers;
use Socialite;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class GoogleController extends Controller
{
    //
    public function redirectToGoogle()
    {
        // dd(env('GOOGLE_CLIENT_ID'));
        return Socialite::driver('google')->redirect();
    }

    // public function handleGoogleCallback()
    // {
    //    $googleUser = Socialite::driver('google')->stateless()->user();
    //     // dd($googleUser->getId());
    //     $user = User::firstOrCreate(
    //         ['email' => $googleUser->getEmail()],
    //         [
    //             'name' => $googleUser->getName(),
    //             'google_id' => $googleUser->getId(),
    //             'avatar' => $googleUser->getAvatar(),
    //             'email_verified_at' => now(), // ✅ Automatically verified
    //             'status' => 'Active',
    //             'password' => bcrypt(str_random(16)), // placeholder password
    //         ]
    //     );

    //     // ✅ Send welcome email only the first time they register
    //     if ($user->wasRecentlyCreated) {
  
    //         // Mail::to($user->email)->send(new WelcomeEmail($user));
           
    //     }

    //     Auth::login($user);

  
    //     return redirect('/')->with('success', 'Welcome, ' . $user->name . '!');
    // }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Find or create user
            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => now(),
                    'password' => bcrypt(Str::random(16)),
                    'role' => 'User',
                ]
            );

            // If existing user but no google_id yet → update it
            if (!$user->google_id) {
                $user->google_id = $googleUser->getId();
                $user->save();
            }

            $isNewUser = $user->wasRecentlyCreated;

            // Optional: send email
            if ($isNewUser) {
                // Mail::to($user->email)->send(new WelcomeEmail($user));
            }
            // Login user
            Auth::login($user);

            // Success message
            if ($isNewUser) {
                Alert::success('Welcome!', 'Your account has been created successfully!')
                    ->persistent('Dismiss');
            } else {
                Alert::success('Welcome Back!', 'Logged in successfully!')
                    ->persistent('Dismiss');
            }

            return redirect('/')->with('success', 'Welcome, ' . $user->name . '!');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Google login failed: ' . $e->getMessage());
        }
    }
}
