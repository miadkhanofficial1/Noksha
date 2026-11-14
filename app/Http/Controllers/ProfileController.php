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
            $avatarFile = $request->file('avatar');
            $avatarExt = $avatarFile->getClientOriginalExtension() ?: 'jpg';
            $avatarFilename = time() . '_' . \Illuminate\Support\Str::random(16) . '.' . $avatarExt;

            // 1. Store to storage/app/public/avatars via public disk
            $storedPath = $avatarFile->storeAs('avatars', $avatarFilename, 'public');
            $cleanPath = ltrim(str_replace(['public/', 'storage/'], '', $storedPath), '/');
            $user->avatar = $cleanPath;

            // 2. Also ensure public/uploads/avatars has a copy for resilient zero-symlink hosting
            try {
                $uploadDir = public_path('uploads/avatars');
                if (!file_exists($uploadDir)) {
                    @mkdir($uploadDir, 0755, true);
                }
                @copy($avatarFile->getRealPath(), $uploadDir . '/' . $avatarFilename);
            } catch (\Throwable $e) {
                // Silently fallback if filesystem permissions disallow direct write
            }

            // 3. Mirror to public/storage/avatars if available
            try {
                $publicStorageDir = public_path('storage/avatars');
                if (!file_exists($publicStorageDir)) {
                    @mkdir($publicStorageDir, 0755, true);
                }
                if (!file_exists($publicStorageDir . '/' . $avatarFilename)) {
                    @copy($avatarFile->getRealPath(), $publicStorageDir . '/' . $avatarFilename);
                }
            } catch (\Throwable $e) {
                // Silently fallback
            }
        }

        if ($request->hasFile('cover_image')) {
            $coverFile = $request->file('cover_image');
            $coverExt = $coverFile->getClientOriginalExtension() ?: 'jpg';
            $coverFilename = time() . '_' . \Illuminate\Support\Str::random(16) . '.' . $coverExt;

            // 1. Store to storage/app/public/covers via public disk
            $storedCoverPath = $coverFile->storeAs('covers', $coverFilename, 'public');
            $cleanCoverPath = ltrim(str_replace(['public/', 'storage/'], '', $storedCoverPath), '/');
            $user->cover_image = $cleanCoverPath;

            // 2. Mirror copy to public/uploads/covers
            try {
                $uploadCoverDir = public_path('uploads/covers');
                if (!file_exists($uploadCoverDir)) {
                    @mkdir($uploadCoverDir, 0755, true);
                }
                @copy($coverFile->getRealPath(), $uploadCoverDir . '/' . $coverFilename);
            } catch (\Throwable $e) {
                // Silently fallback
            }

            // 3. Mirror copy to public/storage/covers
            try {
                $publicStorageCoverDir = public_path('storage/covers');
                if (!file_exists($publicStorageCoverDir)) {
                    @mkdir($publicStorageCoverDir, 0755, true);
                }
                if (!file_exists($publicStorageCoverDir . '/' . $coverFilename)) {
                    @copy($coverFile->getRealPath(), $publicStorageCoverDir . '/' . $coverFilename);
                }
            } catch (\Throwable $e) {
                // Silently fallback
            }
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

        $user->is_available = $request->boolean('is_available');

        $user->save();

        return back()->with('success', 'Profile and media updated successfully.');
    }
}
