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
            'social_links.behance' => ['nullable', 'url', 'max:255'],
            'social_links.dribbble' => ['nullable', 'url', 'max:255'],
            'social_links.website' => ['nullable', 'url', 'max:255'],
            'social_links.linkedin' => ['nullable', 'url', 'max:255'],
            'social_links.twitter' => ['nullable', 'url', 'max:255'],
            'social_links.github' => ['nullable', 'url', 'max:255'],
            'is_available' => ['nullable', 'boolean'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:8192'],
        ], [
            'username.alpha_dash' => 'Username may only contain letters, numbers, dashes, and underscores.',
            'username.unique' => 'This username handle is already taken.',
            'avatar.max' => 'Avatar image must not exceed 4MB.',
            'cover_image.max' => 'Cover image must not exceed 8MB.',
        ]);

        // Handle Avatar Upload
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        // Handle Cover Image Upload
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
            $user->cover_image = $coverPath;
        }

        // Normalize skills (supports array or comma-separated string)
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
        if (!empty($validated['username'])) {
            $user->username = strtolower($validated['username']);
        }
        $user->headline = $validated['headline'] ?? $request->input('headline');
        $user->bio = $validated['bio'] ?? $request->input('bio');
        if (isset($validated['phone']) || $request->has('phone')) {
            $user->phone = $request->input('phone');
        }
        if ($request->has('skills')) {
            $user->skills = $skills;
        }
        if ($request->has('social_links')) {
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
