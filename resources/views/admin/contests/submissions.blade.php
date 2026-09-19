@extends('layouts.admin')

@section('title', 'Contest Submissions & Winner Selection - Noksha Admin HQ')
@section('page_title', 'Contest Submissions')
@section('page_heading', 'Contest Entries & Winner Override')

@section('content')
<div class="space-y-6">

    <!-- CONTEST SUMMARY HEADER CARD -->
    <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-6 border border-gray-200 dark:border-gray-800 shadow-sm">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <a href="{{ route('admin.contests.index') }}" class="text-xs text-brand-600 dark:text-brand-400 font-bold hover:underline inline-flex items-center gap-1 mb-2">
                    <i class="bi bi-arrow-left"></i> Back to Contest Hub
                </a>
                <h2 class="text-xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="bi bi-trophy-fill text-amber-500"></i> {{ $contest->title }}
                </h2>
                <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-gray-500 dark:text-gray-400">
                    <span>Category: <strong class="text-gray-800 dark:text-gray-200">{{ $contest->category->name ?? 'General' }}</strong></span>
                    <span>•</span>
                    <span>Prize Bounty: <strong class="text-teal-600 dark:text-teal-400 font-mono text-sm">৳{{ number_format($contest->prize_amount, 2) }}</strong></span>
                    <span>•</span>
                    <span>Status: 
                        <span class="font-bold uppercase tracking-wider {{ $contest->status === 'active' ? 'text-amber-500' : ($contest->status === 'completed' ? 'text-emerald-500' : 'text-rose-500') }}">
                            {{ $contest->status }}
                        </span>
                    </span>
                    <span>•</span>
                    <span>Total Entries: <strong class="text-purple-500 font-bold">{{ $contest->submissions->count() }}</strong></span>
                </div>
            </div>

            <!-- Current Winner Pill -->
            <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 text-xs">
                <span class="text-gray-500 dark:text-gray-400 block font-semibold">Current Winner:</span>
                @if($contest->winner)
                    <div class="font-black text-amber-700 dark:text-amber-400 text-sm flex items-center gap-1.5 mt-0.5">
                        <i class="bi bi-award-fill text-base"></i>
                        <span>{{ $contest->winner->name }}</span>
                    </div>
                @else
                    <div class="text-gray-400 italic mt-0.5">No winner announced yet</div>
                @endif
            </div>
        </div>
    </div>

    <!-- SUBMISSIONS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($contest->submissions as $submission)
            <div class="bg-white dark:bg-[#0F1623] rounded-2xl border {{ $submission->is_winner ? 'border-amber-400 dark:border-amber-500 shadow-lg shadow-amber-500/10' : 'border-gray-200 dark:border-gray-800' }} overflow-hidden flex flex-col transition-all">
                
                <!-- Preview Thumbnail -->
                <div class="h-48 bg-gray-100 dark:bg-gray-800 relative overflow-hidden group">
                    @if($submission->preview_image)
                        <img src="{{ asset('storage/' . $submission->preview_image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="{{ $submission->title }}">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <i class="bi bi-file-earmark-image text-4xl"></i>
                        </div>
                    @endif

                    @if($submission->is_winner)
                        <div class="absolute top-3 right-3 px-3 py-1 rounded-full bg-amber-500 text-white font-extrabold text-[11px] shadow-lg flex items-center gap-1">
                            <i class="bi bi-award-fill"></i> Declared Winner
                        </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                    <div>
                        <h4 class="font-bold text-sm text-gray-900 dark:text-white truncate" title="{{ $submission->title }}">
                            {{ $submission->title }}
                        </h4>
                        
                        <div class="flex items-center gap-2 mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                            <div class="w-6 h-6 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-[10px]">
                                {{ strtoupper(substr($submission->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $submission->user->name ?? 'Anonymous' }}</span>
                            <span>•</span>
                            <span class="text-[10px]">{{ $submission->created_at->diffForHumans() }}</span>
                        </div>

                        @if($submission->note)
                            <p class="mt-2 text-xs text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900/60 p-2.5 rounded-xl border border-gray-100 dark:border-gray-800 line-clamp-2">
                                {{ $submission->note }}
                            </p>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="pt-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between gap-2">
                        @if($submission->design_file)
                            <a href="{{ asset('storage/' . $submission->design_file) }}" target="_blank" download class="px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-semibold flex items-center gap-1 transition-colors">
                                <i class="bi bi-download"></i> Design File
                            </a>
                        @else
                            <span class="text-xs text-gray-400">No file attachment</span>
                        @endif

                        <!-- Declare / Override Winner -->
                        <form method="POST" action="{{ route('admin.contests.winner', $contest->id) }}">
                            @csrf
                            <input type="hidden" name="submission_id" value="{{ $submission->id }}">
                            @if($submission->is_winner)
                                <button type="button" disabled class="px-3 py-1.5 rounded-lg bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-xs font-bold border border-emerald-500/30 cursor-default">
                                    <i class="bi bi-check-circle-fill"></i> Current Winner
                                </button>
                            @else
                                <button type="submit"
                                        onclick="return confirm('Declare this submission as the WINNER of \"{{ addslashes($contest->title) }}\"?')"
                                        class="px-3 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold transition-colors shadow-sm flex items-center gap-1">
                                    <i class="bi bi-award"></i>
                                    <span>{{ $contest->winner_id ? 'Override Winner' : 'Declare Winner' }}</span>
                                </button>
                            @endif
                        </form>
                    </div>

                </div>

            </div>
        @empty
            <div class="col-span-full bg-white dark:bg-[#0F1623] rounded-2xl p-12 text-center border border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400">
                <i class="bi bi-images text-4xl text-gray-400 block mb-2"></i>
                <h4 class="font-bold text-sm text-gray-800 dark:text-gray-200">No Entries Submitted Yet</h4>
                <p class="text-xs mt-1">Creators have not submitted any design entries for this tournament yet.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
