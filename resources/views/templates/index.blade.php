@extends('layouts.app')

@section('title', 'Marketplace Templates & Creative Digital Assets - Noksha')

@section('content')
<div class="min-h-screen bg-[#ffffff] text-slate-800 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">

        <!-- HEADER HERO (Standard Flow with Clear Separation) -->
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm relative w-full mb-10 py-6">
            <div class="max-w-4xl">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-[#20165c] border border-purple-200 mb-3">
                    <svg class="w-3.5 h-3.5 shrink-0 text-[#f47402]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>Curated Marketplace</span>
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#121826] tracking-tight">Explore Creative Design Templates</h1>
                <p class="text-sm text-slate-500 mt-2">Discover premium UI kits, vectors, Figma assets, and digital templates crafted by verified creators.</p>
                
                <!-- DYNAMIC CATEGORY FILTER PILLS -->
                <div class="mt-6 flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
                    <a href="{{ route('templates.index', array_merge(request()->except(['category', 'page']))) }}" 
                       class="px-4 py-2 rounded-xl text-xs font-semibold transition-all shrink-0 {{ empty($categoryId) ? 'bg-[#20165c] text-white shadow-sm' : 'bg-white text-slate-600 hover:text-[#20165c] hover:bg-slate-100 border border-slate-200' }}">
                        All Templates
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('templates.index', array_merge(request()->except(['page']), ['category' => $category->id])) }}" 
                           class="px-4 py-2 rounded-xl text-xs font-semibold transition-all shrink-0 {{ (string)$categoryId === (string)$category->id ? 'bg-[#20165c] text-white shadow-sm' : 'bg-white text-slate-600 hover:text-[#20165c] hover:bg-slate-100 border border-slate-200' }}">
                            {{ $category->name }}
                            @if(isset($category->resources_count))
                                <span class="ml-1 opacity-70 font-mono text-[11px]">({{ $category->resources_count }})</span>
                            @endif
                        </a>
                    @endforeach
                </div>

                @if(!empty($queryStr))
                    <div class="mt-4 pt-3 border-t border-slate-200 flex items-center gap-2 text-sm text-slate-600">
                        <span>Showing results for <strong class="text-[#121826]">"{{ $queryStr }}"</strong></span>
                        <a href="{{ route('templates.index', request()->except(['q', 'page'])) }}" class="inline-flex items-center gap-1 text-xs text-rose-600 hover:underline ms-2 font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Clear search
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- TEMPLATES GRID -->
        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 relative z-0">
            @forelse($templates as $template)
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md hover:border-[#20165c]/30 transition duration-200 flex flex-col justify-between group">
                    <div>
                        <!-- Thumbnail Box -->
                        <div class="relative aspect-video overflow-hidden bg-slate-100">
                            <img src="{{ $template->thumbnail_url }}" alt="{{ $template->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" onerror="this.src='{{ asset('images/logo.png') }}'">
                            
                            <!-- Category Badge -->
                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-white/95 text-slate-700 border border-slate-200 shadow-xs backdrop-blur-md">
                                {{ $template->category?->name ?? 'Design' }}
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="p-4">
                            <h3 class="text-sm font-bold text-[#121826] truncate block group-hover:text-[#20165c] transition-colors" title="{{ $template->title }}">
                                {{ $template->title }}
                            </h3>

                            <div class="flex items-center gap-2 mt-2 text-xs text-slate-500">
                                @if($template->owner)
                                    <span>by <strong class="text-slate-700">{{ $template->owner->name }}</strong></span>
                                @else
                                    <span>by <strong class="text-slate-700">Noksha Creator</strong></span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="p-4 pt-3 border-t border-slate-200 flex items-center justify-between mt-auto">
                        <span class="text-base font-extrabold text-[#f47402] font-mono">
                            {{ $template->price == 0 ? 'Free' : '৳' . number_format($template->price, 2) }}
                        </span>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('templates.show', $template->id) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#20165c] hover:bg-[#181045] shadow-sm transition-all">
                                View
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <!-- ELEGANT DYNAMIC ZERO-STATE -->
                <div class="col-span-full bg-slate-50 border border-slate-200 rounded-2xl p-12 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-[#20165c]/10 border border-[#20165c]/20 text-[#20165c] mx-auto flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#121826]">No creations published yet</h3>
                    <p class="text-xs text-slate-500 mt-1.5 max-w-md mx-auto">Upload your first design asset to start showcasing your portfolio and earning royalties.</p>
                    <div class="mt-6 flex items-center justify-center gap-3">
                        <a href="{{ route('dashboard', ['tab' => 'upload']) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-[#20165c] hover:bg-[#181045] shadow-lg transition-all">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Upload New Asset</span>
                        </a>
                        <a href="{{ route('templates.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-700 hover:text-[#20165c] bg-white border border-slate-200 hover:bg-slate-50 shadow-xs transition-all">
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
