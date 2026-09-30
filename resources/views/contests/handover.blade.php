@extends('layouts.app')

@section('title', 'Handover Workspace: ' . $contest->title . ' - Noksha')

@section('content')
<div class="min-h-screen bg-slate-950 text-slate-200 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto space-y-8">

        <!-- Top Navigation / Breadcrumb -->
        <div class="flex items-center justify-between flex-wrap gap-4">
            <nav class="flex items-center gap-2 text-xs font-mono text-slate-400">
                <a href="{{ route('home') }}" class="hover:text-indigo-400 transition">Home</a>
                <span class="text-slate-600">/</span>
                <a href="{{ route('contests.index') }}" class="hover:text-indigo-400 transition">Contests</a>
                <span class="text-slate-600">/</span>
                <a href="{{ route('contests.show', $contest->slug ?: $contest->id) }}" class="hover:text-indigo-400 transition truncate max-w-xs">{{ $contest->title }}</a>
                <span class="text-slate-600">/</span>
                <span class="text-indigo-400 font-semibold">Handover & Escrow</span>
            </nav>

            <a href="{{ route('contests.show', $contest->slug ?: $contest->id) }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-slate-900 border border-slate-800 text-xs font-semibold text-slate-300 hover:text-white hover:border-slate-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Back to Contest Page</span>
            </a>
        </div>

        <!-- Flash Notifications -->
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm flex items-start gap-3 shadow-lg">
                <svg class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <div class="space-y-0.5">
                    <p class="font-bold text-white">Success</p>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-sm flex items-start gap-3 shadow-lg">
                <svg class="w-5 h-5 text-rose-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div class="space-y-0.5">
                    <p class="font-bold text-white">Notice</p>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-sm space-y-2">
                <div class="font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Please review the following requirements:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 text-rose-200 ps-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Handover Header Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-950/80 via-slate-900/90 to-purple-950/70 border border-indigo-500/30 p-6 sm:p-8 shadow-2xl backdrop-blur-md">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative z-10">
                <div class="space-y-3">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/30 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <span>Phase: Winner Source Handover</span>
                        </span>

                        @if($contest->status === 'completed' || $entry->handover_status === 'approved')
                            <span class="px-3 py-1 rounded-full text-xs font-mono font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Escrow Completed & Transferred</span>
                            </span>
                        @elseif($entry->handover_status === 'submitted')
                            <span class="px-3 py-1 rounded-full text-xs font-mono font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-indigo-400 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span>Source Files Under Client Review</span>
                            </span>
                        @elseif($entry->handover_status === 'revision_requested')
                            <span class="px-3 py-1 rounded-full text-xs font-mono font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Revision Requested</span>
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-mono font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-amber-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Awaiting Designer Source Upload</span>
                            </span>
                        @endif
                    </div>

                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-black text-white tracking-tight">
                        {{ $contest->title }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-4 text-xs text-slate-300 pt-1">
                        <div class="flex items-center gap-2">
                            <span class="text-slate-400">Organizer:</span>
                            <span class="font-bold text-white">{{ $buyer->name ?? 'Contest Host' }}</span>
                        </div>
                        <span class="text-slate-600">•</span>
                        <div class="flex items-center gap-2">
                            <span class="text-slate-400">Awarded Winner:</span>
                            <span class="font-bold text-amber-400 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 011.233 1.455l-1.077 1.436 1.077 1.436a1 1 0 01-1.233 1.455l-1.599-.8L11 11.677V13a1 1 0 01-2 0v-1.323L5.046 10.095l-1.599.8A1 1 0 012.214 9.44l1.077-1.436-1.077-1.436a1 1 0 011.233-1.455l1.599.8L9 4.323V3a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                                {{ $winner->name ?? 'Winning Designer' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Prize Bounty Stat in Header -->
                <div class="shrink-0 bg-slate-900/90 border border-slate-800 rounded-2xl p-4 sm:p-5 flex flex-col justify-center items-start lg:items-end min-w-[200px]">
                    <span class="text-[11px] font-mono uppercase tracking-wider text-slate-400">Total Bounty Prize</span>
                    <div class="text-2xl sm:text-3xl font-black text-emerald-400 flex items-baseline gap-1 mt-1">
                        <span class="text-lg">৳</span>
                        <span>{{ number_format($prizeBounty, 2) }}</span>
                    </div>
                    <span class="text-[11px] font-mono text-emerald-400/80 mt-0.5">Protected by Noksha Escrow</span>
                </div>
            </div>
        </div>

        <!-- Escrow Financial Ledger Breakdown Card -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 shadow-lg flex flex-col justify-between">
                <span class="text-xs font-mono uppercase tracking-wider text-slate-400">Locked Bounty</span>
                <div class="mt-2">
                    <p class="text-2xl font-black text-white">৳{{ number_format($prizeBounty, 2) }}</p>
                    <p class="text-[11px] text-slate-400 mt-1">Pre-funded by contest organizer</p>
                </div>
            </div>

            <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 shadow-lg flex flex-col justify-between">
                <span class="text-xs font-mono uppercase tracking-wider text-slate-400">Platform Escrow Fee</span>
                <div class="mt-2">
                    <p class="text-2xl font-black text-indigo-400">৳0.00</p>
                    <p class="text-[11px] text-slate-400 mt-1">0% escrow custody fee</p>
                </div>
            </div>

            <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 shadow-lg flex flex-col justify-between">
                <span class="text-xs font-mono uppercase tracking-wider text-slate-400">Net Designer Payout</span>
                <div class="mt-2">
                    <p class="text-2xl font-black text-emerald-400">৳{{ number_format($prizeBounty, 2) }}</p>
                    <p class="text-[11px] text-slate-400 mt-1">100% credited to withdrawable balance</p>
                </div>
            </div>

            <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 shadow-lg flex flex-col justify-between">
                <span class="text-xs font-mono uppercase tracking-wider text-slate-400">Escrow Ledger Status</span>
                <div class="mt-2">
                    @if($contest->status === 'completed' || $entry->handover_status === 'approved')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Released to Winner Wallet</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                            <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span>Escrow Locked</span>
                        </span>
                    @endif
                    <p class="text-[11px] text-slate-400 mt-1">Safe BDT escrow protocol</p>
                </div>
            </div>
        </div>

        <!-- Revision Notice Banner (if active) -->
        @if($entry->handover_status === 'revision_requested')
            <div class="p-5 rounded-2xl bg-amber-500/10 border border-amber-500/30 backdrop-blur-sm flex items-start gap-4 shadow-xl">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div class="space-y-1">
                    <h3 class="font-bold text-amber-300 text-sm">Organizer Requested Revisions on Source Files</h3>
                    <p class="text-xs text-slate-300 leading-relaxed font-mono bg-slate-950/70 p-3 rounded-xl border border-amber-500/20 mt-2">
                        "{{ $entry->handover_notes ?? 'Please inspect deliverable files and re-upload revised package.' }}"
                    </p>
                    <p class="text-[11px] text-slate-400 pt-1">Designer: Please address the notes above and upload an updated source package.</p>
                </div>
            </div>
        @endif

        <!-- 2-COLUMN MAIN WORKSPACE -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

            <!-- ============================================== -->
            <!-- COLUMN 1: DESIGNER SOURCE FILE HANDOVER ZONE -->
            <!-- ============================================== -->
            <div class="bg-slate-900/80 border border-slate-800 rounded-2xl shadow-xl overflow-hidden backdrop-blur-md space-y-6">
                
                <!-- Col 1 Header -->
                <div class="p-6 border-b border-slate-800/80 flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-600/10 text-purple-400 border border-purple-500/20 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-white">1. Designer Source Package</h2>
                            <p class="text-xs text-slate-400">Master editable files & assets</p>
                        </div>
                    </div>

                    <!-- Designer Pill -->
                    <div class="flex items-center gap-2">
                        <img src="{{ $winner->avatar_url }}" alt="{{ $winner->name }}" class="w-7 h-7 rounded-full object-cover border border-indigo-500/40">
                        <span class="text-xs font-semibold text-slate-300 truncate max-w-[120px]">{{ $winner->name }}</span>
                    </div>
                </div>

                <div class="p-6 pt-0 space-y-6">

                    <!-- Awarded Entry Summary Card -->
                    <div class="p-4 rounded-xl bg-slate-950 border border-slate-800 flex items-center gap-4">
                        <div class="w-16 h-16 rounded-lg bg-slate-900 border border-slate-800 overflow-hidden shrink-0">
                            @if($entry->watermarked_preview_image)
                                <img src="{{ asset('storage/' . $entry->watermarked_preview_image) }}" alt="Winning Entry" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-600 text-xs">Preview</div>
                            @endif
                        </div>
                        <div class="space-y-1 truncate">
                            <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                Awarded Concept #{{ $entry->id }}
                            </span>
                            <h3 class="text-sm font-bold text-white truncate">{{ $entry->title }}</h3>
                            <p class="text-xs text-slate-400 truncate">{{ $entry->description ?? 'No extra notes provided' }}</p>
                        </div>
                    </div>

                    <!-- Upload Form (Accessible to Winning Designer or Admin if not yet completed) -->
                    @if(($isWinner || $isAdmin) && $contest->status !== 'completed' && $entry->handover_status !== 'approved')
                        <div class="space-y-4 pt-2">
                            <div class="border-t border-slate-800 pt-4">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Upload Editable Source Package</h4>
                                <p class="text-xs text-slate-400 leading-relaxed">
                                    Prepare a compressed archive containing vector master files (AI, PSD, SVG, EPS, PDF), along with font licensing and asset links.
                                </p>
                            </div>

                            <form action="{{ route('contests.handover.upload', $contest->slug ?: $contest->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="handoverUploadForm">
                                @csrf

                                <!-- File Drop Input -->
                                <div class="relative border-2 border-dashed border-slate-700 hover:border-indigo-500 rounded-2xl p-6 bg-slate-950/60 text-center transition cursor-pointer group">
                                    <input type="file" 
                                           id="source_file" 
                                           name="source_file" 
                                           accept=".zip,.rar,.7z,.tar,.gz,.ai,.psd,.eps,.svg,.pdf" 
                                           required 
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                                    <div class="space-y-2" id="sourceUploadPrompt">
                                        <div class="w-12 h-12 mx-auto rounded-xl bg-indigo-600/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center group-hover:scale-105 transition">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        </div>
                                        <p class="text-xs font-semibold text-white">
                                            <span class="text-indigo-400">Click to browse</span> or drag & drop ZIP package
                                        </p>
                                        <p class="text-[11px] text-slate-400">Accepted: ZIP, RAR, 7Z, AI, PSD, EPS, SVG (Max: 100MB)</p>
                                    </div>

                                    <div id="sourceFileSelected" class="hidden space-y-2">
                                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-indigo-950 border border-indigo-500/30 text-xs font-mono text-indigo-300">
                                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            <span id="sourceFileName" class="font-bold"></span>
                                            <span id="sourceFileSize" class="text-slate-400"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Handover Notes -->
                                <div class="space-y-1.5">
                                    <label for="handover_notes" class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                                        Handover Notes & Font Instructions <span class="text-slate-500 font-normal lowercase">(optional)</span>
                                    </label>
                                    <textarea id="handover_notes" 
                                              name="handover_notes" 
                                              rows="3" 
                                              maxlength="2000"
                                              placeholder="List font names used, color codes (HEX/CMYK), or any instructions for the buyer..."
                                              class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">{{ old('handover_notes', $entry->handover_notes) }}</textarea>
                                </div>

                                <!-- Checklist Guidance -->
                                <div class="p-3.5 rounded-xl bg-slate-950 border border-slate-800/80 space-y-1.5">
                                    <span class="text-xs font-bold text-slate-300 flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Deliverable Quality Standards
                                    </span>
                                    <ul class="text-[11px] text-slate-400 space-y-1 ps-5 list-disc">
                                        <li>Include 100% vector outlines and non-outlined editable texts.</li>
                                        <li>Organize layers into logical groups (Logomark, Typography, Background).</li>
                                        <li>High-resolution exports (PNG, SVG, PDF in RGB & CMYK).</li>
                                    </ul>
                                </div>

                                <button type="submit" id="handoverUploadBtn" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white text-xs font-bold shadow-lg shadow-indigo-600/30 transition transform hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    <span>Upload & Submit Handover Package</span>
                                </button>
                            </form>
                        </div>
                    @endif

                    <!-- Uploaded Package History -->
                    <div class="space-y-3 pt-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-300">Deliverable Archive Records</h4>

                        @if(!empty($handoverFiles))
                            <div class="space-y-2">
                                @foreach($handoverFiles as $file)
                                    <div class="p-3.5 rounded-xl bg-slate-950 border border-slate-800 flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-3 truncate">
                                            <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-400 flex items-center justify-center shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                            </div>
                                            <div class="truncate">
                                                <p class="text-xs font-mono font-bold text-white truncate">{{ $file['original_name'] ?? 'handover_archive.zip' }}</p>
                                                <p class="text-[10px] font-mono text-slate-500">
                                                    {{ isset($file['size']) ? number_format($file['size'] / (1024 * 1024), 2) . ' MB' : 'Archived' }} • 
                                                    {{ $file['uploaded_at'] ?? 'Uploaded' }}
                                                </p>
                                            </div>
                                        </div>

                                        <a href="{{ route('contests.handover.download', $contest->slug ?: $contest->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 hover:border-slate-700 text-xs font-semibold text-slate-300 hover:text-white transition shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            <span>Download</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-6 rounded-xl bg-slate-950/60 border border-slate-800/80 text-center space-y-2">
                                <div class="w-10 h-10 mx-auto rounded-xl bg-slate-900 border border-slate-800 text-slate-500 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <p class="text-xs font-semibold text-slate-300">No source files submitted yet</p>
                                <p class="text-[11px] text-slate-500">The winning contributor is preparing the final editable assets for review.</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            <!-- ============================================== -->
            <!-- COLUMN 2: CLIENT REVIEW & ESCROW RELEASE ZONE -->
            <!-- ============================================== -->
            <div class="bg-slate-900/80 border border-slate-800 rounded-2xl shadow-xl overflow-hidden backdrop-blur-md space-y-6">
                
                <!-- Col 2 Header -->
                <div class="p-6 border-b border-slate-800/80 flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600/10 text-emerald-400 border border-emerald-500/20 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-white">2. Client Review & Escrow Release</h2>
                            <p class="text-xs text-slate-400">Inspect deliverables & transfer prize</p>
                        </div>
                    </div>

                    <!-- Client Pill -->
                    <div class="flex items-center gap-2">
                        <img src="{{ $buyer->avatar_url }}" alt="{{ $buyer->name }}" class="w-7 h-7 rounded-full object-cover border border-emerald-500/40">
                        <span class="text-xs font-semibold text-slate-300 truncate max-w-[120px]">{{ $buyer->name }}</span>
                    </div>
                </div>

                <div class="p-6 pt-0 space-y-6">

                    <!-- Direct Package Download Card -->
                    <div class="p-4 rounded-xl bg-slate-950 border border-slate-800 space-y-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-300">Deliverable File Access</span>
                        
                        @if(!empty($handoverFiles))
                            <div class="flex items-center justify-between gap-3 flex-wrap">
                                <div>
                                    <p class="text-xs font-semibold text-white">{{ $latestFile['original_name'] ?? 'Latest Source Package' }}</p>
                                    <p class="text-[11px] text-slate-400">Stored safely in Noksha private storage</p>
                                </div>

                                <a href="{{ route('contests.handover.download', $contest->slug ?: $contest->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition shadow-md shadow-indigo-600/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    <span>Download Source Archive</span>
                                </a>
                            </div>
                        @else
                            <div class="flex items-center justify-between gap-3 text-slate-500">
                                <p class="text-xs">Archive not yet submitted</p>
                                <button type="button" disabled class="px-4 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-600 text-xs font-bold cursor-not-allowed">
                                    Awaiting Upload
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Status Evaluation & Action Section -->
                    @if($contest->status === 'completed' || $entry->handover_status === 'approved')
                        <!-- Completed State -->
                        <div class="p-6 rounded-2xl bg-emerald-950/40 border border-emerald-500/30 text-center space-y-3">
                            <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 flex items-center justify-center">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h3 class="text-base font-bold text-white">Contest Completed & Escrow Released</h3>
                            <p class="text-xs text-slate-300 max-w-sm mx-auto leading-relaxed">
                                The organizer confirmed file acceptance. <strong>৳{{ number_format($prizeBounty, 2) }}</strong> was credited to <strong>{{ $winner->name }}'s</strong> withdrawable creator balance.
                            </p>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-mono font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                Completed on {{ $contest->handover_completed_at ? $contest->handover_completed_at->format('M d, Y h:i A') : 'Completed' }}
                            </span>
                        </div>
                    @elseif(!empty($handoverFiles) && $entry->handover_status === 'submitted')
                        <!-- Ready for Review & Decision -->
                        <div class="space-y-4">
                            <div class="p-4 rounded-xl bg-indigo-950/40 border border-indigo-500/30 space-y-2">
                                <div class="flex items-center gap-2 text-indigo-300 text-xs font-bold uppercase tracking-wider">
                                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Client Review in Progress</span>
                                </div>
                                <p class="text-xs text-slate-300 leading-relaxed">
                                    Please extract and inspect the source files to ensure all required vector layers, fonts, and assets are present and fully editable before approving release.
                                </p>
                            </div>

                            @if($isBuyer || $isAdmin)
                                <!-- Buyer Action Buttons -->
                                <div class="space-y-3 pt-2">
                                    <button type="button" onclick="openReleaseModal()" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-xs font-bold shadow-lg shadow-emerald-600/30 transition transform hover:-translate-y-0.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span>Accept Deliverables & Release ৳{{ number_format($prizeBounty, 2) }}</span>
                                    </button>

                                    <button type="button" onclick="openRevisionModal()" class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 border border-slate-800 hover:border-amber-500/50 text-slate-300 hover:text-amber-400 text-xs font-bold transition">
                                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Request Revision from Designer</span>
                                    </button>
                                </div>
                            @else
                                <div class="p-4 rounded-xl bg-slate-950 border border-slate-800 text-center">
                                    <p class="text-xs text-slate-400">
                                        Waiting for client <strong class="text-white">{{ $buyer->name }}</strong> to inspect the uploaded package and confirm escrow release.
                                    </p>
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- Waiting for Designer Upload -->
                        <div class="p-6 rounded-2xl bg-slate-950 border border-slate-800 text-center space-y-3">
                            <div class="w-12 h-12 mx-auto rounded-xl bg-slate-900 border border-slate-800 text-slate-500 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h3 class="text-sm font-bold text-white">Awaiting Source Upload</h3>
                            <p class="text-xs text-slate-400 max-w-sm mx-auto leading-relaxed">
                                Once winning designer <strong>{{ $winner->name }}</strong> submits the editable ZIP package, you will be able to download and release the escrow funds here.
                            </p>
                        </div>
                    @endif

                    <!-- Safety & Rights Info Callout -->
                    <div class="p-4 rounded-xl bg-slate-950 border border-slate-800/80 space-y-2">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-300">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span>Intellectual Property Transfer</span>
                        </div>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            Upon releasing escrow, full worldwide commercial rights and copyright to the awarded design concept are irrevocably transferred from the winning contributor to the contest organizer.
                        </p>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>

<!-- ============================================== -->
<!-- CONFIRMATION MODAL: ESCROW RELEASE -->
<!-- ============================================== -->
<div id="releaseModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-white">Release Escrow Bounty</h3>
                <p class="text-xs text-slate-400">Irrevocable wallet transfer confirmation</p>
            </div>
        </div>

        <div class="p-4 rounded-xl bg-slate-950 border border-slate-800 space-y-2 text-xs">
            <div class="flex justify-between text-slate-300">
                <span>Awarded Designer:</span>
                <strong class="text-white">{{ $winner->name }}</strong>
            </div>
            <div class="flex justify-between text-slate-300">
                <span>Escrow Prize Release:</span>
                <strong class="text-emerald-400 text-sm">৳{{ number_format($prizeBounty, 2) }}</strong>
            </div>
            <p class="text-slate-400 text-[11px] pt-2 border-t border-slate-800/80">
                By confirming, you certify that you have inspected the deliverable files and approve them. The bounty will immediately be added to the designer's withdrawable balance.
            </p>
        </div>

        <form action="{{ route('contests.handover.release', $contest->slug ?: $contest->id) }}" method="POST" class="flex items-center justify-end gap-3">
            @csrf
            <button type="button" onclick="closeReleaseModal()" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 hover:text-white text-xs font-semibold transition">
                Cancel
            </button>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-lg shadow-emerald-600/30 transition">
                Confirm & Release Funds
            </button>
        </form>
    </div>
</div>

<!-- ============================================== -->
<!-- REVISION REQUEST MODAL -->
<!-- ============================================== -->
<div id="revisionModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-400 border border-amber-500/20 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-white">Request Source Revision</h3>
                <p class="text-xs text-slate-400">Specify required fixes or file adjustments</p>
            </div>
        </div>

        <form action="{{ route('contests.handover.revision', $contest->slug ?: $contest->id) }}" method="POST" class="space-y-4">
            @csrf

            <div class="space-y-2">
                <label for="revision_notes" class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                    Revision Details & Instructions <span class="text-rose-400">*</span>
                </label>
                <textarea id="revision_notes" 
                          name="revision_notes" 
                          rows="4" 
                          required 
                          maxlength="2000"
                          placeholder="e.g. Please include font licensing links and export the icon as transparent PNG 2048x2048..."
                          class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500 transition"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeRevisionModal()" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 hover:text-white text-xs font-semibold transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold shadow-lg shadow-amber-600/30 transition">
                    Send Revision Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openReleaseModal() {
    document.getElementById('releaseModal').classList.remove('hidden');
}

function closeReleaseModal() {
    document.getElementById('releaseModal').classList.add('hidden');
}

function openRevisionModal() {
    document.getElementById('revisionModal').classList.remove('hidden');
}

function closeRevisionModal() {
    document.getElementById('revisionModal').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    const sourceFileInput = document.getElementById('source_file');
    const sourceUploadPrompt = document.getElementById('sourceUploadPrompt');
    const sourceFileSelected = document.getElementById('sourceFileSelected');
    const sourceFileName = document.getElementById('sourceFileName');
    const sourceFileSize = document.getElementById('sourceFileSize');

    if (sourceFileInput) {
        sourceFileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                sourceFileName.textContent = file.name;
                sourceFileSize.textContent = '(' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)';
                sourceUploadPrompt.classList.add('hidden');
                sourceFileSelected.classList.remove('hidden');
            }
        });
    }
});
</script>
@endsection
