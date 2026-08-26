<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class LanguageController extends Controller
{
    /**
     * Switch application language locale (bn or en) and save in session.
     */
    public function switch(string $locale): RedirectResponse
    {
        if (in_array($locale, ['bn', 'en'])) {
            session(['locale' => $locale]);
            app()->setLocale($locale);
        }

        return redirect()->back();
    }
}
