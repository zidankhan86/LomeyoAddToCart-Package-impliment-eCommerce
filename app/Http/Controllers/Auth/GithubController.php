<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GithubController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('github')
            ->scopes(['user:email', 'read:user']) // Request necessary scopes
            ->redirect();
    }

    public function callback()
    {
        try {
            // Get the GitHub user with stateless if needed
            $githubUser = Socialite::driver('github')->stateless()->user();

            // Verify we got required data
            if (!$githubUser->getEmail()) {
                // Some users hide their email, handle this case
                throw new Exception('GitHub didn\'t provide an email address');
            }

            $user = User::updateOrCreate(
                ['email' => $githubUser->getEmail()],
                [
                    'github_id' => $githubUser->getId(),
                    'name' => $githubUser->getName() ?? $githubUser->getNickname(),
                    'nickname' => $githubUser->getNickname(),
                    'email' => $githubUser->getEmail(),
                    'github_token' => $githubUser->token,
                    'github_refresh_token' => $githubUser->refreshToken, // Store refresh token if available
                    'auth_type' => 'github',
                    'password' => Hash::make(Str::random(24)),
                    'email_verified_at' => now(),
                ]
            );

            Auth::login($user, true);

            return redirect()->intended('/dashboard');

        } catch (Exception $e) {
            logger()->error('GitHub Auth Failed: ' . $e->getMessage());

            return redirect()->route('login')->withErrors([
                'github' => 'GitHub authentication failed. Please try another method.'
            ]);
        }
    }
}

