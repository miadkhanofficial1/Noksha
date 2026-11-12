@extends('layouts.admin')

@section('title', 'Contest Submissions & Winner Selection - Noksha Admin HQ')
@section('page_title', 'Contest Submissions')
@section('page_heading', 'Contest Entries & Winner Override')

@section('content')
<div class="space-y-6">

    <!-- CONTEST SUMMARY HEADER CARD -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <a href="{{ route('admin.contests.index') }}" class="text-xs text-[#20165c] font-bold hover:underline inline-flex items-center gap-1 mb-2">
                    <i class="bi bi-arrow-left"></i> Back to Contest Hub
                </a>
                <h2 class="text-xl font-black text-[#121826] flex items-center gap-2">
                    <i class="bi bi-trophy-fill text-[#f47402]"></i> {{ $contest->title }}
                </h2>
                <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-slate-500">
                    <span>Category: <strong class="text-slate-800">{{ $contest->display_category }}</strong></span>
                    <span>•</span>
                    <span>Prize Bounty: <strong class="text-[#20165c] font-mono text-sm">৳{{ number_format($contest->prize_amount, 0) }}</strong></span>
                    <span>•</span>
                    <span>Status: 
                        <span class="font-bold uppercase tracking-wider {{ $contest->status === 'active' ? 'text-emerald-600' : ($contest->status === 'completed' ? 'text-[#20165c]' : 'text-amber-600') }}">
                            {{ $contest->status }}
                        </span>
                    </span>
                    <span>•</span>
                    <span>Total Entries: <strong class="text-[#20165c] font-bold">{{ $contest->entries->count() ?: $contest->submissions->count() }}</strong></span>
                </div>
            </div>

            <!-- Current Winner Pill -->
            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-xs">
                <span class="text-slate-500 block font-semibold">Declared Champion:</span>
                @if($contest->winner)
                    <div class="font-black text-amber-800 text-sm flex items-center gap-1.5 mt-0.5">
                        <i class="bi bi-award-fill text-base text-amber-600"></i>
                        <span>{{ $contest->winner->name }}</span>
                    </div>
                @else
                    <div class="text-slate-400 italic mt-0.5">No winner announced yet</div>
                @endif
            </div>
        </div>
    </div>

    <!-- SUBMISSIONS GRID -->
    @php
        $entriesList = $contest->entries->isNotEmpty() ? $contest->entries : $contest->submissions;
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($entriesList as $entry)
            <div class="bg-white rounded-2xl border {{ $entry->is_winner ? 'border-amber-400 shadow-md shadow-amber-500/10' : 'border-slate-200' }} overflow-hidden flex flex-col justify-between transition-all">
                
                <div>
                    <!-- Preview Thumbnail -->
                    <div class="h-52 bg-slate-100 relative overflow-hidden group border-b border-slate-200">
                        @if($entry->watermarked_preview_image)
                            <img src="{{ asset('storage/' . $entry->watermarked_preview_image) }}"
                                 onerror="this.onerror=null; this.src='https://placehold.co/400x300/20165c/ffffff?text=Design+Preview';"
                                 class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                                 alt="{{ $entry->title }}">
                        @elseif($entry->preview_image)
                            <img src="{{ asset('storage/' . $entry->preview_image) }}"
                                 onerror="this.onerror=null; this.src='https://placehold.co/400x300/20165c/ffffff?text=Design+Preview';"
                                 class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                                 alt="{{ $entry->title }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                <i class="bi bi-file-earmark-image text-4xl"></i>
                            </div>
                        @endif

                        <div class="absolute top-3 left-3 px-2.5 py-0.5 rounded-full bg-slate-900/70 text-white font-mono text-[10px]">
                            Entry #{{ $entry->id }}
                        </div>

                        @if($entry->is_winner)
                            <div class="absolute top-3 right-3 px-3 py-1 rounded-full bg-amber-500 text-white font-extrabold text-[11px] shadow-sm flex items-center gap-1">
                                <i class="bi bi-award-fill"></i> Declared Winner
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-5 space-y-3">
                        <div>
                            <h4 class="font-bold text-sm text-[#121826] truncate" title="{{ $entry->title }}">
                                {{ $entry->title }}
                            </h4>
                            
                            <div class="flex items-center gap-2 mt-1.5 text-xs text-slate-500">
                                <div class="w-6 h-6 rounded-full bg-purple-100 text-[#20165c] flex items-center justify-center font-bold text-[10px]">
                                    {{ strtoupper(substr($entry->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <span class="font-semibold text-slate-800">{{ $entry->user->name ?? 'Anonymous' }}</span>
                                <span>•</span>
                                <span class="text-[10px] text-slate-400">{{ $entry->created_at->diffForHumans() }}</span>
                            </div>

                            @if($entry->description || $entry->note)
                                <p class="mt-2 text-xs text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-100 line-clamp-2">
                                    {{ $entry->description ?? $entry->note }}
                                </p>
                            @endif

                            @if($entry->client_rating > 0)
                                <div class="mt-2 p-2 bg-amber-50 rounded-xl border border-amber-200 text-[11px]">
                                    <div class="text-amber-700 font-bold flex items-center gap-1">
                                        <i class="bi bi-star-fill text-amber-500"></i> Client Rated: {{ $entry->client_rating }}/5
                                    </div>
                                    @if($entry->client_feedback)
                                        <div class="text-slate-600 italic mt-0.5">"{{ $entry->client_feedback }}"</div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="p-5 pt-0">
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                        @if($entry->source_file_link)
                            <a href="{{ $entry->source_file_link }}" target="_blank" rel="noopener noreferrer" class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center gap-1 transition-colors border border-slate-200">
                                <i class="bi bi-link-45deg"></i> Source Link
                            </a>
                        @elseif($entry->design_file)
                            <a href="{{ asset('storage/' . $entry->design_file) }}" target="_blank" download class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center gap-1 transition-colors border border-slate-200">
                                <i class="bi bi-download"></i> Source File
                            </a>
                        @else
                            <span class="text-xs text-slate-400">No source link</span>
                        @endif

                        <!-- Declare / Override Winner -->
                        <form method="POST" action="{{ route('admin.contests.winner', $contest->id) }}">
                            @csrf
                            <input type="hidden" name="entry_id" value="{{ $entry->id }}">
                            <input type="hidden" name="submission_id" value="{{ $entry->id }}">
                            @if($entry->is_winner)
                                <button type="button" disabled class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200 cursor-default">
                                    <i class="bi bi-check-circle-fill"></i> Current Winner
                                </button>
                            @else
                                <button type="submit"
                                        onclick="return confirm('Declare this submission as the WINNER of \"{{ addslashes($contest->title) }}\"? ৳{{ number_format($contest->prize_amount, 0) }} will be credited.')"
                                        class="px-3 py-1.5 rounded-lg bg-[#f47402] hover:bg-[#d86300] text-white text-xs font-bold transition-colors shadow-sm flex items-center gap-1 cursor-pointer">
                                    <i class="bi bi-award"></i>
                                    <span>{{ $contest->winner_id ? 'Override Winner' : 'Declare Winner' }}</span>
                                </button>
                            @endif
                        </form>
                    </div>
                </div>

            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl p-12 text-center border border-slate-200 text-slate-500">
                <i class="bi bi-images text-4xl text-slate-400 block mb-2"></i>
                <h4 class="font-bold text-sm text-[#121826]">No Entries Submitted Yet</h4>
                <p class="text-xs mt-1">Creators have not submitted any design concepts for this tournament yet.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection