@extends('layouts.app')

@section('title', 'Contributor Identity Verification (KYC) - Noksha')

@section('content')

<!-- CUSTOM KYC VERIFICATION STYLES -->
<style>
    .kyc-dropzone {
        border: 2px dashed #374151;
        border-radius: 1rem;
        background: rgba(7, 13, 30, 0.8);
        padding: 2rem 1.5rem;
        text-align: center;
        transition: all 0.25s ease;
        cursor: pointer;
    }

    .kyc-dropzone:hover {
        border-color: #6366f1;
        background: rgba(11, 19, 41, 0.95);
        transform: translateY(-2px);
    }
</style>

<div class="min-h-screen bg-[#070b14] text-gray-100 py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">

        <!-- Top Breadcrumbs & Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-6">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 text-xs uppercase font-semibold">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-indigo-400 hover:text-indigo-300 text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-indigo-400 hover:text-indigo-300 text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active text-gray-300" aria-current="page">KYC Verification</li>
                    </ol>
                </nav>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-semibold uppercase tracking-wider mb-2">
                    <i class="bi bi-shield-check"></i>
                    <span>Contributor Identity Verification & Escrow Compliance</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight mb-1">Apply to Become a Contributor</h1>
                <p class="text-xs text-gray-400 mb-0">Verify your government identity to unlock design publishing, earn royalty payouts, and receive the Verified Author badge.</p>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-gray-300 bg-[#0b1329] border border-gray-700/80 hover:bg-[#131d36] hover:text-white transition shadow-sm">
                    <i class="bi bi-speedometer2 text-indigo-400"></i>
                    <span>Back to Dashboard</span>
                </a>
            </div>
        </div>

        <!-- Session Status & Alerts -->
        @if (session('warning'))
            <div class="mb-6 p-4 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-300 text-xs flex items-center justify-between gap-3 shadow-lg">
                <div class="flex items-center gap-2.5 font-semibold">
                    <i class="bi bi-exclamation-triangle-fill text-amber-400 text-base"></i>
                    <span>{{ session('warning') }}</span>
                </div>
                <button type="button" class="text-amber-400 hover:text-white" onclick="this.parentElement.remove()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs flex items-center justify-between gap-3 shadow-lg">
                <div class="flex items-center gap-2.5 font-semibold">
                    <i class="bi bi-x-circle-fill text-rose-400 text-base"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" class="text-rose-400 hover:text-white" onclick="this.parentElement.remove()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs flex items-center justify-between gap-3 shadow-lg">
                <div class="flex items-center gap-2.5 font-semibold">
                    <i class="bi bi-check-circle-fill text-emerald-400 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="text-emerald-400 hover:text-white" onclick="this.parentElement.remove()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs shadow-lg">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <i class="bi bi-exclamation-triangle-fill text-rose-400 text-base"></i>
                    <span>Please correct the errors below:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-slate-300 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- VERIFICATION STATUS CARD -->
        <div class="bg-[#0b1329] border border-gray-800 rounded-2xl p-6 mb-6 shadow-xl">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    @if(isset($verification) && $verification->status === 'approved')
                        <div class="w-12 h-12 rounded-xl bg-emerald-500/15 text-emerald-400 flex items-center justify-center text-2xl flex-shrink-0 border border-emerald-500/30">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <div>
                            <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 px-3 py-1 rounded-full text-xs font-semibold inline-block mb-1">
                                <i class="bi bi-check-circle-fill me-1"></i> Contributor Approved ✓
                            </span>
                            <h4 class="text-base font-bold text-white mb-0.5">Verified Creator Status Activated</h4>
                            <p class="text-xs text-gray-400 mb-0">
                                Verified on {{ $verification->reviewed_at ? $verification->reviewed_at->format('M d, Y') : $verification->updated_at->format('M d, Y') }}. Creator publishing tools unlocked!
                            </p>
                        </div>
                    @elseif(isset($verification) && $verification->status === 'pending')
                        <div class="w-12 h-12 rounded-xl bg-amber-500/15 text-amber-400 flex items-center justify-center text-2xl flex-shrink-0 border border-amber-500/30">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div>
                            <span class="bg-amber-500/10 text-amber-400 border border-amber-500/30 px-3 py-1 rounded-full text-xs font-semibold inline-block mb-1">
                                <i class="bi bi-hourglass-split me-1"></i> Pending Approval
                            </span>
                            <h4 class="text-base font-bold text-white mb-0.5">Application Under Compliance Review</h4>
                            <p class="text-xs text-gray-400 mb-0">
                                Submitted on {{ $verification->submitted_at ? $verification->submitted_at->format('M d, Y h:i A') : $verification->created_at->format('M d, Y') }}. Compliance team is verifying your NID/Passport & selfie.
                            </p>
                        </div>
                    @elseif(auth()->user()->isKycRejectedInCooldown())
                        <div class="w-12 h-12 rounded-xl bg-rose-500/15 text-rose-400 flex items-center justify-center text-2xl flex-shrink-0 border border-rose-500/30">
                            <i class="bi bi-shield-x"></i>
                        </div>
                        <div>
                            <span class="bg-rose-500/10 text-rose-400 border border-rose-500/30 px-3 py-1 rounded-full text-xs font-semibold inline-block mb-1">
                                <i class="bi bi-shield-x me-1"></i> 7-Day Resubmission Cooldown
                            </span>
                            <h4 class="text-base font-bold text-white mb-0.5">{{ __('dashboard.kyc_cooldown_title') }}</h4>
                            <p class="text-xs text-rose-400 font-semibold mb-0">
                                Reason: {{ auth()->user()->getKycRejectionReason() }}
                            </p>
                        </div>
                    @elseif(isset($verification) && $verification->status === 'rejected')
                        <div class="w-12 h-12 rounded-xl bg-rose-500/15 text-rose-400 flex items-center justify-center text-2xl flex-shrink-0 border border-rose-500/30">
                            <i class="bi bi-x-circle-fill"></i>
                        </div>
                        <div>
                            <span class="bg-rose-500/10 text-rose-400 border border-rose-500/30 px-3 py-1 rounded-full text-xs font-semibold inline-block mb-1">
                                <i class="bi bi-x-circle-fill me-1"></i> Declined / Resubmission Allowed
                            </span>
                            <h4 class="text-base font-bold text-white mb-0.5">Verification Declined</h4>
                            <p class="text-xs text-rose-400 mb-0">
                                {{ auth()->user()->getKycRejectionReason() }}
                            </p>
                        </div>
                    @else
                        <div class="w-12 h-12 rounded-xl bg-indigo-500/15 text-indigo-400 flex items-center justify-center text-2xl flex-shrink-0 border border-indigo-500/30">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <div>
                            <span class="bg-indigo-500/10 text-indigo-400 border border-indigo-500/30 px-3 py-1 rounded-full text-xs font-semibold inline-block mb-1">
                                <i class="bi bi-info-circle-fill me-1"></i> Application Ready
                            </span>
                            <h4 class="text-base font-bold text-white mb-0.5">Submit Identity Credentials</h4>
                            <p class="text-xs text-gray-400 mb-0">Please submit your official government ID details and verification selfie below.</p>
                        </div>
                    @endif
                </div>

                <div class="text-right">
                    <span class="text-[11px] font-bold text-gray-400 block mb-1 uppercase tracking-wider">Security Standard</span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#070d1e] text-indigo-400 border border-gray-700/60 text-xs font-mono">
                        <i class="bi bi-lock-fill text-emerald-400"></i> Encrypted Storage
                    </span>
                </div>
            </div>
        </div>

        @if(auth()->user()->isKycRejectedInCooldown())
            <!-- COOLDOWN ACTIVE CARD -->
            <div class="bg-[#0b1329] border border-rose-500/30 rounded-2xl p-8 text-center shadow-2xl">
                <div class="w-16 h-16 rounded-2xl bg-rose-500/10 text-rose-400 flex items-center justify-center mx-auto mb-4 text-3xl border border-rose-500/20">
                    <i class="bi bi-shield-x"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">
                    {{ __('dashboard.kyc_cooldown_title') }}
                </h3>
                <p class="text-xs text-gray-400 mb-6 max-w-lg mx-auto">
                    {{ __('dashboard.kyc_cooldown_desc', ['reason' => auth()->user()->getKycRejectionReason(), 'date' => auth()->user()->getKycCooldownRemainingDate()]) }}
                </p>
                <div class="p-4 bg-[#070d1e] rounded-xl border border-rose-500/20 text-left text-xs mb-6 max-w-md mx-auto space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Rejection Feedback:</span>
                        <span class="font-bold text-rose-400">{{ auth()->user()->getKycRejectionReason() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Status:</span>
                        <span class="bg-rose-500/10 text-rose-400 border border-rose-500/30 px-2 py-0.5 rounded text-[11px] font-semibold">7-Day Lock Active</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Reapplication Unlocks:</span>
                        <span class="font-bold text-white font-mono">{{ auth()->user()->getKycCooldownRemainingDate() }}</span>
                    </div>
                </div>
                <div>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs transition shadow-lg shadow-indigo-600/20">
                        <i class="bi bi-arrow-left"></i>
                        <span>Return to Dashboard</span>
                    </a>
                </div>
            </div>
        @else
            <!-- MODERN KYC SUBMISSION FORM -->
            <div class="bg-[#0b1329] border border-gray-800 rounded-2xl p-6 sm:p-8 max-w-4xl mx-auto shadow-2xl mb-8">
                <form action="{{ route('seller.verification.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- SECTION 1: PERSONAL IDENTITY -->
                    <div class="mb-8">
                        <div class="mb-5 pb-3 border-b border-gray-800">
                            <div class="text-lg font-bold text-white tracking-wide flex items-center gap-2">
                                <i class="bi bi-person-vcard-fill text-indigo-400"></i>
                                <span>1. Personal Identity Details</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Provide your legal credentials as registered on your official government identification card.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Full Name -->
                            <div>
                                <label for="full_name" class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2">
                                    Full Legal Name <span class="text-rose-400">*</span>
                                </label>
                                <input type="text" 
                                       name="full_name" 
                                       id="full_name" 
                                       class="w-full bg-[#070d1e] border border-gray-800 text-gray-200 placeholder-gray-500 rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all duration-200 @error('full_name') border-rose-500 @enderror" 
                                       placeholder="Legal full name" 
                                       value="{{ old('full_name', $verification->full_name ?? auth()->user()->name) }}" 
                                       required>
                                @error('full_name')
                                    <div class="text-rose-400 text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone / WhatsApp -->
                            <div>
                                <label for="phone" class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2">
                                    Phone / WhatsApp Number <span class="text-rose-400">*</span>
                                </label>
                                <input type="text" 
                                       name="phone" 
                                       id="phone" 
                                       class="w-full bg-[#070d1e] border border-gray-800 text-gray-200 placeholder-gray-500 rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all duration-200 @error('phone') border-rose-500 @enderror" 
                                       placeholder="01XXXXXXXXX" 
                                       value="{{ old('phone', auth()->user()->phone) }}" 
                                       required>
                                @error('phone')
                                    <div class="text-rose-400 text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- National ID / Passport Number -->
                            <div>
                                <label for="id_number" class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2">
                                    National ID / Passport Number <span class="text-rose-400">*</span>
                                </label>
                                <input type="text" 
                                       name="id_number" 
                                       id="id_number" 
                                       class="w-full bg-[#070d1e] border border-gray-800 text-gray-200 placeholder-gray-500 rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm font-mono transition-all duration-200 @error('id_number') border-rose-500 @enderror" 
                                       placeholder="e.g. 199XXXXXXXXXX or Passport Number" 
                                       value="{{ old('id_number', $verification->id_number ?? '') }}" 
                                       required>
                                @error('id_number')
                                    <div class="text-rose-400 text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Portfolio Link -->
                            <div>
                                <label for="portfolio_link" class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2">
                                    Portfolio Link <span class="text-rose-400">*</span>
                                </label>
                                <input type="url" 
                                       name="portfolio_link" 
                                       id="portfolio_link" 
                                       class="w-full bg-[#070d1e] border border-gray-800 text-gray-200 placeholder-gray-500 rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all duration-200 @error('portfolio_link') border-rose-500 @enderror" 
                                       placeholder="https://behance.net/... or https://dribbble.com/..." 
                                       value="{{ old('portfolio_link', $verification->portfolio_link ?? '') }}" 
                                       required>
                                @error('portfolio_link')
                                    <div class="text-rose-400 text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Government ID Type -->
                            <div class="md:col-span-2">
                                <label for="document_type" class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2">
                                    Government ID Document Type <span class="text-rose-400">*</span>
                                </label>
                                <select name="document_type" 
                                        id="document_type" 
                                        class="w-full bg-[#070d1e] border border-gray-800 text-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all duration-200 @error('document_type') border-rose-500 @enderror" 
                                        required>
                                    <option value="nid" {{ old('document_type', $verification->document_type ?? 'nid') == 'nid' ? 'selected' : '' }}>NID Card (National Identity Card)</option>
                                    <option value="passport" {{ old('document_type', $verification->document_type ?? '') == 'passport' ? 'selected' : '' }}>International Passport</option>
                                    <option value="driving_license" {{ old('document_type', $verification->document_type ?? '') == 'driving_license' ? 'selected' : '' }}>Driving License</option>
                                </select>
                                @error('document_type')
                                    <div class="text-rose-400 text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: GOVERNMENT ID DOCUMENT UPLOAD -->
                    <div class="mb-8">
                        <div class="mb-4 pb-3 border-b border-gray-800">
                            <div class="text-lg font-bold text-white tracking-wide flex items-center gap-2">
                                <i class="bi bi-file-earmark-person-fill text-indigo-400"></i>
                                <span>2. Government Identity Document File</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Upload clear front & back scan of NID, Passport, or Driving License.</p>
                        </div>

                        <div class="kyc-dropzone group" onclick="document.getElementById('document_file').click();">
                            <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 flex items-center justify-center mx-auto mb-3 text-2xl group-hover:scale-110 transition-transform">
                                <i class="bi bi-cloud-arrow-up"></i>
                            </div>
                            <h5 class="text-sm font-bold text-white mb-1">Click or Drag Government ID File Here</h5>
                            <p class="text-xs text-gray-400 mb-3">Allowed Formats: <span class="text-indigo-400 font-mono font-semibold">.PDF, .JPG, .PNG max 10MB</span></p>
                            <div>
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-[#070d1e] text-indigo-300 border border-indigo-500/30 text-xs font-semibold" id="docFileName">
                                    <i class="bi bi-paperclip"></i>
                                    <span>{{ isset($verification->document_file) ? 'Replace Document File' : 'Choose Document File' }}</span>
                                </span>
                            </div>
                            <input type="file" 
                                   name="document_file" 
                                   id="document_file" 
                                   class="hidden" 
                                   accept=".jpg,.jpeg,.png,.pdf" 
                                   {{ isset($verification) ? '' : 'required' }} 
                                   onchange="document.getElementById('docFileName').querySelector('span').textContent = this.files[0].name">
                        </div>
                        @error('document_file')
                            <div class="text-rose-400 text-xs mt-1.5">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- SECTION 3: SELFIE PHOTO UPLOAD -->
                    <div class="mb-8">
                        <div class="mb-4 pb-3 border-b border-gray-800">
                            <div class="text-lg font-bold text-white tracking-wide flex items-center gap-2">
                                <i class="bi bi-camera-fill text-indigo-400"></i>
                                <span>3. Face Verification Selfie</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Upload a clear photo of your face holding your identity card next to you.</p>
                        </div>

                        <div class="kyc-dropzone group" onclick="document.getElementById('selfie_file').click();">
                            <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 flex items-center justify-center mx-auto mb-3 text-2xl group-hover:scale-110 transition-transform">
                                <i class="bi bi-person-bounding-box"></i>
                            </div>
                            <h5 class="text-sm font-bold text-white mb-1">Click or Drag Selfie Photo Here</h5>
                            <p class="text-xs text-gray-400 mb-3">Allowed Formats: <span class="text-indigo-400 font-mono font-semibold">.JPG, .PNG max 5MB</span></p>
                            <div>
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-[#070d1e] text-indigo-300 border border-indigo-500/30 text-xs font-semibold" id="selfieFileName">
                                    <i class="bi bi-camera"></i>
                                    <span>{{ isset($verification->selfie_file) ? 'Replace Selfie Photo' : 'Choose Selfie Photo' }}</span>
                                </span>
                            </div>
                            <input type="file" 
                                   name="selfie_file" 
                                   id="selfie_file" 
                                   class="hidden" 
                                   accept="image/*" 
                                   {{ isset($verification) ? '' : 'required' }} 
                                   onchange="document.getElementById('selfieFileName').querySelector('span').textContent = this.files[0].name">
                        </div>
                        @error('selfie_file')
                            <div class="text-rose-400 text-xs mt-1.5">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- SECTION 4: VIDEO VERIFICATION (MAX 15 SECONDS) -->
                    <div class="mb-8">
                        <div class="mb-4 pb-3 border-b border-gray-800">
                            <div class="text-lg font-bold text-white tracking-wide flex items-center gap-2">
                                <i class="bi bi-record-circle-fill text-indigo-400"></i>
                                <span>4. Verification Video Clip</span>
                                <span class="text-[11px] font-mono px-2 py-0.5 rounded bg-gray-800 text-gray-400 border border-gray-700/60 font-normal">Optional / Max 15s</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Upload a short 5–15 second selfie video confirming your author identity.</p>
                        </div>

                        <div class="kyc-dropzone group" onclick="document.getElementById('video_file').click();">
                            <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 flex items-center justify-center mx-auto mb-3 text-2xl group-hover:scale-110 transition-transform">
                                <i class="bi bi-file-earmark-play"></i>
                            </div>
                            <h5 class="text-sm font-bold text-white mb-1">Click or Drag Video File Here</h5>
                            <p class="text-xs text-gray-400 mb-3">Allowed Formats: <span class="text-indigo-400 font-mono font-semibold">.MP4, .WEBM max 15 sec / 50MB</span></p>
                            <div>
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-[#070d1e] text-indigo-300 border border-indigo-500/30 text-xs font-semibold" id="videoFileName">
                                    <i class="bi bi-play-circle"></i>
                                    <span>{{ isset($verification->video_file) ? 'Replace Video File' : 'Choose Video File' }}</span>
                                </span>
                            </div>
                            <input type="file" 
                                   name="video_file" 
                                   id="video_file" 
                                   class="hidden" 
                                   accept="video/mp4,video/webm" 
                                   onchange="document.getElementById('videoFileName').querySelector('span').textContent = this.files[0].name">
                        </div>
                        @error('video_file')
                            <div class="text-rose-400 text-xs mt-1.5">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- SECTION 5: AGREEMENT CHECKBOX & SUBMISSION -->
                    <div class="mb-6">
                        <div class="p-4 bg-[#070d1e] rounded-xl border border-gray-800 flex items-start gap-3">
                            <input class="w-5 h-5 accent-indigo-600 rounded cursor-pointer mt-0.5 flex-shrink-0" 
                                   type="checkbox" 
                                   name="agreement" 
                                   id="agreement" 
                                   value="1" 
                                   required>
                            <label class="text-xs font-semibold text-gray-300 cursor-pointer leading-relaxed" for="agreement">
                                I confirm under penalty of account suspension that all submitted identity information and government document scans are genuine, accurate, and legally belong to me.
                            </label>
                        </div>
                        @error('agreement')
                            <div class="text-rose-400 text-xs mt-1.5">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-gray-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <span class="text-xs text-gray-400 flex items-center gap-1.5">
                            <i class="bi bi-shield-lock-fill text-emerald-400"></i>
                            <span>Protected by Noksha Compliance & Encryption Protocols.</span>
                        </span>

                        <button type="submit" 
                                class="w-full sm:w-auto bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-semibold py-3.5 px-8 rounded-xl shadow-lg shadow-indigo-600/30 transition transform active:scale-[0.99] text-sm flex items-center justify-center gap-2">
                            <i class="bi bi-shield-check text-base"></i>
                            <span>Submit Verification Application</span>
                        </button>
                    </div>

                </form>
            </div>
        @endif

    </div>
</div>

@endsection
