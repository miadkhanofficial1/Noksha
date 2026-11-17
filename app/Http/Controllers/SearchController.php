<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Resource;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Unified multi-section discovery search across Creators, Services, and Templates (/search).
     */
    public function index(Request $request): View
    {
        $q = trim((string) $request->input('q', ''));
        $tab = $request->input('tab', 'all');
        if (!in_array($tab, ['all', 'templates', 'services', 'creators'])) {
            $tab = 'all';
        }

        if ($q !== '') {
            // Track search keyword in session
            $recent = session('recent_searches', []);
            if (!in_array($q, $recent)) {
                array_unshift($recent, $q);
                session(['recent_searches' => array_slice($recent, 0, 5)]);
            }

            // 1. Matching Creators (Active contributors/sellers)
            $creators = User::query()
                ->where('is_admin', false)
                ->where('role', '!=', 'admin')
                ->where('role', '!=', 'superadmin')
                ->where('role', '!=', 'super_admin')
                ->where(function ($query) {
                    $query->where('contributor_status', 'approved')
                          ->orWhere('is_contributor', true)
                          ->orWhereIn('role', ['contributor', 'seller']);
                })
                ->where(function ($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%")
                          ->orWhere('username', 'like', "%{$q}%")
                          ->orWhere('bio', 'like', "%{$q}%")
                          ->orWhere('contributor_bio', 'like', "%{$q}%")
                          ->orWhere('headline', 'like', "%{$q}%")
                          ->orWhere('skills', 'like', "%{$q}%");
                })
                ->withCount(['templates' => fn($t) => $t->where('status', 'approved')])
                ->with(['templates' => fn($t) => $t->where('status', 'approved')->latest('id')->limit(3)])
                ->latest('id')
                ->get();

            // 2. Matching Creative Services
            $services = Service::query()
                ->where(function ($query) {
                    $query->where('status', 'active')
                          ->orWhere('is_active', true);
                })
                ->where(function ($query) use ($q) {
                    $query->where('title', 'like', "%{$q}%")
                          ->orWhere('description', 'like', "%{$q}%")
                          ->orWhere('category', 'like', "%{$q}%")
                          ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$q}%")->orWhere('username', 'like', "%{$q}%"));
                })
                ->with(['user.receivedReviews', 'reviews'])
                ->latest()
                ->get();

            // 3. Matching Templates / Assets
            $templates = Resource::query()
                ->where('status', 'approved')
                ->where(function ($query) use ($q) {
                    $query->where('title', 'like', "%{$q}%")
                          ->orWhere('description', 'like', "%{$q}%")
                          ->orWhere('tags', 'like', "%{$q}%")
                          ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$q}%"))
                          ->orWhereHas('owner', fn($u) => $u->where('name', 'like', "%{$q}%")->orWhere('username', 'like', "%{$q}%"));
                })
                ->with(['owner', 'category'])
                ->latest()
                ->get();
        } else {
            $creators = collect();
            $services = collect();
            $templates = collect();
        }

        $totalCount = $creators->count() + $services->count() + $templates->count();

        return view('search.index', compact(
            'q',
            'tab',
            'creators',
            'services',
            'templates',
            'totalCount'
        ));
    }
}
