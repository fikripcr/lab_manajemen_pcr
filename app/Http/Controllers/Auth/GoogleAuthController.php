<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if the user already exists in our database
            $existingUser = User::where('email', $googleUser->email)->first();

            if ($existingUser) {
                // If user exists, log them in
                Auth::login($existingUser);
            }

            // Redirect based on user role or status
            if (Auth::check()) {
                // Redirect to dashboard or intended location
                return redirect()->intended(route('dashboard'));
            }

            return redirect()->route('login')->with('error', 'Authentication failed.');
        } catch (\Exception $e) {
            // Handle any errors during authentication
            return redirect()->route('login')->withErrors(['error' => 'Google authentication failed: ' . $e->getMessage()]);
        }
    }
}
