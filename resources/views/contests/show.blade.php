@extends('layouts.app')

@section('title', $contest->title . ' - Contest Details - Noksha')

@section('content')

<!-- CUSTOM SINGLE CONTEST STYLES -->
<style>
    .contest-detail-bg {
        background: linear-gradient(180deg, #F8F5FF 0%, #FFFFFF 100%);
        min-height: 100vh;
    }

    .contest-hero-card {
        background: linear-gradient(135deg, #1E1B4B 0%, #312E81 50%, #4338CA 100%);
        border-radius: 1.75rem;
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }

    .entry-card-figma {
        background: #ffffff;
        border-radius: 1.25rem !important;
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        box-shadow: 0 10px 25px -5px rgba(108, 76, 241, 0.08) !important;
        overflow: hidden;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
    }

    .entry-card-figma:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 35px -8px rgba(108, 76, 241, 0.18) !important;
    }

    .winner-glow-card {
        border: 2px solid #F59E0B !important;
        box-shadow: 0 0 30px rgba(245, 158, 11, 0.25) !important;
    }

    .glass-sidebar-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(108, 76, 241, 0.15) !important;
        border-radius: 1.5rem !important;
        box-shadow: 0 15px 35px -10px rgba(108, 76, 241, 0.12) !important;
    }

    .btn-purple-cta {
        background: linear-gradient(135deg, #6C4CF1 0%, #5A3DE0 100%);
        color: #ffffff !important;
        border: none;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 6px 16px -4px rgba(108, 76, 241, 0.35);
    }

    .btn-purple-cta:hover {
        background: linear-gradient(135deg, #5A3DE0 0%, #4327C6 100%);
        transform: translateY(-2px);
        box-shadow: 0 12px 25px -4px rgba(108, 76, 241, 0.45);
        color: #ffffff !important;
    }

    .entry-preview-box {
        position: relative;
        height: 240px;
        background: #0F172A;
        overflow: hidden;
        cursor: pointer;
    }

    .entry-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.4s ease;
    }

    .entry-preview-box:hover img {
        transform: scale(1.03);
    }

    .zoom-overlay {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.25s ease;
    }

    .entry-preview-box:hover .zoom-overlay {
        opacity: 1;
    }

    .btn-like {
        transition: all 0.2s ease;
        border-radius: 9999px;
    }

    .btn-like.liked {
        background: #FEE2E2 !important;
        color: #EF4444 !important;
        border-color: #FECACA !important;
    }

    .btn-like.liked i {
        color: #EF4444 !important;
    }

    .rating-star-interactive {
        cursor: pointer;
        font-size: 1.5rem;
        color: #CBD5E1;
        transition: color 0.15s ease;
    }

    .rating-star-interactive:hover,
    .rating-star-interactive.active {
        color: #F59E0B;
    }

    .drag-drop-zone {
        border: 2px dashed rgba(108, 76, 241, 0.35);
        background: #F8FAFC;
        cursor: pointer;
        border-radius: 1rem;
        transition: all 0.25s ease;
    }

    .drag-drop-zone:hover, .drag-drop-zone.dragover {
        border-color: #6C4CF1 !important;
        background: #EEF2FF !important;
    }

    .handover-console-card {
        border: 1px solid rgba(245, 158, 11, 0.25) !important;
        background: #FFFFFF;
        border-radius: 1.25rem !important;
        box-shadow: 0 10px 30px -5px rgba(245, 158, 11, 0.1) !important;
    }
</style>

<div class="contest-detail-bg py-4 py-lg-5">
    <div class="container py-2">
        
        <!-- BREADCRUMB NAVIGATION -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small fw-semibold text-muted mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-primary"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contests.index') }}" class="text-decoration-none text-primary">Contests</a></li>
                <li class="breadcrumb-item active text-dark" aria-current="page">{{ Str::limit($contest->title, 35) }}</li>
            </ol>
        </nav>


        <!-- HERO HEADER CARD -->
        <div class="card contest-hero-card p-4 p-md-5 mb-5 shadow-lg">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-bold extra-small">
                            {{ $contest->display_category }}
                        </span>

                        @if($contest->is_guaranteed)
                            <span class="badge bg-success text-white rounded-pill px-3 py-1.5 extra-small fw-bold border border-white border-opacity-25 shadow-sm">
                                <i class="bi bi-shield-fill-check me-1 text-warning"></i> 100% Guaranteed Escrow
                            </span>
                        @endif

                        @if($contest->status === 'active')
                            <span class="badge bg-success bg-opacity-25 text-white rounded-pill px-3 py-1.5 extra-small fw-bold border border-white border-opacity-25">
                                <i class="bi bi-record-fill me-1 text-success"></i> Accepting Submissions
                            </span>
                        @elseif($contest->status === 'judging')
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 extra-small fw-bold">
                                <i class="bi bi-hourglass-split me-1"></i> In Review / Judging
                            </span>
                        @elseif($contest->status === 'handover')
                            <span class="badge bg-primary text-white rounded-pill px-3 py-1.5 extra-small fw-bold shadow-sm">
                                <i class="bi bi-shield-lock-fill me-1 text-warning"></i> File Handover in Progress
                            </span>
                        @elseif($contest->status === 'completed')
                            <span class="badge bg-secondary text-white rounded-pill px-3 py-1.5 extra-small fw-bold">
                                <i class="bi bi-check-circle-fill me-1"></i> Winner Announced
                            </span>
                        @endif
                    </div>

                    <h1 class="display-6 fw-extrabold text-white mb-3">{{ $contest->title }}</h1>
                    <p class="text-white text-opacity-90 fs-6 mb-4 lh-lg" style="max-width: 720px;">
                        {{ $contest->description }}
                    </p>

                    <!-- Meta Details -->
                    <div class="d-flex flex-wrap align-items-center gap-4 text-white text-opacity-80 small font-monospace">
                        <div class="d-flex align-items-center gap-1.5">
                            <i class="bi bi-person-circle text-warning fs-6"></i>
                            @if($contest->organizer)
                                <span>Organized by <a href="{{ route('user.profile', $contest->organizer->username ?? $contest->organizer->id) }}" class="text-white fw-bold text-decoration-underline hover-warning">{{ $contest->organizer->name }}</a></span>
                            @else
                                <span>Organized by <strong>Noksha Official</strong></span>
                            @endif
                        </div>
                        <div>
                            <i class="bi bi-images text-warning me-1"></i>
                            <strong>{{ $contest->entries->count() ?: $contest->submissions->count() }}</strong> Submissions
                        </div>
                        <div>
                            <i class="bi bi-clock-history text-warning me-1"></i>
                            <strong>{{ $contest->remaining_time }}</strong>
                            <span class="text-white text-opacity-60">({{ $contest->effective_deadline ? $contest->effective_deadline->format('M d, Y') : 'Ongoing' }})</span>
                        </div>
                    </div>

                    <!-- Attachment / Dimensions bar -->
                    @if($contest->required_dimensions || $contest->attachment_file)
                        <div class="d-flex flex-wrap align-items-center gap-3 mt-4 pt-3 border-top border-white border-opacity-15">
                            @if($contest->required_dimensions)
                                <div class="badge bg-white bg-opacity-10 text-white rounded-pill px-3 py-2 extra-small fw-normal">
                                    <i class="bi bi-aspect-ratio text-warning me-1"></i> Required Format: <strong>{{ $contest->required_dimensions }}</strong>
                                </div>
                            @endif

                            @if($contest->attachment_file)
                                <a href="{{ asset('storage/' . $contest->attachment_file) }}" target="_blank" download class="btn btn-outline-light btn-sm rounded-pill px-3 py-1.5 extra-small fw-bold">
                                    <i class="bi bi-paperclip me-1 text-warning"></i> Download Brief Attachment
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="col-lg-4 text-lg-end">
                    <div class="p-4 bg-white bg-opacity-10 rounded-4 border border-white border-opacity-25 text-center">
                        <div class="extra-small text-uppercase fw-bold text-white text-opacity-75">1st Place Prize Bounty</div>
                        <div class="display-5 fw-extrabold text-warning my-1 font-monospace">৳{{ number_format($contest->prize_amount, 0) }}</div>
                        <div class="extra-small text-white text-opacity-90">
                            @if($contest->is_guaranteed)
                                <i class="bi bi-shield-check text-success me-1"></i> 100% Escrow Funded
                            @else
                                <i class="bi bi-clock me-1"></i> Verified Tournament
                            @endif
                        </div>

                        @if($isOrganizer && $contest->status === 'active')
                            <div class="mt-3 pt-3 border-top border-white border-opacity-20">
                                <span class="badge bg-white text-primary rounded-pill px-3 py-1 extra-small fw-bold">
                                    <i class="bi bi-star-fill text-warning me-1"></i> You are the Organizer
                                </span>
                            </div>
                        @else
                            <div class="mt-3 pt-3 border-top border-white border-opacity-20">
                                @if($contest->status === 'active')
                                    @if(auth()->check() && $contest->hasUserEntered(auth()->id()))
                                        <span class="badge bg-success bg-opacity-25 text-white rounded-pill px-3 py-1.5 extra-small fw-bold">
                                            <i class="bi bi-check2-circle me-1"></i> Entry Submitted
                                        </span>
                                    @else
                                        <a href="{{ route('contests.entries.create', $contest->slug ?: $contest->id) }}" class="btn btn-warning rounded-pill px-3 py-2 fw-extrabold extra-small text-dark shadow w-100 d-inline-flex align-items-center justify-content-center gap-1.5">
                                            <i class="bi bi-pencil-square"></i>
                                            <span>Submit Your Design Entry</span>
                                        </a>
                                    @endif
                                @elseif(in_array($contest->status, ['handover', 'completed']))
                                    <a href="{{ route('contests.handover.show', $contest->slug ?: $contest->id) }}" class="btn btn-light rounded-pill px-3 py-2 fw-extrabold extra-small text-dark shadow w-100 d-inline-flex align-items-center justify-content-center gap-1.5">
                                        <i class="bi bi-shield-lock text-warning"></i>
                                        <span>Open Handover Workspace</span>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- STATUS BANNER (PHASE 4) -->
        @if($contest->status === 'handover')
            <div class="alert alert-warning border-0 rounded-4 px-4 py-3 mb-4 d-flex flex-wrap align-items-center justify-content-between gap-3 shadow-sm text-dark">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-2 bg-warning bg-opacity-25 rounded-circle text-warning fs-5 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <span class="fw-bold small d-block">Winner Selected – Awaiting editable source file handover and verification.</span>
                        <span class="extra-small text-muted">Contest submissions are locked. Escrow funds will be released upon buyer file verification.</span>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('contests.handover.show', $contest->slug ?: $contest->id) }}" class="btn btn-sm btn-dark rounded-pill px-3 py-1.5 extra-small fw-bold text-white shadow-sm">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Open Handover Workspace
                    </a>
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 extra-small fw-bold">Handover Stage</span>
                </div>
            </div>
        @elseif($contest->status === 'completed')
            <div class="alert border-0 rounded-4 px-4 py-3 mb-4 d-flex flex-wrap align-items-center justify-content-between gap-3 shadow-sm text-dark" style="background-color: #ecfdf5; border-left: 4px solid #10b981 !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-2 rounded-circle text-success fs-5 d-flex align-items-center justify-content-center" style="background-color: #d1fae5; width: 42px; height: 42px;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <span class="fw-bold small d-block text-success">Contest Completed – Payout released to winner.</span>
                        <span class="extra-small text-muted">All design assets verified and ৳{{ number_format($contest->prize_amount, 0) }} escrow prize disbursed.</span>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('contests.handover.show', $contest->slug ?: $contest->id) }}" class="btn btn-sm btn-success rounded-pill px-3 py-1.5 extra-small fw-bold text-white shadow-sm">
                        <i class="bi bi-file-earmark-check me-1"></i> View Handover Workspace
                    </a>
                    <span class="badge bg-success rounded-pill px-3 py-1.5 extra-small fw-bold text-white">Completed</span>
                </div>
            </div>
        @endif

        <!-- DEDICATED HANDOVER MANAGEMENT CONSOLE (PHASE 4) -->
        @php
            $winnerEntry = $contest->winningEntry ?? $contest->entries->firstWhere('is_winner', true);
            $winnerUser = $contest->winner ?? ($winnerEntry ? $winnerEntry->user : null);
            $currentUser = auth()->user();
            $isWinner = $currentUser && $winnerUser && ($currentUser->id === $winnerUser->id);
            $isBuyer = $currentUser && ($contest->user_id === $currentUser->id);
            $isAdmin = $currentUser && $currentUser->isAdmin();
            $handoverStatus = $winnerEntry?->handover_status ?? 'pending';
            $handoverFiles = is_array($winnerEntry?->handover_files) ? $winnerEntry->handover_files : [];
            $latestHandoverFile = !empty($handoverFiles) ? end($handoverFiles) : null;
        @endphp

        @if(in_array($contest->status, ['handover', 'completed']) && ($winnerUser || $winnerEntry))
            <div class="card handover-console-card p-4 mb-5 border-0 shadow-sm">
                <!-- Console Top Header -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pb-3 mb-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fs-4">🏆</span>
                        <div>
                            <h6 class="fw-bold text-dark mb-0">Handover Management Console</h6>
                            <span class="extra-small text-muted">
                                Winner: @if($winnerUser)<a href="{{ route('user.profile', $winnerUser->username ?? $winnerUser->id) }}" class="text-decoration-none text-dark hover-primary fw-bold">{{ $winnerUser->username ? '@'.$winnerUser->username : $winnerUser->name }}</a>@else<strong>Winning Designer</strong>@endif • Prize Bounty: <strong class="text-success font-monospace">৳{{ number_format($contest->prize_amount, 0) }}</strong>
                            </span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if($contest->status === 'handover')
                            @if($handoverStatus === 'pending')
                                <span class="badge bg-warning bg-opacity-20 text-dark rounded-pill px-3 py-1 extra-small fw-bold">
                                    <i class="bi bi-clock me-1"></i> Awaiting Source Files
                                </span>
                            @elseif($handoverStatus === 'submitted')
                                <span class="badge bg-primary text-white rounded-pill px-3 py-1 extra-small fw-bold">
                                    <i class="bi bi-file-earmark-check me-1"></i> Files Submitted
                                </span>
                            @elseif($handoverStatus === 'revision_requested')
                                <span class="badge bg-danger text-white rounded-pill px-3 py-1 extra-small fw-bold">
                                    <i class="bi bi-arrow-repeat me-1"></i> Revision Requested
                                </span>
                            @endif
                        @else
                            <span class="badge bg-success text-white rounded-pill px-3 py-1 extra-small fw-bold">
                                <i class="bi bi-check2-all me-1"></i> Escrow Released
                            </span>
                        @endif
                        @if($isAdmin)
                            <span class="badge bg-dark text-white rounded-pill px-2.5 py-1 extra-small fw-bold">Admin Oversight</span>
                        @endif
                        <a href="{{ route('contests.handover.show', $contest->slug ?: $contest->id) }}" class="btn btn-purple-cta btn-sm rounded-pill px-3 py-1.5 extra-small fw-bold shadow-sm">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Open Dedicated Workspace
                        </a>
                    </div>
                </div>

                <!-- Role-Specific Views -->
                @if($isWinner)
                    <!-- 1. WINNING DESIGNER CONSOLE -->
                    @if($contest->status === 'handover')
                        @if(in_array($handoverStatus, ['pending', 'revision_requested']))
                            @if($handoverStatus === 'revision_requested' && $winnerEntry->handover_notes)
                                <div class="alert alert-warning border-0 rounded-3 extra-small mb-3">
                                    <strong><i class="bi bi-exclamation-triangle-fill text-danger me-1"></i> Revision Notes from Client:</strong>
                                    {{ $winnerEntry->handover_notes }}
                                </div>
                            @endif
                            <form action="{{ route('contests.handover.upload', $contest->slug) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="drag-drop-zone p-4 text-center mb-3" id="dragDropZone" onclick="document.getElementById('sourceFileInput').click()">
                                    <i class="bi bi-cloud-arrow-up text-primary fs-1 d-block mb-1"></i>
                                    <span class="fw-bold small text-dark d-block">Drag & drop source files here, or <span class="text-primary text-decoration-underline">browse</span></span>
                                    <span class="extra-small text-muted d-block">Accepts .zip, .rar, .ai, .psd, .eps, .svg (Max: 100MB)</span>
                                    <span id="selectedFileName" class="badge bg-primary bg-opacity-10 text-primary font-monospace extra-small mt-2 d-none"></span>
                                    <input type="file" name="source_file" id="sourceFileInput" class="d-none" accept=".zip,.rar,.ai,.psd,.eps,.svg,.7z" required onchange="handleFileSelect(this)">
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="handover_notes" class="form-control form-control-sm rounded-pill px-3 extra-small" placeholder="Optional notes for buyer (e.g. font links, layer guide)">
                                </div>
                                <button type="submit" class="btn btn-purple-cta btn-sm rounded-pill px-4 py-2 fw-bold">
                                    <i class="bi bi-upload me-1"></i> Submit Source Files
                                </button>
                            </form>
                        @elseif($handoverStatus === 'submitted')
                            <div class="alert alert-info border-0 rounded-3 mb-0 extra-small d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <span><i class="bi bi-clock-history me-1.5"></i> Files submitted. Waiting for buyer review.</span>
                                <a href="{{ route('contests.handover.download', $contest->slug) }}" class="btn btn-sm btn-outline-info rounded-pill px-3 py-1 extra-small fw-bold">
                                    <i class="bi bi-download me-1"></i> Download Package
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-success border-0 rounded-3 mb-0 extra-small d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <span><i class="bi bi-check-circle-fill text-success me-1.5"></i> Contest Completed – Payout released to winner.</span>
                            <div class="d-flex align-items-center gap-2">
                                @if($latestHandoverFile)
                                    <a href="{{ route('contests.handover.download', $contest->slug) }}" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 extra-small fw-bold">
                                        <i class="bi bi-download me-1"></i> Download Files
                                    </a>
                                @endif
                                <a href="{{ route('dashboard') }}#wallet" class="btn btn-sm btn-success rounded-pill px-3 py-1 extra-small fw-bold">
                                    <i class="bi bi-wallet2 me-1"></i> View Wallet
                                </a>
                            </div>
                        </div>
                    @endif

                @elseif($isBuyer)
                    <!-- 2. BUYER CONSOLE -->
                    @if($contest->status === 'handover')
                        @if($handoverStatus === 'pending')
                            <div class="alert alert-warning border-0 rounded-3 mb-0 extra-small d-flex align-items-center gap-2">
                                <i class="bi bi-hourglass-split text-warning fs-5"></i>
                                <span>Waiting for designer to upload editable source files.</span>
                            </div>
                        @elseif($handoverStatus === 'submitted')
                            <div>
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 p-2.5 bg-light rounded-3 border mb-3">
                                    <div>
                                        <span class="badge bg-secondary bg-opacity-10 text-dark extra-small font-monospace me-1">{{ $latestHandoverFile['original_name'] ?? 'source_files.zip' }}</span>
                                        <span class="extra-small text-muted">{{ !empty($latestHandoverFile['size']) ? number_format($latestHandoverFile['size'] / 1048576, 2) . ' MB' : '' }}</span>
                                    </div>
                                    <a href="{{ route('contests.handover.download', $contest->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 extra-small fw-bold">
                                        <i class="bi bi-download me-1"></i> Download Source Files
                                    </a>
                                </div>
                                @if(!empty($winnerEntry->handover_notes))
                                    <div class="extra-small text-muted mb-3"><strong class="text-dark">Notes:</strong> {{ $winnerEntry->handover_notes }}</div>
                                @endif
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-sm btn-success rounded-pill px-3.5 py-1.5 extra-small fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#releaseEscrowModal">
                                        <i class="bi bi-check-circle-fill me-1"></i> Approve Files & Release Escrow
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-3 py-1.5 extra-small fw-bold" data-bs-toggle="modal" data-bs-target="#revisionModal">
                                        <i class="bi bi-arrow-repeat me-1"></i> Request Revision
                                    </button>
                                </div>
                            </div>
                        @elseif($handoverStatus === 'revision_requested')
                            <div class="alert alert-secondary border-0 rounded-3 mb-0 extra-small">
                                <i class="bi bi-info-circle me-1"></i> Revision requested. Waiting for designer to upload updated source files.
                            </div>
                        @endif
                    @else
                        <div class="alert alert-success border-0 rounded-3 mb-0 extra-small d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <span><i class="bi bi-check-circle-fill text-success me-1.5"></i> Contest Completed – Payout released to winner.</span>
                            @if($latestHandoverFile)
                                <a href="{{ route('contests.handover.download', $contest->slug) }}" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 extra-small fw-bold">
                                    <i class="bi bi-download me-1"></i> Download Final Files
                                </a>
                            @endif
                        </div>
                    @endif

                @elseif($isAdmin)
                    <!-- 3. ADMIN CONSOLE -->
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <span class="extra-small text-muted d-block">Status: <strong class="text-dark">{{ ucfirst($handoverStatus) }}</strong></span>
                            @if($latestHandoverFile)
                                <span class="extra-small text-muted">File: <span class="font-monospace text-dark">{{ $latestHandoverFile['original_name'] }}</span> ({{ number_format($latestHandoverFile['size'] / 1048576, 2) }} MB)</span>
                            @endif
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            @if($latestHandoverFile)
                                <a href="{{ route('contests.handover.download', $contest->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 extra-small fw-bold">
                                    <i class="bi bi-download me-1"></i> Inspect & Download
                                </a>
                            @endif
                            @if($contest->status !== 'completed')
                                <button type="button" class="btn btn-sm btn-danger rounded-pill px-3 py-1 extra-small fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#adminForceReleaseModal">
                                    <i class="bi bi-shield-lock-fill me-1"></i> Admin Force Release Escrow
                                </button>
                            @else
                                <span class="badge bg-success rounded-pill px-3 py-1 extra-small fw-bold">Escrow Settled</span>
                            @endif
                        </div>
                    </div>

                @else
                    <!-- 4. PUBLIC VISITOR VIEW -->
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 extra-small text-muted">
                        <span>Winner selected: <strong class="text-dark">{{ $winnerUser ? $winnerUser->name : 'Winning Designer' }}</strong></span>
                        <span class="badge bg-secondary bg-opacity-10 text-dark rounded-pill px-2.5 py-1">Protected Handover In Progress</span>
                    </div>
                @endif
            </div>
        @endif

        <div class="row g-4 g-lg-5">
            <!-- LEFT COLUMN: PUBLIC SUBMISSIONS GALLERY (8 COLUMNS) -->
            <div class="col-12 col-lg-8">
                
                <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                    <div>
                        <h4 class="fw-extrabold text-dark mb-0">
                            <i class="bi bi-grid-fill text-primary me-2"></i>Contest Submissions
                        </h4>
                        <span class="text-muted extra-small">100% Public Gallery with Protected Watermarks</span>
                    </div>
                    <span class="badge bg-light text-dark border rounded-pill px-3 py-1.5 font-monospace fw-bold">
                        {{ $contest->entries->count() }} Designs Submitted
                    </span>
                </div>

                @if($contest->entries->count() > 0)
                    @php
                        $sortedEntries = $contest->entries->sortByDesc('is_winner');
                    @endphp
                    <div class="row g-4 mb-5">
                        @foreach($sortedEntries as $entry)
                            <div class="col-12 col-sm-6" id="entry-{{ $entry->id }}">
                                <div class="card entry-card-figma h-100 {{ $entry->is_winner ? 'winner-glow-card' : '' }}">
                                    
                                    <!-- Watermarked Image Box -->
                                    <div class="entry-preview-box" onclick="openLightbox('{{ asset('storage/' . $entry->watermarked_preview_image) }}', '{{ addslashes($entry->title) }}', '{{ addslashes($entry->user->name ?? 'Designer') }}')">
                                        <img src="{{ asset('storage/' . $entry->watermarked_preview_image) }}" alt="{{ $entry->title }}" loading="lazy">
                                        
                                        <!-- Overlay -->
                                        <div class="zoom-overlay">
                                            <span class="btn btn-light btn-sm rounded-pill px-3 py-1.5 fw-bold shadow-sm extra-small">
                                                <i class="bi bi-zoom-in me-1"></i> Preview Concept
                                            </span>
                                        </div>

                                        <!-- Badges on Image -->
                                        <div class="position-absolute top-0 start-0 m-2.5">
                                            <span class="badge bg-dark bg-opacity-75 text-white rounded-pill px-2.5 py-1 extra-small font-monospace">
                                                #{{ $entry->id }}
                                            </span>
                                        </div>

                                        @if($entry->is_winner)
                                            <div class="position-absolute top-0 end-0 m-2.5">
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-extrabold shadow-sm">
                                                    🏆 Winner
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Entry Details -->
                                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $entry->title }}">
                                                {{ $entry->title }}
                                            </h6>
                                            
                                            <!-- Creator & Time -->
                                            <div class="d-flex align-items-center gap-2 extra-small text-muted mb-2">
                                                @if($entry->user)
                                                    <a href="{{ route('user.profile', $entry->user->username ?? $entry->user->id) }}" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1.5 hover-primary" title="View {{ $entry->user->name }}'s Profile">
                                                        @if($entry->user->avatar)
                                                            <img src="{{ asset('storage/' . $entry->user->avatar) }}" alt="{{ $entry->user->name }}" class="rounded-circle" style="width: 20px; height: 20px; object-fit: cover;">
                                                        @else
                                                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 20px; height: 20px; font-size: 0.65rem;">
                                                                {{ strtoupper(substr($entry->user->name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                        <span>by <strong class="text-dark">{{ $entry->user->name }}</strong></span>
                                                    </a>
                                                @else
                                                    <span>by <strong>Contributor</strong></span>
                                                @endif
                                                <span>•</span>
                                                <span>{{ $entry->created_at->diffForHumans() }}</span>
                                            </div>

                                            @if($entry->description)
                                                <p class="text-secondary extra-small mb-3 line-clamp-2">
                                                    "{{ Str::limit($entry->description, 110) }}"
                                                </p>
                                            @endif
                                        </div>

                                        <!-- Client Star Rating & Feedback Quote -->
                                        @if($entry->client_rating > 0)
                                            <div class="p-2.5 bg-warning bg-opacity-10 rounded-3 mb-3 border border-warning border-opacity-25">
                                                <div class="d-flex align-items-center gap-1 mb-1">
                                                    <div class="text-warning extra-small">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $entry->client_rating)
                                                                <i class="bi bi-star-fill"></i>
                                                            @else
                                                                <i class="bi bi-star"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <span class="extra-small fw-bold text-dark ms-1">Client Rating</span>
                                                </div>
                                                @if($entry->client_feedback)
                                                    <div class="extra-small text-secondary fst-italic">
                                                        "{{ $entry->client_feedback }}"
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        <!-- Footer Actions: Like Button & Client Controls -->
                                        <div class="pt-2 border-top d-flex align-items-center justify-content-between gap-2">
                                            <!-- Like / Upvote Button (AJAX) -->
                                            @php
                                                $isLiked = $entry->isLikedBy(auth()->id(), request()->ip());
                                            @endphp
                                            <button type="button"
                                                    onclick="toggleEntryLike({{ $entry->id }}, this)"
                                                    class="btn btn-sm btn-outline-secondary btn-like d-inline-flex align-items-center gap-1.5 extra-small px-3 py-1 fw-bold {{ $isLiked ? 'liked' : '' }}"
                                                    data-liked="{{ $isLiked ? '1' : '0' }}">
                                                <i class="bi {{ $isLiked ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                                <span class="like-counter">{{ $entry->likes_count }}</span>
                                            </button>

                                            <!-- Organizer Specific Controls -->
                                            @if($isOrganizer)
                                                <div class="d-flex align-items-center gap-1.5">
                                                    <!-- Rate Button -->
                                                    <button type="button"
                                                            onclick="openRateModal({{ $entry->id }}, '{{ addslashes($entry->title) }}', {{ $entry->client_rating ?? 0 }}, '{{ addslashes($entry->client_feedback ?? '') }}')"
                                                            class="btn btn-sm btn-outline-warning rounded-pill px-2.5 py-1 extra-small fw-bold"
                                                            title="Rate & leave feedback">
                                                        <i class="bi bi-star me-1"></i> Rate
                                                    </button>

                                                    <!-- Award as Winner Button -->
                                                    @if($contest->status === 'active' && $isOrganizer && !$entry->is_winner)
                                                        <button type="button"
                                                                onclick="confirmAwardWinner('{{ $entry->id }}', '{{ addslashes($entry->user->username ?? $entry->user->name ?? 'Designer') }}', '{{ $entry->id }}', '{{ number_format($contest->prize_amount, 0) }}')"
                                                                class="btn btn-sm btn-success rounded-pill px-2.5 py-1 extra-small fw-bold shadow-sm">
                                                            <i class="bi bi-trophy-fill me-1"></i> Award Winner
                                                        </button>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="card border-0 shadow-sm rounded-4 p-5 text-center my-4">
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                            <i class="bi bi-images fs-2"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">No Entries Submitted Yet</h5>
                        <p class="text-secondary small mb-0">Be the first verified contributor to submit an entry for this challenge and win ৳{{ number_format($contest->prize_amount, 0) }}.</p>
                    </div>
                @endif

            </div>

            <!-- RIGHT COLUMN: CONTRIBUTOR SUBMISSION & RULES (4 COLUMNS) -->
            <div class="col-12 col-lg-4">
                
                <!-- SUBMISSION FORM CARD -->
                <div class="card glass-sidebar-card p-4 mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <h5 class="fw-extrabold text-dark mb-0">
                            <i class="bi bi-cloud-arrow-up-fill text-primary me-2"></i>Submit Design
                        </h5>
                        <span class="badge bg-success bg-opacity-15 text-success rounded-pill px-2.5 py-1 extra-small fw-bold">
                            Zero Entry Fee
                        </span>
                    </div>

                    @auth
                        @if($contest->status === 'active')
                            @if(!$isContributor)
                                <!-- User is NOT an approved contributor -->
                                <div class="text-center py-4">
                                    <div class="p-3 bg-warning bg-opacity-15 text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="bi bi-person-badge fs-2"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Contributor KYC Required</h6>
                                    <p class="text-secondary extra-small mb-3">
                                        To ensure high quality original submissions and protect organizers, only verified Noksha Contributors can submit entries.
                                    </p>
                                    <a href="{{ route('contributor.apply') }}" class="btn btn-purple-cta rounded-pill w-100 py-2.5 fw-bold extra-small">
                                        <i class="bi bi-shield-check me-1"></i> Apply for Contributor KYC
                                    </a>
                                </div>
                            @elseif($userHasSubmitted)
                                <!-- User has ALREADY submitted (Single Entry Rule) -->
                                <div class="p-3 bg-light rounded-4 text-center border">
                                    <div class="p-2.5 bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px;">
                                        <i class="bi bi-check-lg fs-3"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Your Entry Is Active!</h6>
                                    <p class="text-secondary extra-small mb-3">
                                        Under Noksha's <strong>Single Entry Rule</strong>, you have submitted your designated concept for this tournament:
                                        @if($userEntry)
                                            <div class="p-2 bg-white rounded-3 border extra-small text-dark font-monospace mb-2 text-truncate">
                                                #{{ $userEntry->id }} - {{ $userEntry->title }}
                                            </div>
                                        @endif
                                    </p>
                                    @if($userEntry)
                                        <a href="#entry-{{ $userEntry->id }}" class="btn btn-outline-primary rounded-pill w-100 py-2 extra-small fw-bold">
                                            <i class="bi bi-eye me-1"></i> View My Submission
                                        </a>
                                    @endif
                                </div>
                            @else
                                <!-- Direct link to Dedicated Full Submit Page -->
                                <a href="{{ route('contests.entries.create', $contest->slug ?: $contest->id) }}" class="btn btn-warning rounded-pill w-100 py-2.5 fw-extrabold extra-small text-dark shadow-sm mb-3 d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-pencil-square"></i>
                                    <span>Submit Your Design Entry</span>
                                </a>

                                <div class="text-center my-2 text-muted extra-small">or submit via quick form below:</div>

                                <!-- Eligible Contributor Submission Form -->
                                <form action="{{ route('contests.entries.store', $contest->slug ?: $contest->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <!-- Concept Title -->
                                    <div class="mb-3">
                                        <label class="form-label extra-small fw-bold text-uppercase text-muted">Concept Title *</label>
                                        <input type="text" name="title" class="form-control form-control-sm rounded-3 @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="e.g. Modern Minimalist Vector Identity" required>
                                        @error('title')
                                            <div class="invalid-feedback extra-small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Clean Preview Image Upload -->
                                    <div class="mb-3">
                                        <label class="form-label extra-small fw-bold text-uppercase text-muted">Clean Artwork Preview *</label>
                                        <input type="file" name="clean_preview_image" class="form-control form-control-sm rounded-3 @error('clean_preview_image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp" required>
                                        <div class="form-text extra-small text-muted">
                                            <i class="bi bi-shield-shaded text-primary me-1"></i> Upload your clean JPG/PNG (max 10MB). Noksha will automatically apply protective diagonal watermarks for public display.
                                        </div>
                                        @error('clean_preview_image')
                                            <div class="invalid-feedback extra-small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Source File Cloud Link -->
                                    <div class="mb-3">
                                        <label class="form-label extra-small fw-bold text-uppercase text-muted">Source Asset Link (Optional)</label>
                                        <input type="url" name="source_file_link" class="form-control form-control-sm rounded-3 @error('source_file_link') is-invalid @enderror" value="{{ old('source_file_link') }}" placeholder="https://figma.com/... or Google Drive link">
                                        <div class="form-text extra-small text-muted">
                                            Link to Figma, Adobe XD, AI, or drive folder for the client review.
                                        </div>
                                        @error('source_file_link')
                                            <div class="invalid-feedback extra-small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Concept Notes -->
                                    <div class="mb-3">
                                        <label class="form-label extra-small fw-bold text-uppercase text-muted">Concept Brief / Notes</label>
                                        <textarea name="description" rows="3" class="form-control form-control-sm rounded-3 @error('description') is-invalid @enderror" placeholder="Describe the design inspiration, fonts, or tools used...">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback extra-small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Originality Confirmation Checkbox -->
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="agreement" id="contestAgreement" value="1" required>
                                        <label class="form-check-label extra-small text-muted" for="contestAgreement">
                                            I confirm this is <strong>100% my original artwork</strong> and adheres to Noksha's anti-plagiarism terms.
                                        </label>
                                    </div>

                                    <button type="submit" class="btn btn-purple-cta rounded-pill w-100 py-2.5 fw-bold">
                                        <i class="bi bi-send-fill me-1"></i> Submit Design Entry
                                    </button>
                                </form>
                            @endif
                        @else
                            <div class="alert alert-secondary border-0 rounded-4 small mb-0 text-center py-4">
                                <i class="bi bi-lock-fill text-muted fs-3 d-block mb-2"></i>
                                <div class="fw-bold text-dark mb-1">Submissions Locked</div>
                                <p class="extra-small text-muted mb-0">
                                    @if($contest->status === 'handover')
                                        Winner selected. Protected source file handover in progress.
                                    @elseif($contest->status === 'completed')
                                        Contest completed. Payout released to the winning designer.
                                    @else
                                        This tournament is currently in the {{ ucfirst($contest->status) }} stage.
                                    @endif
                                </p>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-light border rounded-4 small text-muted mb-0 text-center py-4">
                            <i class="bi bi-lock-fill text-primary fs-3 d-block mb-2"></i>
                            <div class="fw-bold text-dark mb-1">Sign in to participate</div>
                            <p class="extra-small text-muted mb-3">Verified contributors can enter contests with zero submission fees.</p>
                            <a href="{{ route('login') }}" class="btn btn-purple-cta rounded-pill px-4 py-2 extra-small fw-bold">
                                Sign In / Register
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- CONTEST RULES CARD -->
                <div class="card p-4 rounded-4 border-0 shadow-sm bg-white">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-shield-check text-primary me-1.5"></i>Contest Integrity Rules</h6>
                    <ul class="extra-small text-secondary ps-3 mb-0 space-y-2">
                        <li class="mb-2"><strong>Single Entry Rule:</strong> Each verified contributor may only submit one distinct concept per tournament.</li>
                        <li class="mb-2"><strong>Auto-Watermark Protection:</strong> All previews are stamped with diagonal safety seals to prevent asset theft.</li>
                        <li class="mb-2"><strong>Zero Plagiarism:</strong> Copied templates or stock art usage will result in immediate disqualification and KYC revocation.</li>
                        <li><strong>100% Escrow Guarantee:</strong> The organizer has pre-funded the ৳{{ number_format($contest->prize_amount, 0) }} bounty.</li>
                    </ul>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- LIGHTBOX MODAL -->
<div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden bg-dark">
            <div class="modal-header border-0 pb-0 text-white">
                <div>
                    <h5 class="modal-title fw-bold text-white mb-0" id="lightboxTitle">Design Preview</h5>
                    <span class="extra-small text-white text-opacity-75" id="lightboxAuthor">by Designer</span>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <img id="lightboxImage" src="" alt="Enlarged Preview" class="img-fluid rounded-3" style="max-height: 80vh; object-fit: contain;">
                <div class="mt-2 text-white text-opacity-50 extra-small font-monospace">
                    Protected by Noksha Diagonal Preview Seal
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CLIENT RATING MODAL (ORGANIZER ACTION) -->
@if($isOrganizer)
<div class="modal fade" id="rateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg p-3">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold text-dark mb-0">Client Rating & Feedback</h5>
                    <span class="extra-small text-muted" id="rateModalEntryTitle">Rate design submission</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rateModalForm" method="POST">
                @csrf
                <div class="modal-body py-3">
                    <input type="hidden" name="rating" id="ratingInput" value="5">

                    <!-- Interactive Star Rating -->
                    <label class="form-label extra-small fw-bold text-uppercase text-muted mb-2">Quality Rating (1 to 5 Stars)</label>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star-fill rating-star-interactive {{ $i <= 5 ? 'active' : '' }}" data-value="{{ $i }}" onclick="setStarRating({{ $i }})"></i>
                        @endfor
                        <span id="starRatingLabel" class="fw-bold extra-small text-warning ms-2 font-monospace">5.0 / 5</span>
                    </div>

                    <!-- Client Feedback Text -->
                    <label class="form-label extra-small fw-bold text-uppercase text-muted">Constructive Feedback to Contributor</label>
                    <textarea name="feedback" id="feedbackInput" rows="3" class="form-control rounded-3" placeholder="Provide feedback on color, typography, or alignment..."></textarea>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3 py-2 extra-small fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-4 py-2 extra-small fw-bold">
                        <i class="bi bi-check-circle-fill me-1"></i> Save Rating
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- AWARD WINNER CONFIRMATION MODAL (ORGANIZER ACTION) -->
<div class="modal fade" id="awardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0 shadow-lg p-3">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <span class="fs-4">🏆</span>
                    <h6 class="modal-title fw-bold text-dark mb-0">Award Winner</h6>
                </div>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="awardModalForm" method="POST">
                @csrf
                <div class="modal-body py-3">
                    <div class="p-2.5 bg-light rounded-3 border mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="extra-small text-muted">Winner:</span>
                            <span class="extra-small fw-bold text-dark font-monospace" id="awardWinnerUsername">@designer</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="extra-small text-muted">Entry:</span>
                            <span class="badge bg-secondary bg-opacity-10 text-dark extra-small font-monospace" id="awardEntryNumber">#0</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="extra-small text-muted">Prize Bounty:</span>
                            <strong class="text-success extra-small font-monospace">৳<span id="awardPrizeAmount">{{ number_format($contest->prize_amount, 0) }}</span></strong>
                        </div>
                    </div>
                    <p class="extra-small text-secondary mb-0">
                        Awarding this entry will lock the contest and prompt the designer to upload source files.
                    </p>
                </div>
                <div class="modal-footer border-0 pt-0 d-flex gap-2">
                    <button type="button" class="btn btn-light rounded-pill flex-fill py-1.5 extra-small fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success rounded-pill flex-fill py-1.5 extra-small fw-bold shadow-sm">
                        <i class="bi bi-trophy-fill me-1"></i> Confirm & Award
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- RELEASE ESCROW PAYMENT MODAL (ORGANIZER ACTION) -->
<div class="modal fade" id="releaseEscrowModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0 shadow-lg p-3">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <span class="fs-4">💰</span>
                    <h6 class="modal-title fw-bold text-dark mb-0">Release Escrow</h6>
                </div>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('contests.handover.release', $contest->slug) }}" method="POST">
                @csrf
                <div class="modal-body py-3">
                    <div class="p-2.5 bg-light rounded-3 border mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="extra-small text-muted">Recipient:</span>
                            <span class="extra-small fw-bold text-dark">{{ $winnerUser ? $winnerUser->name : 'Winning Designer' }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="extra-small text-muted">Payout:</span>
                            <strong class="text-success extra-small font-monospace">৳{{ number_format($contest->prize_amount, 2) }}</strong>
                        </div>
                    </div>
                    <p class="extra-small text-secondary mb-0">
                        Approve deliverables and release ৳{{ number_format($contest->prize_amount, 0) }} escrow to winner's balance immediately.
                    </p>
                </div>
                <div class="modal-footer border-0 pt-0 d-flex gap-2">
                    <button type="button" class="btn btn-light rounded-pill flex-fill py-1.5 extra-small fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success rounded-pill flex-fill py-1.5 extra-small fw-bold shadow-sm">
                        <i class="bi bi-cash-coin me-1"></i> Confirm & Release
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- REQUEST REVISION MODAL (ORGANIZER ACTION) -->
<div class="modal fade" id="revisionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg p-3">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <span class="fs-4">🔄</span>
                    <h6 class="modal-title fw-bold text-dark mb-0">Request Revision</h6>
                </div>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('contests.handover.revision', $contest->slug) }}" method="POST">
                @csrf
                <div class="modal-body py-3">
                    <label class="form-label extra-small fw-bold text-dark mb-1">Revision Notes *</label>
                    <textarea name="revision_notes" class="form-control rounded-3 extra-small mb-2" rows="3" placeholder="Explain required edits, missing font formats, or layer adjustments..." required></textarea>
                    <span class="extra-small text-muted">The designer will be notified to upload an updated archive.</span>
                </div>
                <div class="modal-footer border-0 pt-0 d-flex gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-3 py-1.5 extra-small fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-3 py-1.5 extra-small fw-bold text-dark">
                        <i class="bi bi-send-fill me-1"></i> Send Revision Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ADMIN FORCE RELEASE MODAL (ADMIN ACTION) -->
@if(auth()->check() && auth()->user()->isAdmin())
<div class="modal fade" id="adminForceReleaseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0 shadow-lg p-3">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <span class="fs-4 text-danger"><i class="bi bi-shield-lock-fill"></i></span>
                    <h6 class="modal-title fw-bold text-dark mb-0">Admin Force Release</h6>
                </div>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('contests.handover.release', $contest->slug) }}" method="POST">
                @csrf
                <div class="modal-body py-3">
                    <div class="alert alert-danger border-0 rounded-3 extra-small mb-2">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Admin Override
                    </div>
                    <p class="extra-small text-secondary mb-0">
                        Force release ৳{{ number_format($contest->prize_amount, 2) }} escrow to <strong>{{ $winnerUser ? $winnerUser->name : 'Winner' }}</strong> for dispute handling.
                    </p>
                </div>
                <div class="modal-footer border-0 pt-0 d-flex gap-2">
                    <button type="button" class="btn btn-light rounded-pill flex-fill py-1.5 extra-small fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-pill flex-fill py-1.5 extra-small fw-bold shadow-sm">
                        Force Release
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endif

<!-- SCRIPTS FOR LIGHTBOX, LIKE, & MODALS -->
<script>
    function openLightbox(imageUrl, title, author) {
        document.getElementById('lightboxImage').src = imageUrl;
        document.getElementById('lightboxTitle').textContent = title;
        document.getElementById('lightboxAuthor').textContent = 'by ' + author;
        var modal = new bootstrap.Modal(document.getElementById('lightboxModal'));
        modal.show();
    }

    // AJAX Like Toggle
    function toggleEntryLike(entryId, btnElement) {
        fetch('/contests/entries/' + entryId + '/like', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                var counterSpan = btnElement.querySelector('.like-counter');
                var icon = btnElement.querySelector('i');
                counterSpan.textContent = data.likes_count;

                if (data.liked) {
                    btnElement.classList.add('liked');
                    icon.classList.remove('bi-heart');
                    icon.classList.add('bi-heart-fill');
                } else {
                    btnElement.classList.remove('liked');
                    icon.classList.remove('bi-heart-fill');
                    icon.classList.add('bi-heart');
                }
            }
        })
        .catch(err => console.error('Like toggle error:', err));
    }

    @if($isOrganizer)
    function openRateModal(entryId, title, currentRating, currentFeedback) {
        document.getElementById('rateModalEntryTitle').textContent = title;
        document.getElementById('feedbackInput').value = currentFeedback || '';
        setStarRating(currentRating > 0 ? currentRating : 5);
        document.getElementById('rateModalForm').action = '/contests/{{ $contest->slug }}/entries/' + entryId + '/rate';
        var modal = new bootstrap.Modal(document.getElementById('rateModal'));
        modal.show();
    }

    function setStarRating(rating) {
        document.getElementById('ratingInput').value = rating;
        document.getElementById('starRatingLabel').textContent = rating + '.0 / 5';
        var stars = document.querySelectorAll('.rating-star-interactive');
        stars.forEach(function(star) {
            var val = parseInt(star.getAttribute('data-value'));
            if (val <= rating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }

    function confirmAwardWinner(entryId, username, entryNum, prizeAmount) {
        var usernameEl = document.getElementById('awardWinnerUsername');
        var entryEl = document.getElementById('awardEntryNumber');
        var prizeEl = document.getElementById('awardPrizeAmount');
        if (usernameEl) usernameEl.textContent = '@' + username;
        if (entryEl) entryEl.textContent = '#' + entryNum;
        if (prizeEl) prizeEl.textContent = prizeAmount;
        document.getElementById('awardModalForm').action = '/contests/{{ $contest->slug }}/award/' + entryId;
        var modal = new bootstrap.Modal(document.getElementById('awardModal'));
        modal.show();
    }
    @endif

    function handleFileSelect(input) {
        if (input.files && input.files[0]) {
            var file = input.files[0];
            var nameBadge = document.getElementById('selectedFileName');
            if (nameBadge) {
                nameBadge.textContent = 'Selected: ' + file.name + ' (' + (file.size / (1024*1024)).toFixed(2) + ' MB)';
                nameBadge.classList.remove('d-none');
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var dropZone = document.getElementById('dragDropZone');
        if (dropZone) {
            ['dragenter', 'dragover'].forEach(function(eventName) {
                dropZone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.classList.add('dragover');
                }, false);
            });
            ['dragleave', 'drop'].forEach(function(eventName) {
                dropZone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.classList.remove('dragover');
                }, false);
            });
            dropZone.addEventListener('drop', function(e) {
                var dt = e.dataTransfer;
                var files = dt.files;
                if (files.length) {
                    var fileInput = document.getElementById('sourceFileInput');
                    fileInput.files = files;
                    handleFileSelect(fileInput);
                }
            }, false);
        }
    });
</script>

@endsection
