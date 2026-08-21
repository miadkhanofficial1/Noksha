<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page (Placeholder structure).
     */
    public function redirectToGoogle()
    {
        // Socialite architecture structure ready for client_id & client_secret configuration
        return redirect()->route('login')->with('info', 'Google OAuth structure prepared! Configure GOOGLE_CLIENT_ID & GOOGLE_CLIENT_SECRET in .env when ready. / গুগল অথেনটিকেশন স্ট্রাকচার প্রস্তুত।');
    }

    /**
     * Obtain the user information from Google (Placeholder callback).
     */
    public function handleGoogleCallback()
    {
        return redirect()->route('login');
    }
}
