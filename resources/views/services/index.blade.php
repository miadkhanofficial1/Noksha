@extends('layouts.app')

@section('title', 'On-Demand Creator Services & Custom Design Projects - Noksha')

@section('content')
<div class="min-h-screen bg-[#f8fafc] text-slate-800 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">

        <!-- HEADER HERO -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-10 shadow-sm relative w-full overflow-hidden">
            <div class="absolute -top-16 -right-16 w-64 h-64 bg-purple-500/5 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-16 -left-16 w-64 h-64 bg-orange-500/5 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative max-w-3xl">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-[#20165c] border border-purple-200 mb-3 shadow-xs">
                    <svg class="w-3.5 h-3.5 shrink-0 text-[#f47402]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>On-Demand Creator Services</span>
                </span>
                <h1 class="text-2xl sm:text-4xl font-extrabold text-[#121826] tracking-tight">
                    Hire Top Designers for Custom Projects
                </h1>
                <p class="text-sm text-slate-500 mt-2.5 leading-relaxed">
                    Collaborate directly with verified creators for bespoke branding, UI/UX systems, illustrations, and digital assets. Every project is secured by Noksha Smart Escrow with milestone release.
                </p>

                <!-- SEARCH & FILTER FORM -->
                <form action="{{ route('services.index') }}" method="GET" class="mt-6 space-y-4">
                    <div class="flex flex-col sm:flex-row items-stretch gap-2.5 max-w-2xl">
                        <div class="relative flex-1">
                            <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" name="q" value="{{ $queryStr }}" 
                                   placeholder="Search services (e.g. Logo Design, UI Kit, Fintech App, Mockups)..." 
                                   class="w-full pl-10 pr-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-[#20165c] focus:outline-none transition shadow-xs">
                        </div>
                        <button type="submit" class="px-6 py-3 rounded-xl bg-[#20165c] hover:bg-[#170f45] text-white font-medium text-xs shadow-sm transition-all duration-200 shrink-0 cursor-pointer">
                            Search Services
                        </button>
                    </div>

                    <!-- CATEGORY PILLS -->
                    <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none pt-2">
                        <a href="{{ route('services.index', request()->except(['category', 'page'])) }}" 
                           class="px-4 py-2 rounded-xl text-xs font-bold transition shrink-0 {{ empty($category) ? 'bg-[#20165c] text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:text-[#20165c] hover:bg-purple-50 border border-slate-200' }}">
                            All Categories
                        </a>
                        @php
                            $serviceCats = [
                                'Graphics & Branding',
                                'UI/UX Design',
                                'Logo & Identity',
                                'Social Media Kit',
                                'Packaging & Labels',
                                'Illustrations',
                                '3D & Mockups',
                            ];
                        @endphp
                        @foreach($serviceCats as $catName)
                            <a href="{{ route('services.index', array_merge(request()->except(['page']), ['category' => $catName])) }}" 
                               class="px-4 py-2 rounded-xl text-xs font-semibold transition shrink-0 {{ $category === $catName ? 'bg-[#20165c] text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:text-[#20165c] hover:bg-purple-50 border border-slate-200' }}">
                                {{ $catName }}
                            </a>
                        @endforeach
                    </div>
                </form>

                @if(!empty($queryStr))
                    <div class="mt-4 pt-3 border-t border-slate-200 flex items-center gap-2 text-xs text-slate-600">
                        <span>Showing search results for <strong class="text-[#121826]">"{{ $queryStr }}"</strong></span>
                        <a href="{{ route('services.index', request()->except(['q', 'page'])) }}" class="text-rose-600 hover:underline font-bold ms-2">Clear search</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- TOOLBAR: FILTER OPTIONS & SORTING -->
        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4 flex-wrap">
                <!-- AVAILABLE SLOTS ONLY TOGGLE -->
                <form id="filterForm" action="{{ route('services.index') }}" method="GET" class="flex items-center gap-3 flex-wrap m-0 p-0">
                    @if(!empty($queryStr)) <input type="hidden" name="q" value="{{ $queryStr }}"> @endif
                    @if(!empty($category)) <input type="hidden" name="category" value="{{ $category }}"> @endif

                    <label class="inline-flex items-center gap-2 cursor-pointer bg-slate-50 hover:bg-slate-100 border border-slate-200 px-3 py-1.5 rounded-xl text-xs font-bold transition">
                        <input type="checkbox" name="available_only" value="1" {{ $availableOnly ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()" class="rounded text-[#20165c] focus:ring-[#20165c]">
                        <span class="text-slate-800">🟢 Available Slots Only</span>
                    </label>

                    <!-- DELIVERY DAYS -->
                    <select name="delivery_days" onchange="document.getElementById('filterForm').submit()" class="px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700 focus:outline-none">
                        <option value="">Any Delivery Time</option>
                        <option value="3" {{ $deliveryDays == 3 ? 'selected' : '' }}>≤ 3 Days</option>
                        <option value="7" {{ $deliveryDays == 7 ? 'selected' : '' }}>≤ 7 Days</option>
                        <option value="14" {{ $deliveryDays == 14 ? 'selected' : '' }}>≤ 14 Days</option>
                    </select>

                    <!-- SORT -->
                    <select name="sort" onchange="document.getElementById('filterForm').submit()" class="px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700 focus:outline-none">
                        <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Newest Listed</option>
                        <option value="rating" {{ $sort === 'rating' ? 'selected' : '' }}>Highest Rated (★)</option>
                        <option value="price_low" {{ $sort === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ $sort === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="fastest" {{ $sort === 'fastest' ? 'selected' : '' }}>Fastest Delivery</option>
                        <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Most Active Orders</option>
                    </select>

                    @if($availableOnly || !empty($deliveryDays) || !empty($category) || !empty($queryStr) || ($sort !== 'latest' && !empty($sort)))
                        <a href="{{ route('services.index') }}" class="text-xs text-rose-500 hover:underline font-semibold">
                            Reset Filters
                        </a>
                    @endif
                </form>
            </div>

            <!-- CREATOR STUDIO CTA -->
            @if(auth()->check() && auth()->user()->isApprovedContributor())
                <button type="button" onclick="openCreateServiceModal()" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-medium text-white bg-[#20165c] hover:bg-[#170f45] shadow-sm transition-all duration-200 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Add New Service</span>
                </button>
            @endif
        </div>

        <!-- SERVICES GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($services as $service)
                <x-service-card :service="$service" />
            @empty
                <div class="col-span-full bg-white border border-dashed border-slate-300 rounded-3xl p-12 text-center my-6 shadow-xs">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-purple-50 text-[#20165c] flex items-center justify-center text-2xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#121826] mb-1.5">No Services Match Your Filter</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto mb-5">
                        Try resetting your search query, adjusting your delivery timeline, or clearing the category filter to explore all available services.
                    </p>
                    <a href="{{ route('services.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-medium text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 shadow-sm transition-all duration-200">
                        Reset All Filters
                    </a>
                </div>
            @endforelse
        </div>

        <!-- PAGINATION -->
        @if($services->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $services->links() }}
            </div>
        @endif
    </div>
</div>

<!-- MODALS -->
@include('services.partials.inquiry-modal')
@include('services.partials.service-form-modal')
@include('services.partials.image-preview-modal')
@endsection
