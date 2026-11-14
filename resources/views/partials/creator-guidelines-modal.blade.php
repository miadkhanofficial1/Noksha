@php
    $canAutoOpenGuidelines = auth()->check()
        && (auth()->user()->is_contributor || auth()->user()->contributor_status === 'approved')
        && !auth()->user()->is_admin
        && !auth()->user()->has_seen_creator_guidelines;
@endphp

<!-- ======================================================== -->
<!-- NOKSHA CREATOR UPLOAD GUIDELINES MODAL                   -->
<!-- ======================================================== -->
<div id="nokshaGuidelinesModal"
     x-cloak
     data-auto-open="{{ $canAutoOpenGuidelines ? 'true' : 'false' }}"
     data-seen-route="{{ route('creator.guidelines.seen') }}"
     data-csrf="{{ csrf_token() }}"
     style="display: none;"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden transition-opacity duration-300"
     onclick="if(event.target === this) { closeUploadGuidelinesModal(); }">

    <div class="relative w-full max-w-2xl bg-white border border-slate-200 rounded-3xl shadow-2xl overflow-hidden my-8 max-h-[90vh] flex flex-col"
         onclick="event.stopPropagation()">

        <!-- Modal Header -->
        <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between bg-slate-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#20165c]/10 border border-[#20165c]/20 text-[#20165c] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 shrink-0 text-[#20165c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900 mb-0">Noksha Creator Upload Guidelines</h3>
                    <p class="text-xs text-slate-500 mb-0">Follow our quality and packaging rules to ensure rapid asset approval</p>
                </div>
            </div>
            <button type="button"
                    onclick="closeUploadGuidelinesModal()"
                    class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 hover:text-slate-800 hover:bg-slate-200 flex items-center justify-center transition-colors cursor-pointer"
                    aria-label="Close modal">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Modal Body (Scrollable) -->
        <div class="p-6 space-y-4 overflow-y-auto text-sm text-slate-700">
            <!-- Rule 1: High-Resolution Cover Preview -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-start gap-3.5">
                <div class="w-8 h-8 rounded-xl bg-[#20165c]/10 text-[#20165c] border border-[#20165c]/20 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900 mb-1">1. High-Resolution Cover Preview</h4>
                    <p class="text-xs text-slate-600 leading-relaxed mb-0">
                        Upload a clean, high-resolution preview image in <strong>16:9</strong> or <strong>4:3</strong> aspect ratio. Supported formats are <strong>JPG, PNG, or WEBP</strong> (Max file size: <strong>5MB</strong>). Avoid pixelated imagery, external watermarks, or intrusive text overlays.
                    </p>
                </div>
            </div>

            <!-- Rule 2: Structured Source & Guideline Files -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-start gap-3.5">
                <div class="w-8 h-8 rounded-xl bg-[#20165c]/10 text-[#20165c] border border-[#20165c]/20 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900 mb-1">2. Structured Source & Guideline Files</h4>
                    <p class="text-xs text-slate-600 leading-relaxed mb-0">
                        Upload up to <strong>5 high-resolution preview images</strong>, <strong>1 main source file</strong> (.psd, .ai, .fig, .eps, .svg, or .zip), and <strong>1 optional guideline PDF</strong>. Noksha automatically organizes, packages, and renames your deliverables into an encrypted, ready-to-download bundle for verified buyers.
                    </p>
                </div>
            </div>

            <!-- Rule 3: Relevant Categories & Keyword Tags -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-emerald-200 flex items-start gap-3.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900 mb-1">3. Relevant Categories & Keyword Tags</h4>
                    <p class="text-xs text-slate-600 leading-relaxed mb-0">
                        Select the most precise marketplace category (UI Kit, Mockups, Templates, Graphics, etc.). Provide at least <strong>3 to 5 comma-separated tags</strong> (e.g., <em>dashboard, dark mode, fintech, responsive</em>) to optimize discoverability on marketplace search engines.
                    </p>
                </div>
            </div>

            <!-- Rule 4: 100% Original Intellectual Property -->
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 flex items-start gap-3.5">
                <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 border border-rose-200 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-rose-700 mb-1">4. 100% Original Intellectual Property</h4>
                    <p class="text-xs text-slate-600 leading-relaxed mb-0">
                        You must hold full ownership and intellectual property rights for every file uploaded. The upload of ripped designs, freeware without commercial re-distribution rights, or copyright-infringing content is strictly prohibited and results in <strong>immediate and permanent account suspension</strong>.
                    </p>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex items-center justify-between flex-wrap gap-3">
            <span class="text-xs text-slate-500 font-medium">Noksha Creator Submission Standards</span>
            <div class="flex items-center gap-3">
                <a href="{{ route('guidelines.download-pdf') }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#20165c] hover:bg-[#181045] text-white font-semibold text-xs shadow-sm transition-all">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Download PDF Guidelines</span>
                </a>
                <button type="button"
                        onclick="closeUploadGuidelinesModal()"
                        class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 font-semibold text-xs transition-colors cursor-pointer">
                    Got it, Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    window.getGuidelinesModal = function() {
        return document.getElementById('nokshaGuidelinesModal');
    };

    window.openUploadGuidelinesModal = function() {
        var modal = window.getGuidelinesModal();
        if (modal) {
            modal.removeAttribute('x-cloak');
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        } else {
            window.open('{{ route("guidelines.download-pdf") }}', '_blank');
        }
    };

    window.closeUploadGuidelinesModal = function(e) {
        if (e && e.target && e.currentTarget && e.target !== e.currentTarget) {
            return;
        }
        var modal = window.getGuidelinesModal();
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.style.overflow = '';

            var shouldAutoOpen = modal.getAttribute('data-auto-open') === 'true';
            if (shouldAutoOpen) {
                modal.setAttribute('data-auto-open', 'false');
                try {
                    localStorage.setItem('noksha_guidelines_seen', 'true');
                } catch (err) {}

                var seenRoute = modal.getAttribute('data-seen-route');
                var csrf = modal.getAttribute('data-csrf');
                if (seenRoute) {
                    fetch(seenRoute, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({})
                    }).catch(function(err) {
                        console.warn('Guidelines seen record failed', err);
                    });
                }
            }
        }
    };

    // Keyboard 'Escape' Dismissal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' || e.keyCode === 27) {
            var modal = window.getGuidelinesModal();
            if (modal && modal.style.display !== 'none' && !modal.classList.contains('hidden')) {
                window.closeUploadGuidelinesModal();
            }
        }
    });

    // Check for auto-opening on page load (Strictly for newly approved contributors who haven't seen guidelines)
    document.addEventListener('DOMContentLoaded', function() {
        var modal = window.getGuidelinesModal();
        if (!modal) return;

        var shouldAutoOpen = modal.getAttribute('data-auto-open') === 'true';
        if (shouldAutoOpen) {
            try {
                if (localStorage.getItem('noksha_guidelines_seen') === 'true') {
                    return; // Already dismissed in this browser session
                }
            } catch (err) {}

            setTimeout(function() {
                window.openUploadGuidelinesModal();
            }, 350);
        }
    });
})();
</script>
