<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the profile edit page.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('settings.profile', compact('user'));
    }

    /**
     * Update the authenticated user's profile and media.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
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

        // Handle Avatar File Upload: store to avatars in public disk
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        // Handle Cover Image File Upload: store to covers in public disk
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
            $user->cover_image = $coverPath;
        }

        // Normalize skills (supports array or comma-separated string)
        if ($request->has('skills')) {
            if (is_array($request->skills)) {
                $rawSkills = $request->skills;
            } else {
                $rawSkills = explode(',', (string) $request->skills);
            }
            $user->skills = array_values(array_unique(array_filter(array_map('trim', $rawSkills))));
        }

        // Normalize social links
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

        $user->name = $validated['name'];
        if (!empty($validated['username'])) {
            $user->username = strtolower($validated['username']);
        }
        if ($request->has('headline')) {
            $user->headline = $validated['headline'] ?? $request->input('headline');
        }
        if ($request->has('bio')) {
            $user->bio = $validated['bio'] ?? $request->input('bio');
        }
        if ($request->has('phone')) {
            $user->phone = $request->input('phone');
        }
        if ($request->has('is_available')) {
            $user->is_available = $request->boolean('is_available', true);
        }

        $user->save();

        return back()->with('success', 'Profile and media updated successfully.');
    }
}
