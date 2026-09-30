@extends('layouts.app')

@section('title', 'Submit Entry: ' . $contest->title . ' - Noksha')

@section('content')
<div class="min-h-screen bg-slate-950 text-slate-200 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto space-y-8">
        
        <!-- Breadcrumb & Back Action -->
        <div class="flex items-center justify-between flex-wrap gap-4">
            <nav class="flex items-center gap-2 text-xs font-mono text-slate-400">
                <a href="{{ route('home') }}" class="hover:text-indigo-400 transition">Home</a>
                <span class="text-slate-600">/</span>
                <a href="{{ route('contests.index') }}" class="hover:text-indigo-400 transition">Contests</a>
                <span class="text-slate-600">/</span>
                <a href="{{ route('contests.show', $contest->slug ?: $contest->id) }}" class="hover:text-indigo-400 transition truncate max-w-xs">{{ $contest->title }}</a>
                <span class="text-slate-600">/</span>
                <span class="text-indigo-400 font-semibold">Submit Entry</span>
            </nav>

            <a href="{{ route('contests.show', $contest->slug ?: $contest->id) }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-slate-900 border border-slate-800 text-xs font-semibold text-slate-300 hover:text-white hover:border-slate-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Back to Contest</span>
            </a>
        </div>

        <!-- Contest Summary Banner Card -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-950/70 via-slate-900/90 to-purple-950/60 border border-indigo-500/20 p-6 sm:p-8 shadow-2xl backdrop-blur-md">
            <div class="absolute -right-12 -top-12 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                <div class="space-y-3">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-indigo-600/30 text-indigo-300 border border-indigo-500/30">
                            {{ $contest->category->name ?? 'Design Contest' }}
                        </span>
                        
                        @if($contest->effective_deadline && $contest->effective_deadline->isFuture())
                            <span class="px-3 py-1 rounded-full text-xs font-mono font-bold bg-amber-500/10 text-amber-300 border border-amber-500/30 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-amber-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $contest->effective_deadline->diffForHumans(['parts' => 2]) }} left</span>
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-mono font-bold bg-rose-500/10 text-rose-300 border border-rose-500/30">
                                Contest Closed
                            </span>
                        @endif

                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-slate-800/80 text-slate-300 border border-slate-700/80">
                            Single Entry Rule Applied
                        </span>
                    </div>

                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-black text-white tracking-tight leading-tight">
                        {{ $contest->title }}
                    </h1>

                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed max-w-2xl line-clamp-2">
                        {{ Str::limit(strip_tags($contest->description), 160) }}
                    </p>
                </div>

                <!-- Prize Bounty Pill -->
                <div class="shrink-0 flex md:flex-col items-center md:items-end justify-between border-t md:border-t-0 md:border-l border-slate-800/80 pt-4 md:pt-0 md:pl-6">
                    <span class="text-xs font-mono uppercase tracking-wider text-slate-400">Guaranteed Prize</span>
                    <div class="text-2xl sm:text-3xl font-black text-emerald-400 tracking-tight flex items-baseline gap-1 mt-1">
                        <span class="text-lg">৳</span>
                        <span>{{ number_format($contest->prize_bounty ?: $contest->prize_amount ?: 0, 2) }}</span>
                    </div>
                    <span class="text-[11px] font-mono text-emerald-300/80 mt-0.5">100% Escrow Funded</span>
                </div>
            </div>
        </div>

        <!-- Flash Messages & Validation Errors -->
        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-sm flex items-start gap-3">
                <svg class="w-5 h-5 text-rose-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div class="space-y-1">
                    <p class="font-bold text-white">Submission Notice</p>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-sm space-y-2">
                <div class="font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Please resolve the following submission issues:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 text-rose-200 ps-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main Submission Form Container -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl shadow-xl backdrop-blur-md overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-slate-800/80">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white">Submit Your Original Design</h2>
                        <p class="text-xs text-slate-400">All submissions are timestamped and protected with automatic diagonal preview watermarks.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('contests.entries.store', $contest->slug ?: $contest->id) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-8" id="contestSubmitForm">
                @csrf

                <!-- 1. Entry Title -->
                <div class="space-y-2">
                    <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Design Concept Title <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title') }}" 
                           required 
                           maxlength="255"
                           placeholder="e.g. Modern Minimalist Branding & Identity System"
                           class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    <p class="text-[11px] text-slate-400">Give your submission a distinct title that describes your artistic approach.</p>
                </div>

                <!-- 2. Concept Description & Rationale -->
                <div class="space-y-2">
                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Concept Notes & Rationale <span class="text-slate-500 font-normal lowercase">(optional)</span>
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4" 
                              maxlength="2000"
                              placeholder="Describe your design choices, color palette reasoning, typography choices, and how it aligns with the client brief..."
                              class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">{{ old('description') }}</textarea>
                    <p class="text-[11px] text-slate-400">Clear rationales help clients appreciate the thought process behind your design.</p>
                </div>

                <!-- 3. Drag-and-Drop Preview Upload Zone -->
                <div class="space-y-3">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        High-Resolution Artwork Preview <span class="text-rose-400">*</span>
                    </label>

                    <div id="dropZone" class="relative group border-2 border-dashed border-slate-700 hover:border-indigo-500 rounded-2xl p-8 bg-slate-950/60 transition cursor-pointer text-center">
                        <input type="file" 
                               id="clean_preview_image" 
                               name="clean_preview_image" 
                               accept="image/png,image/jpeg,image/webp" 
                               required 
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">

                        <!-- Empty State -->
                        <div id="uploadPrompt" class="space-y-3">
                            <div class="w-14 h-14 mx-auto rounded-2xl bg-indigo-600/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center group-hover:scale-105 transition">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            </div>
                            <div class="space-y-1">
                                <p class="text-sm font-semibold text-white">
                                    <span class="text-indigo-400">Click to browse</span> or drag and drop artwork preview
                                </p>
                                <p class="text-xs text-slate-400">JPG, PNG, or WebP (Max size: 10MB, minimum recommendation 1200x800px)</p>
                            </div>
                        </div>

                        <!-- Preview State -->
                        <div id="imagePreviewContainer" class="hidden relative z-10 space-y-3">
                            <img id="imagePreview" src="" alt="Preview" class="max-h-80 mx-auto rounded-xl shadow-lg border border-slate-700 object-contain">
                            <div class="flex items-center justify-center gap-2">
                                <span id="fileName" class="text-xs font-mono text-slate-300 font-semibold truncate max-w-xs"></span>
                                <span id="fileSize" class="text-xs font-mono text-slate-500"></span>
                                <button type="button" id="removeFileBtn" class="px-2 py-0.5 rounded bg-rose-500/20 text-rose-300 text-xs font-mono hover:bg-rose-500/30 transition">Change</button>
                            </div>
                        </div>
                    </div>

                    <!-- Watermark Security Information Callout -->
                    <div class="p-4 rounded-xl bg-slate-950 border border-indigo-500/20 flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-400 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <div class="text-xs space-y-1">
                            <span class="font-bold text-slate-200">Automatic Watermark Protection</span>
                            <p class="text-slate-400 leading-relaxed">
                                Your clean source artwork will be securely stored in protected storage. Noksha automatically stamps a high-contrast diagonal watermark across the public gallery preview to prevent unauthorized client usage before prize award.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 4. Optional External Drive / Source Link -->
                <div class="space-y-2">
                    <label for="source_file_link" class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        External Proof or Figma Prototype Link <span class="text-slate-500 font-normal lowercase">(optional)</span>
                    </label>
                    <input type="url" 
                           id="source_file_link" 
                           name="source_file_link" 
                           value="{{ old('source_file_link') }}" 
                           placeholder="https://figma.com/@design or https://drive.google.com/..."
                           class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    <p class="text-[11px] text-slate-400">If you have an interactive live demo or Figma prototype link, you may attach it here.</p>
                </div>

                <!-- 5. Originality Guarantee & Terms Checkbox -->
                <div class="p-4 rounded-xl bg-slate-950 border border-slate-800 space-y-3">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" 
                               name="agreement" 
                               value="1" 
                               required
                               {{ old('agreement') ? 'checked' : '' }}
                               class="mt-1 w-4 h-4 rounded border-slate-700 bg-slate-900 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-slate-950">
                        <span class="text-xs text-slate-300 leading-relaxed">
                            <strong class="text-white">Originality & Intellectual Property Guarantee:</strong> I solemnly declare that this entry is 100% my original, custom-crafted artwork created specifically for this contest. It does not infringe upon any third-party copyrights, trademarked logos, or stock assets. If awarded, I agree to deliver full editable source files (AI, PSD, SVG, fonts, and documentation) to the contest organizer.
                        </span>
                    </label>
                </div>

                <!-- 6. Action Footer -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800/80">
                    <a href="{{ route('contests.show', $contest->slug ?: $contest->id) }}" class="px-5 py-2.5 rounded-xl bg-slate-800 text-slate-300 hover:text-white hover:bg-slate-700 text-xs font-bold transition">
                        Cancel
                    </a>

                    <button type="submit" id="submitBtn" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white text-xs font-bold shadow-lg shadow-indigo-600/30 transition transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Submit Design Entry</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('clean_preview_image');
    const uploadPrompt = document.getElementById('uploadPrompt');
    const previewContainer = document.getElementById('imagePreviewContainer');
    const previewImg = document.getElementById('imagePreview');
    const fileNameSpan = document.getElementById('fileName');
    const fileSizeSpan = document.getElementById('fileSize');
    const removeBtn = document.getElementById('removeFileBtn');
    const dropZone = document.getElementById('dropZone');
    const form = document.getElementById('contestSubmitForm');
    const submitBtn = document.getElementById('submitBtn');

    function handleFile(file) {
        if (!file) return;

        if (!file.type.match('image.*')) {
            alert('Please select an image file (PNG, JPG, or WebP).');
            return;
        }

        if (file.size > 10 * 1024 * 1024) {
            alert('File size exceeds the 10MB limit. Please upload an optimized preview.');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            fileNameSpan.textContent = file.name;
            fileSizeSpan.textContent = '(' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)';
            uploadPrompt.classList.add('hidden');
            previewContainer.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    fileInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            handleFile(this.files[0]);
        }
    });

    removeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        fileInput.value = '';
        previewImg.src = '';
        uploadPrompt.classList.remove('hidden');
        previewContainer.classList.add('hidden');
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.add('border-indigo-500', 'bg-indigo-950/20');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.remove('border-indigo-500', 'bg-indigo-950/20');
        }, false);
    });

    dropZone.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files && files.length > 0) {
            fileInput.files = files;
            handleFile(files[0]);
        }
    });

    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Submitting Artwork...</span>
        `;
    });
});
</script>
@endsection
