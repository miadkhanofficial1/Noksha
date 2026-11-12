@extends('layouts.admin')

@section('title', 'Verification Review - ' . $verification->full_name . ' - Noksha Admin HQ')
@section('page_title', 'KYC Review')
@section('page_heading', 'Contributor Verification Details')

@section('content')
<div class="space-y-6">

    <!-- TOP BAR -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between flex-wrap gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-[#20165c] border border-purple-200">
                    <i class="bi bi-shield-check me-1"></i> Contributor Application
                </span>
                @if($verification->status === 'approved')
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <i class="bi bi-check-circle-fill me-1"></i> Approved
                    </span>
                @elseif($verification->status === 'pending')
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 animate-pulse">
                        <i class="bi bi-clock-history me-1"></i> Pending Review
                    </span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                        <i class="bi bi-x-circle-fill me-1"></i> Rejected
                    </span>
                @endif
            </div>
            <h2 class="text-xl font-black text-[#121826]">{{ $verification->full_name }}</h2>
            <div class="text-xs text-slate-500 font-mono mt-0.5">
                Account: <span class="text-[#20165c] font-semibold">{{ $verification->user ? $verification->user->email : 'N/A' }}</span> • Country: {{ $verification->country ?? 'Bangladesh' }}
            </div>
        </div>

        <div>
            <a href="{{ route('admin.verifications.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition border border-slate-200 inline-flex items-center gap-1.5">
                <i class="bi bi-arrow-left"></i> Back to Review Queue
            </a>
        </div>
    </div>

    <!-- MAIN TWO COLUMN GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- LEFT COLUMN: APPLICANT METADATA & ACTIONS (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Metadata Card -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-4">
                <h4 class="font-bold text-sm text-[#121826] pb-3 border-b border-slate-100 flex items-center gap-2">
                    <i class="bi bi-person-vcard text-[#20165c]"></i> Identity Details
                </h4>

                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">Full Legal Name</span>
                    <div class="text-sm font-bold text-[#121826]">{{ $verification->full_name }}</div>
                </div>

                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">Government ID Number</span>
                    <div class="text-sm font-mono font-bold text-[#20165c]">
                        {{ $verification->id_number ?? $verification->user?->nid_or_passport_number ?? 'N/A' }}
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">ID Type</span>
                        <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-700 uppercase border border-slate-200">
                            {{ $verification->document_type ?? 'NID' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">Country</span>
                        <div class="text-xs font-semibold text-slate-800">{{ $verification->country ?? 'Bangladesh' }}</div>
                    </div>
                </div>

                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">Date of Birth</span>
                    <div class="text-xs text-slate-700">
                        {{ $verification->date_of_birth ? $verification->date_of_birth->format('F d, Y') : 'N/A' }}
                    </div>
                </div>

                @if($verification->portfolio_link)
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">Portfolio Link</span>
                        <a href="{{ $verification->portfolio_link }}" target="_blank" class="text-xs font-semibold text-[#20165c] hover:underline flex items-center gap-1">
                            <i class="bi bi-box-arrow-up-right"></i> {{ $verification->portfolio_link }}
                        </a>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-100 text-[11px] text-slate-500 font-mono">
                    <div>
                        <span class="block text-[10px] text-slate-400 uppercase font-sans">Submitted</span>
                        {{ $verification->submitted_at ? $verification->submitted_at->format('M d, Y h:i A') : $verification->created_at->format('M d, Y') }}
                    </div>
                    @if($verification->reviewed_at)
                        <div>
                            <span class="block text-[10px] text-slate-400 uppercase font-sans">Reviewed</span>
                            {{ $verification->reviewed_at->format('M d, Y h:i A') }}
                        </div>
                    @endif
                </div>

                @if($verification->admin_note)
                    <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-800">
                        <strong class="font-bold text-rose-900 block mb-1">Previous Rejection Note:</strong>
                        {{ $verification->admin_note }}
                    </div>
                @endif
            </div>

            <!-- Decisions & Action Buttons -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-3">
                <h4 class="font-bold text-sm text-[#121826] pb-3 border-b border-slate-100 flex items-center gap-2">
                    <i class="bi bi-shield-lock text-[#20165c]"></i> Compliance Decisions
                </h4>

                @if($verification->status !== 'approved')
                    <form action="{{ route('admin.verifications.approve', $verification->id) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('Approve this contributor and grant full creator selling tools?')" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition flex items-center justify-center gap-2 shadow-sm cursor-pointer">
                            <i class="bi bi-patch-check-fill text-base"></i>
                            <span>Approve & Verify Contributor</span>
                        </button>
                    </form>
                @endif

                @if($verification->status !== 'rejected')
                    <button type="button" onclick="openShowRejectModal()" class="w-full py-2.5 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white font-bold text-xs transition border border-rose-200 flex items-center justify-center gap-2 cursor-pointer">
                        <i class="bi bi-x-circle-fill text-base"></i>
                        <span>Reject Application (7-Day Lock)</span>
                    </button>
                @endif
            </div>

        </div>

        <!-- RIGHT COLUMN: DOCUMENT, SELFIE & VIDEO MEDIA PREVIEWS (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
            @php
                $idPath = $verification->id_file_path ?? $verification->id_document_path ?? $verification->document_file ?? null;
                $docUrl = $idPath ? route('admin.kyc.document', ['type' => 'ids', 'filename' => basename($idPath)]) : null;
                $isPdf = $idPath && str_ends_with(strtolower($idPath), '.pdf');
                $selfie = $verification->selfie_file_path ?? $verification->selfie_path ?? $verification->selfie_file ?? null;
                $selfieUrl = $selfie ? route('admin.kyc.document', ['type' => 'selfies', 'filename' => basename($selfie)]) : null;
                $videoFile = $verification->video_file ?? $verification->video_file_path ?? null;
                $videoUrl = $videoFile ? route('admin.kyc.document', ['type' => 'videos', 'filename' => basename($videoFile)]) : null;
            @endphp

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-6">
                <h4 class="font-bold text-sm text-[#121826] pb-3 border-b border-slate-100 flex items-center gap-2">
                    <i class="bi bi-images text-[#20165c]"></i> KYC Document Media Previews
                </h4>

                <!-- 1. Government ID Preview -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                            <i class="bi bi-file-earmark-person text-[#20165c]"></i> 1. Government Identity Document
                        </span>
                        @if($docUrl)
                            <a href="{{ $docUrl }}" target="_blank" class="text-xs font-semibold text-[#20165c] hover:underline flex items-center gap-1">
                                <i class="bi bi-box-arrow-up-right"></i> Open Original
                            </a>
                        @endif
                    </div>
                    <div class="h-64 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center p-2">
                        @if($docUrl)
                            @if($isPdf)
                                <div class="text-center p-4">
                                    <i class="bi bi-file-earmark-pdf-fill text-rose-500 text-5xl mb-2 block"></i>
                                    <div class="font-bold text-slate-800 text-xs mb-2">PDF Document Attached</div>
                                    <a href="{{ $docUrl }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-rose-600 text-white font-bold text-xs shadow-sm hover:bg-rose-700 transition">
                                        <i class="bi bi-file-arrow-down-fill"></i> Download / View PDF Scan
                                    </a>
                                </div>
                            @else
                                <img src="{{ $docUrl }}" alt="Government ID" class="w-full h-full object-contain cursor-zoom-in" onclick="window.open(this.src, '_blank')" onerror="this.onerror=null; this.src='/assets/images/placeholder-avatar.png';">
                            @endif
                        @else
                            <div class="text-slate-400 text-xs text-center">
                                <i class="bi bi-file-earmark-x text-3xl block mb-1 opacity-50"></i>
                                No document file uploaded
                            </div>
                        @endif
                    </div>
                </div>

                <!-- 2. Face Selfie Preview -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                            <i class="bi bi-camera text-[#20165c]"></i> 2. Face Selfie Photo
                        </span>
                        @if($selfieUrl)
                            <a href="{{ $selfieUrl }}" target="_blank" class="text-xs font-semibold text-[#20165c] hover:underline flex items-center gap-1">
                                <i class="bi bi-box-arrow-up-right"></i> Open Original
                            </a>
                        @endif
                    </div>
                    <div class="h-64 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center p-2">
                        @if($selfieUrl)
                            <img src="{{ $selfieUrl }}" alt="Face Selfie" class="w-full h-full object-contain cursor-zoom-in" onclick="window.open(this.src, '_blank')" onerror="this.onerror=null; this.src='/assets/images/placeholder-avatar.png';">
                        @else
                            <div class="text-slate-400 text-xs text-center">
                                <i class="bi bi-person-bounding-box text-3xl block mb-1 opacity-50"></i>
                                No selfie photo uploaded
                            </div>
                        @endif
                    </div>
                </div>

                <!-- 3. Video Verification Preview -->
                @if($videoUrl)
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                <i class="bi bi-record-circle text-[#20165c]"></i> 3. Verification Video (Max 15s)
                            </span>
                            <a href="{{ $videoUrl }}" download class="text-xs font-semibold text-[#20165c] hover:underline flex items-center gap-1">
                                <i class="bi bi-download"></i> Download Video
                            </a>
                        </div>
                        <div class="rounded-xl bg-slate-100 border border-slate-200 overflow-hidden p-2 text-center">
                            <video controls class="w-full rounded-lg max-h-60 mx-auto">
                                <source src="{{ $videoUrl }}" type="video/mp4">
                                Your browser does not support HTML5 video playback.
                            </video>
                        </div>
                    </div>
                @endif

            </div>
        </div>

    </div>

</div>

<!-- REJECT REASON MODAL -->
<div id="showRejectModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 border border-slate-200 shadow-2xl">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <h3 class="font-bold text-sm text-[#121826] flex items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill text-rose-500"></i> Reject Contributor Verification
            </h3>
            <button type="button" onclick="closeShowRejectModal()" class="text-slate-400 hover:text-slate-600">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.verifications.reject', $verification->id) }}" method="POST">
            @csrf
            <p class="text-xs text-slate-600 mb-3">
                Provide a clear reason for declining seller identity application (Applicant will be locked for 7 days):
            </p>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Rejection Feedback</label>
                <textarea name="admin_note" rows="3" required placeholder="e.g. Document image is blurry or NID numbers do not match legal full name." class="w-full px-3 py-2 rounded-xl text-xs bg-slate-50 border border-slate-200 text-[#121826] placeholder-slate-400 focus:bg-white focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500">{{ $verification->admin_note }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-2">
                <button type="button" onclick="closeShowRejectModal()" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-sm">
                    Confirm Rejection & Lock 7 Days
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openShowRejectModal() {
        document.getElementById('showRejectModal').classList.remove('hidden');
    }
    function closeShowRejectModal() {
        document.getElementById('showRejectModal').classList.add('hidden');
    }
</script>
@endsection