@extends('layouts.app')

@section('title', 'Marketplace Templates & Creative Digital Assets - Noksha')

@section('content')
<div class="min-h-screen bg-slate-950 text-slate-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">

        <!-- HEADER HERO -->
        <div class="bg-slate-900/70 border border-slate-800/90 rounded-2xl p-6 sm:p-8 shadow-xl backdrop-blur-md">
            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 mb-3">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>Curated Marketplace</span>
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Explore Creative Design Templates</h1>
                <p class="text-sm text-slate-400 mt-2">Discover premium UI kits, vectors, Figma assets, and digital templates crafted by verified creators.</p>
                
                <!-- Search Box -->
                <form action="{{ route('templates.index') }}" method="GET" class="mt-5 flex items-center gap-3 max-w-xl">
                    <div class="relative flex-grow">
                        <svg class="w-5 h-5 absolute left-3.5 top-3 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="q" value="{{ $queryStr ?? '' }}" placeholder="Search templates by keyword, title, or tag..." class="w-full pl-11 pr-4 py-2.5 rounded-xl bg-slate-950/80 border border-slate-800 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none">
                    </div>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 shadow-md transition-all shrink-0">
                        Search
                    </button>
                </form>
            </div>
        </div>

        <!-- DYNAMIC CATEGORY FILTER PILLS -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
            <a href="{{ route('templates.index', array_merge(request()->except(['category', 'page']))) }}" 
               class="px-4 py-2 rounded-xl text-xs font-semibold transition-all shrink-0 {{ empty($categoryId) ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-900/80 text-slate-400 hover:text-white hover:bg-slate-800 border border-slate-800' }}">
                All Templates
            </a>
            @foreach($categories as $category)
                <a href="{{ route('templates.index', array_merge(request()->except(['page']), ['category' => $category->id])) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-semibold transition-all shrink-0 {{ (string)$categoryId === (string)$category->id ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-900/80 text-slate-400 hover:text-white hover:bg-slate-800 border border-slate-800' }}">
                    {{ $category->name }}
                    @if(isset($category->resources_count))
                        <span class="ml-1 opacity-70 font-mono text-[11px]">({{ $category->resources_count }})</span>
                    @endif
                </a>
            @endforeach
        </div>

        <!-- TEMPLATES GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($templates as $template)
                <div class="bg-slate-900/70 border border-slate-800/90 rounded-2xl overflow-hidden shadow-lg backdrop-blur-sm hover:border-slate-700 transition duration-200 flex flex-col justify-between group">
                    <div>
                        <!-- Thumbnail Box -->
                        <div class="relative aspect-video overflow-hidden bg-slate-950">
                            <img src="{{ $template->thumbnail_url }}" alt="{{ $template->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" onerror="this.src='{{ asset('images/logo.png') }}'">
                            
                            <!-- Category Badge -->
                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-slate-950/80 text-slate-200 border border-slate-800/80 backdrop-blur-md">
                                {{ $template->category?->name ?? 'Design' }}
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="p-4">
                            <h3 class="text-sm font-bold text-white truncate block group-hover:text-indigo-400 transition-colors" title="{{ $template->title }}">
                                {{ $template->title }}
                            </h3>

                            <div class="flex items-center gap-2 mt-2 text-xs text-slate-400">
                                @if($template->owner)
                                    <span>by <strong class="text-slate-300">{{ $template->owner->name }}</strong></span>
                                @else
                                    <span>by <strong class="text-slate-300">Noksha Creator</strong></span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="p-4 pt-3 border-t border-slate-800/80 flex items-center justify-between mt-auto">
                        <span class="text-base font-extrabold text-cyan-400 font-mono">
                            {{ $template->price == 0 ? 'Free' : '৳' . number_format($template->price, 2) }}
                        </span>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('templates.show', $template->id) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 shadow-sm transition-all">
                                View
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <!-- ELEGANT DYNAMIC ZERO-STATE -->
                <div class="col-span-full bg-slate-900/60 border border-slate-800/90 rounded-2xl p-12 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 mx-auto flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-white">No creations published yet</h3>
                    <p class="text-xs text-slate-400 mt-1.5 max-w-md mx-auto">Upload your first design asset to start showcasing your portfolio and earning royalties.</p>
                    <div class="mt-6 flex items-center justify-center gap-3">
                        <a href="{{ route('dashboard', ['tab' => 'upload']) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 shadow-lg transition-all">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Upload New Asset</span>
                        </a>
                        <a href="{{ route('templates.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:text-white bg-slate-800 border border-slate-700/60 transition-all">
                            <span>Reset Filters</span>
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- PAGINATION -->
        @if($templates->hasPages())
            <div class="pt-4">
                {{ $templates->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
