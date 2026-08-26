<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the Noksha welcome homepage with real database-driven marketplace listings.
     */
    public function index(Request $request): View
    {
        // 1. Featured / Latest Approved Resources (Paginated 12 items per page)
        $resources = Resource::where('status', 'approved')
            ->with(['owner', 'category'])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        // 2. Trending Resources (Top 3 ordered by downloads & views)
        $trendingResources = Resource::where('status', 'approved')
            ->with(['owner', 'category'])
            ->orderByDesc('downloads')
            ->orderByDesc('views')
            ->take(3)
            ->get();

        // 3. Categories with real approved resources count
        $categories = Category::withCount(['resources' => function ($query) {
            $query->where('status', 'approved');
        }])->get();

        // 4. Statistics count
        $totalApprovedCount = Resource::where('status', 'approved')->count();

        return view('welcome', compact(
            'resources',
            'trendingResources',
            'categories',
            'totalApprovedCount'
        ));
    }
}
