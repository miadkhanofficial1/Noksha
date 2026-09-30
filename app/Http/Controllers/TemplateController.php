<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TemplateController extends Controller
{
    /**
     * Display marketplace templates listing with real database records, categories, and zero-state.
     */
    public function index(Request $request): View
    {
        $queryStr = trim($request->input('q', ''));
        $categoryId = $request->input('category');
        $selectedTag = $request->input('tag');
        $priceType = $request->input('price');
        $sort = $request->input('sort', 'latest');

        $templatesQuery = Template::where('status', 'approved')
            ->with(['owner', 'category']);

        if (!empty($queryStr)) {
            $templatesQuery->where(function ($q) use ($queryStr) {
                $q->where('title', 'like', "%{$queryStr}%")
                  ->orWhere('description', 'like', "%{$queryStr}%")
                  ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$queryStr}%"))
                  ->orWhereHas('owner', fn($u) => $u->where('name', 'like', "%{$queryStr}%"))
                  ->orWhere('tags', 'like', "%{$queryStr}%");
            });
        }

        if ($categoryId) {
            $templatesQuery->where('category_id', $categoryId);
        }

        if ($selectedTag) {
            $templatesQuery->where('tags', 'like', "%{$selectedTag}%");
        }

        if ($priceType === 'free') {
            $templatesQuery->where('is_paid', false);
        } elseif ($priceType === 'paid') {
            $templatesQuery->where('is_paid', true);
        }

        if ($sort === 'popular') {
            $templatesQuery->orderByDesc('downloads');
        } elseif ($sort === 'price_low') {
            $templatesQuery->orderBy('price', 'asc');
        } elseif ($sort === 'price_high') {
            $templatesQuery->orderBy('price', 'desc');
        } else {
            $templatesQuery->latest();
        }

        $templates = $templatesQuery->paginate(12)->withQueryString();
        $resources = $templates;

        $categories = Category::withCount(['resources' => function ($q) {
            $q->where('status', 'approved');
        }])->get();

        return view('templates.index', compact(
            'templates',
            'resources',
            'categories',
            'categoryId',
            'queryStr',
            'selectedTag',
            'priceType',
            'sort'
        ));
    }

    /**
     * Show single template.
     */
    public function show(string $id)
    {
        return app(ResourceController::class)->show($id);
    }
}
