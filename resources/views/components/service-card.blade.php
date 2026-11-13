@props(['service'])

@php
    $creator = $service->user;
    $isOwner = auth()->check() && (auth()->id() === $service->user_id);
    $previewImages = is_array($service->preview_images) ? array_filter($service->preview_images) : [];
    $imgCount = count($previewImages);
    $availableSlots = max(0, ($service->max_slots ?? 3) - ($service->active_orders_count ?? 0));
    $isBookedOut = ($availableSlots === 0);

    // Normalize preview image URLs
    $formattedImages = [];
    foreach ($previewImages as $img) {
        $formattedImages[] = str_starts_with($img, 'http') ? $img : asset('storage/' . ltrim($img, '/'));
    }

    $serviceRating = number_format($service->rating_avg ?? 5.0, 1);
    $reviewsCount = $service->reviews_count ?? 0;
@endphp

<div class="service-card group bg-white border border-slate-200 hover:border-purple-200 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 flex flex-col h-full overflow-hidden relative">
    <!-- TOP COLLAGE / THUMBNAILS (Behance-Style Multi-Thumbnail Grid) -->
    <div class="relative bg-slate-100 overflow-hidden" style="height: 190px;">
        @if($imgCount >= 3)
            {{-- 3-Image Collage: 1 Large Left (60%) + 2 Stacked Right (40%) --}}
            <div class="grid grid-cols-12 h-full gap-1 p-1">
                <div class="col-span-7 h-full overflow-hidden rounded-l-xl">
                    <img src="{{ $formattedImages[0] }}" 
                         alt="{{ $service->title }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 cursor-pointer"
                         onclick="openServicePreviewModal('{{ $formattedImages[0] }}', '{{ addslashes($service->title) }}')">
                </div>
                <div class="col-span-5 h-full grid grid-rows-2 gap-1">
                    <div class="h-full overflow-hidden rounded-tr-xl">
                        <img src="{{ $formattedImages[1] }}" 
                             alt="{{ $service->title }} preview 2" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 cursor-pointer"
                             onclick="openServicePreviewModal('{{ $formattedImages[1] }}', '{{ addslashes($service->title) }}')">
                    </div>
                    <div class="h-full overflow-hidden rounded-br-xl relative">
                        <img src="{{ $formattedImages[2] }}" 
                             alt="{{ $service->title }} preview 3" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 cursor-pointer"
                             onclick="openServicePreviewModal('{{ $formattedImages[2] }}', '{{ addslashes($service->title) }}')">
                    </div>
                </div>
            </div>
        @elseif($imgCount === 2)
            {{-- 2-Image Split Collage --}}
            <div class="grid grid-cols-2 h-full gap-1 p-1">
                <div class="h-full overflow-hidden rounded-l-xl">
                    <img src="{{ $formattedImages[0] }}" 
                         alt="{{ $service->title }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 cursor-pointer"
                         onclick="openServicePreviewModal('{{ $formattedImages[0] }}', '{{ addslashes($service->title) }}')">
                </div>
                <div class="h-full overflow-hidden rounded-r-xl">
                    <img src="{{ $formattedImages[1] }}" 
                         alt="{{ $service->title }} preview 2" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 cursor-pointer"
                         onclick="openServicePreviewModal('{{ $formattedImages[1] }}', '{{ addslashes($service->title) }}')">
                </div>
            </div>
        @elseif($imgCount === 1)
            {{-- 1 Hero Image --}}
            <div class="h-full w-full overflow-hidden">
                <img src="{{ $formattedImages[0] }}" 
                     alt="{{ $service->title }}" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 cursor-pointer"
                     onclick="openServicePreviewModal('{{ $formattedImages[0] }}', '{{ addslashes($service->title) }}')">
            </div>
        @else
            {{-- Clean Graphical Fallback --}}
            <div class="h-full w-full flex flex-col items-center justify-center p-6 text-center bg-gradient-to-br from-purple-50/70 via-white to-orange-50/40">
                <div class="w-12 h-12 rounded-2xl bg-[#20165c]/10 text-[#20165c] flex items-center justify-center mb-2 shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-700">{{ $service->category ?? 'Custom Creative Service' }}</span>
                <span class="text-[11px] text-slate-400 mt-0.5">Escrow Protected</span>
            </div>
        @endif

        <!-- Floating Category Tag -->
        <span class="absolute top-2.5 left-2.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-white/95 backdrop-blur-md text-[#121826] border border-slate-200/80 shadow-xs">
            {{ $service->category ?? 'Creative' }}
        </span>

        <!-- Floating Live Slot Availability Badge -->
        <div class="absolute top-2.5 right-2.5">
            @if(!$isBookedOut)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/90 text-white backdrop-blur-md shadow-xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                    <span>🟢 {{ $availableSlots }} Slot{{ $availableSlots > 1 ? 's' : '' }} Left</span>
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-600/90 text-white backdrop-blur-md shadow-xs" title="Creator is currently at capacity. You may still inquire for the next queue slot.">
                    <span>🔴 Booked Out</span>
                </span>
            @endif
        </div>
    </div>

    <!-- CARD BODY -->
    <div class="p-4 sm:p-5 flex flex-col flex-1">
        <!-- Creator Header Row -->
        <div class="flex items-center justify-between gap-3 mb-2.5">
            @if($creator)
                <a href="{{ route('creator.profile', $creator->username ?? $creator->id) }}" class="flex items-center gap-2 min-w-0 group/creator">
                    <img src="{{ $creator->avatar ? (str_starts_with($creator->avatar, 'http') ? $creator->avatar : asset('storage/' . $creator->avatar)) : 'https://ui-avatars.com/api/?name=' . urlencode($creator->name) . '&background=20165c&color=ffffff' }}" 
                         alt="{{ $creator->name }}" 
                         class="w-6 h-6 rounded-full object-cover border border-slate-200 shrink-0">
                    <span class="text-xs font-semibold text-slate-700 group-hover/creator:text-[#20165c] truncate">
                        {{ $creator->name }}
                    </span>
                    @if($creator->isContributor() || $creator->is_verified)
                        <svg class="w-3.5 h-3.5 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    @endif
                    @if($creator->hasReliabilityWarning())
                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-300 shrink-0" title="Reliability Warning: Accrued demerit points or late deliveries">
                            ⚠️ Warning
                        </span>
                    @endif
                </a>
            @else
                <span class="text-xs text-slate-500 font-medium">Noksha Creator</span>
            @endif

            <!-- Rating Stars -->
            <div class="flex items-center gap-1 text-xs shrink-0">
                <span class="text-amber-500 font-bold">★</span>
                <span class="font-bold text-slate-800">{{ $serviceRating }}</span>
                <span class="text-slate-400 text-[11px]">({{ $reviewsCount }})</span>
            </div>
        </div>

        <!-- Service Title -->
        <h4 class="text-sm font-bold text-[#121826] hover:text-[#20165c] transition mb-1.5 line-clamp-1 leading-snug">
            {{ $service->title }}
        </h4>

        <!-- Starting Price -->
        <div class="flex items-baseline gap-1.5 mb-2">
            <span class="text-xs text-slate-500 font-medium">From</span>
            <span class="text-base font-extrabold text-[#20165c]">
                ৳{{ number_format($service->starting_price, 2) }}
            </span>
            <span class="text-[10px] text-slate-400 font-mono">BDT</span>
        </div>

        <!-- Short Description (2 Lines) -->
        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed mb-3">
            {{ $service->description }}
        </p>

        <!-- Service Specifications Row -->
        <div class="mt-auto pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-600 mb-3.5 flex-wrap gap-1">
            <span class="inline-flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ $service->delivery_days }}d Delivery</span>
            </span>
            <span class="text-slate-300">•</span>
            <span class="inline-flex items-center gap-1">
                <span>✨ {{ $service->concepts_count }} Concept{{ $service->concepts_count > 1 ? 's' : '' }}</span>
            </span>
            <span class="text-slate-300">•</span>
            <span class="inline-flex items-center gap-1">
                <span>🔄 {{ $service->revisions_count }} Rev</span>
            </span>
        </div>

        <!-- ACTION BUTTONS -->
        <div>
            @if($isOwner)
                {{-- OWNER CONTROLS: Edit Service & Quick Menu --}}
                <div class="flex items-center gap-2">
                    <button type="button" 
                            onclick="openEditServiceModal({{ json_encode($service) }})"
                            class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold text-[#20165c] bg-purple-50 hover:bg-purple-100 border border-purple-200 transition cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        <span>Edit Service</span>
                    </button>

                    <form action="{{ route('services.toggle-status', $service->id) }}" method="POST" class="m-0 p-0">
                        @csrf
                        <button type="submit" 
                                class="px-2.5 py-2 rounded-xl text-xs font-semibold {{ $service->status === 'active' ? 'text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200' : 'text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200' }} transition cursor-pointer" 
                                title="{{ $service->status === 'active' ? 'Pause Service' : 'Activate Service' }}">
                            {{ $service->status === 'active' ? '⏸️' : '▶️' }}
                        </button>
                    </form>

                    <form action="{{ route('services.destroy', $service->id) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Are you sure you want to delete this service?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-2.5 py-2 rounded-xl text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition cursor-pointer" title="Delete Service">
                            🗑️
                        </button>
                    </form>
                </div>
            @else
                {{-- VISITOR / BUYER: Smart Brief Inquire CTA --}}
                <button type="button" 
                        onclick="openInquiryModal({{ json_encode([
                            'id' => $service->id,
                            'title' => $service->title,
                            'category' => $service->category,
                            'starting_price' => $service->starting_price,
                            'delivery_days' => $service->delivery_days,
                            'creator_name' => $creator?->name ?? 'Noksha Creator',
                            'creator_rating' => $serviceRating,
                            'rating_count' => $reviewsCount,
                            'preview_images' => $formattedImages,
                            'available_slots' => $availableSlots,
                            'is_booked_out' => $isBookedOut,
                        ]) }})"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-bold text-xs text-white bg-[#20165c] hover:bg-[#181045] shadow-sm hover:shadow transition-all cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <span>Inquire</span>
                </button>
            @endif
        </div>
    </div>
</div>
