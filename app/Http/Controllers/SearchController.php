<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Display the smart marketplace search page with filters, tags, and category chips.
     */
    public function index(Request $request): View
    {
        $queryStr = trim($request->input('q', ''));
        $categoryId = $request->input('category');
        $selectedTag = $request->input('tag');
        $priceType = $request->input('price');
        $sort = $request->input('sort', 'latest');

        $resourcesQuery = Resource::where('status', 'approved')
            ->with(['owner', 'category']);

        // 1. Text Search matching title, description, category, seller, or tags
        if (!empty($queryStr)) {
            // Track in session
            $recent = session('recent_searches', []);
            if (!in_array($queryStr, $recent)) {
                array_unshift($recent, $queryStr);
                session(['recent_searches' => array_slice($recent, 0, 5)]);
            }

            $resourcesQuery->where(function ($q) use ($queryStr) {
                $q->where('title', 'like', "%{$queryStr}%")
                  ->orWhere('description', 'like', "%{$queryStr}%")
                  ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$queryStr}%"))
                  ->orWhereHas('owner', fn($u) => $u->where('name', 'like', "%{$queryStr}%"))
                  ->orWhere('tags', 'like', "%{$queryStr}%");
            });
        }

        // 2. Category Filter
        if ($categoryId) {
            $resourcesQuery->where('category_id', $categoryId);
        }

        // 3. Tag Filter
        if ($selectedTag) {
            $resourcesQuery->where('tags', 'like', "%{$selectedTag}%");
        }

        // 4. Price Filter
        if ($priceType === 'free') {
            $resourcesQuery->where('is_paid', false);
        } elseif ($priceType === 'paid') {
            $resourcesQuery->where('is_paid', true);
        }

        // 5. Sorting
        if ($sort === 'popular') {
            $resourcesQuery->orderByDesc('downloads');
        } elseif ($sort === 'price_low') {
            $resourcesQuery->orderBy('price', 'asc');
        } elseif ($sort === 'price_high') {
            $resourcesQuery->orderBy('price', 'desc');
        } else {
            $resourcesQuery->latest();
        }

        $resources = $resourcesQuery->paginate(12)->withQueryString();

        $categories = Category::all();
        $recentSearches = session('recent_searches', ['Mobile UI', 'Figma', 'Fintech', 'Logo', 'Vector']);

        // Popular Tags Cloud
        $allTags = Resource::where('status', 'approved')->pluck('tags')->flatten()->filter()->toArray();
        $tagCounts = array_count_values(array_map('strtolower', $allTags));
        arsort($tagCounts);
        $popularTags = array_slice(array_keys($tagCounts), 0, 14);
        if (empty($popularTags)) {
            $popularTags = ['ui', 'fintech', 'mobile', 'dashboard', 'figma', 'interface', 'web', 'logo', 'social', 'vector'];
        }

        return view('search.index', compact(
            'queryStr',
            'resources',
            'categories',
            'categoryId',
            'selectedTag',
            'priceType',
            'sort',
            'recentSearches',
            'popularTags'
        ));
    }
}
