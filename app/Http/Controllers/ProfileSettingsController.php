<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileSettingsController extends Controller
{
    /**
     * Show the profile settings form.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('settings.profile', compact('user'));
    }

    /**
     * Update the authenticated user's profile information.
     */
    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'username' => [
                'nullable',
                'string',
                'alpha_dash',
                'max:50',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'headline' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:30'],
            'skills' => ['nullable'],
            'social_links' => ['nullable', 'array'],
            'is_available' => ['nullable'],
            'avatar' => ['nullable', 'image', 'max:15360'],
            'cover_image' => ['nullable', 'image', 'max:20480'],
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
            $user->cover_image = $coverPath;
        }

        if ($request->filled('name')) {
            $user->name = $request->input('name');
        }

        if ($request->filled('username')) {
            $user->username = strtolower($request->input('username'));
        }

        if ($request->has('headline')) {
            $user->headline = $request->input('headline');
        }

        if ($request->has('bio')) {
            $user->bio = $request->input('bio');
        }

        if ($request->has('phone')) {
            $user->phone = $request->input('phone');
        }

        if ($request->has('skills')) {
            if (is_array($request->skills)) {
                $rawSkills = $request->skills;
            } else {
                $rawSkills = explode(',', (string) $request->skills);
            }
            $user->skills = array_values(array_unique(array_filter(array_map('trim', $rawSkills))));
        }

        if ($request->has('social_links') && is_array($request->social_links)) {
            $socialLinks = [];
            foreach ($request->social_links as $key => $link) {
                $trimmed = trim((string) $link);
                if ($trimmed !== '') {
                    $socialLinks[$key] = $trimmed;
                }
            }
            $user->social_links = $socialLinks;
        }

        if ($request->has('is_available')) {
            $user->is_available = $request->boolean('is_available', true);
        }

        $user->save();

        return back()->with('success', 'Profile and media updated successfully.');
    }

    /**
     * Alias for updateProfile to support standard update resource signatures.
     */
    public function update(Request $request)
    {
        return $this->updateProfile($request);
    }

    /**
     * Update the authenticated user's password.
     */
    public function updatePassword(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ], [
            'current_password.current_password' => 'The provided current password does not match our records.',
            'password.confirmed' => 'New password confirmation does not match.',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
