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

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'alpha_dash',
                'max:50',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'headline' => ['nullable', 'string', 'max:150'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:30'],
            'skills' => ['nullable'],
            'social_links' => ['nullable', 'array'],
            'social_links.behance' => ['nullable', 'url', 'max:255'],
            'social_links.dribbble' => ['nullable', 'url', 'max:255'],
            'social_links.website' => ['nullable', 'url', 'max:255'],
            'social_links.linkedin' => ['nullable', 'url', 'max:255'],
            'social_links.twitter' => ['nullable', 'url', 'max:255'],
            'social_links.github' => ['nullable', 'url', 'max:255'],
            'is_available' => ['nullable', 'boolean'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ], [
            'username.alpha_dash' => 'Username may only contain letters, numbers, dashes, and underscores.',
            'username.unique' => 'This username handle is already taken.',
            'avatar.max' => 'Avatar image must not exceed 3MB.',
            'cover_image.max' => 'Cover image must not exceed 5MB.',
        ]);

        // Process avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        // Process cover image upload
        if ($request->hasFile('cover_image')) {
            if ($user->cover_image && Storage::disk('public')->exists($user->cover_image)) {
                Storage::disk('public')->delete($user->cover_image);
            }
            $coverPath = $request->file('cover_image')->store('covers', 'public');
            $user->cover_image = $coverPath;
        }

        // Normalize skills
        $skills = [];
        if (!empty($request->skills)) {
            if (is_array($request->skills)) {
                $rawSkills = $request->skills;
            } else {
                $rawSkills = explode(',', (string) $request->skills);
            }
            $skills = array_values(array_unique(array_filter(array_map('trim', $rawSkills))));
        }

        // Clean social links
        $socialLinks = [];
        if (!empty($request->social_links) && is_array($request->social_links)) {
            foreach ($request->social_links as $key => $link) {
                $trimmed = trim((string) $link);
                if ($trimmed !== '') {
                    $socialLinks[$key] = $trimmed;
                }
            }
        }

        $user->name = $validated['name'];
        $user->username = strtolower($validated['username']);
        $user->headline = $validated['headline'] ?? null;
        $user->bio = $validated['bio'] ?? null;
        if (isset($validated['phone'])) {
            $user->phone = $validated['phone'];
        }
        $user->skills = $skills;
        $user->social_links = $socialLinks;
        $user->is_available = $request->boolean('is_available', true);
        $user->save();

        return redirect()->route('settings.profile')->with('success', 'Profile updated successfully.');
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

        return redirect()->route('settings.profile')->with('success', 'Password updated successfully.');
    }
}
