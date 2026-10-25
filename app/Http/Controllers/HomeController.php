<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
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

        // 4. Statistics counts (Real-time database-driven strictly synchronized with Admin HQ)
        $templatesQuery = Resource::where('status', 'approved');
        if (Schema::hasColumn('resources', 'is_active')) {
            $templatesQuery->where('is_active', true);
        }
        $templatesCount = $templatesQuery->count();

        // Strictly filter for approved sellers/creators matching Admin User Hub schema
        // Excludes admins, buyers, and general users
        $creatorsQuery = User::where('is_admin', false)
            ->whereNotIn('role', ['admin', 'super_admin', 'buyer', 'user'])
            ->where(function ($query) {
                $query->where('role', 'seller')
                    ->orWhere('role', 'creator')
                    ->orWhere('is_contributor', true);
            })
            ->where('status', '!=', 'suspended');
        $creatorsCount = $creatorsQuery->count();

        $downloadsCount = (int) (Resource::where('status', 'approved')->sum('downloads') ?: 0);
        if (class_exists(\App\Models\OrderItem::class)) {
            $downloadsCount += \App\Models\OrderItem::whereHas('order', function ($q) {
                $q->where('payment_status', 'completed');
            })->count();
        }

        // Smart K+ / M+ formatter for counters
        $formatStat = function (int $number) {
            if ($number >= 1000000) {
                $val = round($number / 1000000, 1);
                $display = ($val == floor($val) ? (int)$val : $val) . 'M+';
                return ['display' => $display, 'target' => $val, 'suffix' => 'M+'];
            }
            if ($number >= 1000) {
                $val = round($number / 1000, 1);
                $display = ($val == floor($val) ? (int)$val : $val) . 'K+';
                return ['display' => $display, 'target' => $val, 'suffix' => 'K+'];
            }
            return ['display' => number_format($number), 'target' => $number, 'suffix' => ''];
        };

        $templatesStat = $formatStat($templatesCount);
        $creatorsStat = $formatStat($creatorsCount);
        $downloadsStat = $formatStat($downloadsCount);

        $formattedTemplatesCount = $templatesStat['display'];
        $formattedCreatorsCount = $creatorsStat['display'];
        $formattedDownloadsCount = $downloadsStat['display'];

        $totalApprovedCount = $templatesCount;

        $heroShowcase = $trendingResources->first() ?? $resources->first();
        $aiShowcase = $resources->skip(1)->first() ?? $resources->first();

        return view('welcome', compact(
            'resources',
            'trendingResources',
            'categories',
            'heroShowcase',
            'aiShowcase',
            'totalApprovedCount',
            'templatesCount',
            'creatorsCount',
            'downloadsCount',
            'templatesStat',
            'creatorsStat',
            'downloadsStat',
            'formattedTemplatesCount',
            'formattedCreatorsCount',
            'formattedDownloadsCount'
        ));
    }
}
