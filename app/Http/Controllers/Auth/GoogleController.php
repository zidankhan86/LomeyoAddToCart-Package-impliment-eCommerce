<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')
                ->scopes(['openid', 'profile', 'email'])  // Explicitly request necessary scopes
                ->redirect();
        } catch (Exception $e) {
            return redirect()->route('login')->withErrors([
                'google' => 'Unable to connect to Google. Please try again later.'
            ]);
        }
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Validate required fields
            if (!$googleUser->getEmail()) {
                throw new Exception('Google did not provide an email address');
            }

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName() ?? $googleUser->getEmail(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                    'password' => bcrypt(Str::random(24)), // Secure random password
                    'role'=>'customer',
                ]);
            } else {
                $updateData = [
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'name' => $googleUser->getName() ?? $user->name,
                    'role'=>'customer',
                ];

                // Only update role if not already set
                if (!$user->role) {
                    $updateData['role'] = 'user';
                }

                $user->update($updateData);
            }

            Auth::login($user, true); // Remember the user session

            return redirect()->intended('/'); // Use intended for safer redirects

        } catch (Exception $e) {

            return redirect()->route('login')->withErrors([
                'google' => 'Failed to login with Google. Please try again or use another method.'
            ]);
        }
    }
}
