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

        // 4. Statistics counts (Real-time database-driven with smart base counters)
        $realTemplates = class_exists(\App\Models\Template::class)
            ? \App\Models\Template::count()
            : Resource::count();

        $realCreators = \App\Models\User::where(function ($query) {
            $query->where('role', 'seller')
                ->orWhere('role', 'contributor')
                ->orWhere('is_contributor', true)
                ->orWhere('contributor_status', 'approved')
                ->orWhereHas('resources');
        })->count();

        $realDownloads = (int) (Resource::sum('downloads') ?: 0);
        if (class_exists(\App\Models\OrderItem::class)) {
            $realDownloads += \App\Models\OrderItem::count();
        }

        // Dynamic real-time statistics counts from database
        $templatesCount = $realTemplates;
        $creatorsCount = $realCreators;
        $downloadsCount = $realDownloads;

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

        $totalApprovedCount = Resource::where('status', 'approved')->count();

        return view('welcome', compact(
            'resources',
            'trendingResources',
            'categories',
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
