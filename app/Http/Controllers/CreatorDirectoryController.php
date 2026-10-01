<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CreatorDirectoryController extends Controller
{
    /**
     * Display the Top Creators & Visual Designers directory.
     */
    public function index(Request $request): View
    {
        $search = trim($request->input('search', ''));
        $activeSpecialty = trim($request->input('specialty', 'all'));

        $query = User::query()
            // Strictly exclude admin and superadmin accounts from public creator discovery
            ->where('is_admin', false)
            ->where('role', '!=', 'admin')
            ->where('role', '!=', 'superadmin')
            ->where('role', '!=', 'super_admin')
            ->where(function ($q) {
                $q->whereIn('role', ['contributor', 'seller'])
                  ->orWhere('contributor_status', 'approved')
                  ->orWhere('is_contributor', true);
            })
            ->where(function ($q) {
                $q->where('contributor_status', 'approved')
                  ->orWhere('is_contributor', true);
            })
            ->withCount(['templates' => function ($q) {
                $q->where('status', 'approved');
            }]);

        // Filter by creator name, username, or general query
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('bio', 'like', "%{$search}%")
                  ->orWhere('contributor_bio', 'like', "%{$search}%")
                  ->orWhere('headline', 'like', "%{$search}%")
                  ->orWhere('skills', 'like', "%{$search}%");
            });
        }

        // Filter by specialty tag
        if ($activeSpecialty !== '' && $activeSpecialty !== 'all') {
            $query->where(function ($q) use ($activeSpecialty) {
                $q->where('skills', 'like', "%{$activeSpecialty}%")
                  ->orWhere('headline', 'like', "%{$activeSpecialty}%")
                  ->orWhere('bio', 'like', "%{$activeSpecialty}%")
                  ->orWhere('contributor_bio', 'like', "%{$activeSpecialty}%");
            });
        }

        $creators = $query->orderByDesc('templates_count')
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        $specialties = [
            'all' => 'All Creators',
            'UI/UX' => 'UI/UX Design',
            'Vectors' => 'Vectors & Icons',
            '3D' => '3D Graphics',
            'Branding' => 'Branding & Identity',
            'Typography' => 'Typography',
            'Print' => 'Print & Packaging',
        ];

        return view('creators.index', compact('creators', 'specialties', 'activeSpecialty', 'search'));
    }
}
