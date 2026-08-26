@extends('layouts.app')

@section('title', 'Admin Seller Verification Review Panel - Noksha (নকশা)')

@section('content')

<!-- CUSTOM SUPER ADMIN VERIFICATION REVIEW STYLES -->
<style>
    .admin-verification-hero {
        background: linear-gradient(135deg, #4C1D95 0%, #6C4CF1 50%, #8B5CF6 100%);
        border-radius: 1.5rem !important;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 45px -10px rgba(76, 29, 149, 0.3) !important;
    }

    .admin-verification-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.2) 1px, transparent 1px);
        background-size: 24px 24px;
        opacity: 0.35;
        pointer-events: none;
    }

    /* Stat Cards */
    .admin-stat-card {
        background: #ffffff;
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        border-radius: 1.25rem !important;
        box-shadow: 0 10px 25px -5px rgba(108, 76, 241, 0.08) !important;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
    }

    .admin-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 35px -8px rgba(108, 76, 241, 0.18) !important;
        border-color: rgba(108, 76, 241, 0.3) !important;
    }

    .admin-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Table Container */
    .admin-table-card {
        background: #ffffff;
        border-radius: 1.5rem !important;
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        box-shadow: 0 15px 35px -10px rgba(108, 76, 241, 0.1) !important;
        overflow: hidden;
    }

    /* Filter Pills */
    .admin-filter-pill {
        border-radius: 999px !important;
        padding: 0.45rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.25s ease;
        text-decoration: none !important;
    }

    .btn-purple-cta {
        background: linear-gradient(135deg, #6C4CF1 0%, #5A3DE0 100%);
        color: #ffffff !important;
        border: none;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .btn-purple-cta:hover {
        background: linear-gradient(135deg, #5A3DE0 0%, #4327C6 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -4px rgba(108, 76, 241, 0.4);
    }
</style>

<!-- ADMIN VERIFICATION HERO BANNER -->
<section class="py-4 py-lg-5" style="background-color: #F8F7FF;">
    <div class="container">
        
        <!-- Flash Success Notification -->
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-lg rounded-4 p-4 mb-4 d-flex align-items-center gap-3 text-dark bg-white">
                <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle">
                    <i class="bi bi-check-circle-fill fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Action Complete!</h5>
                    <p class="mb-0 small text-secondary">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- HERO BANNER -->
        <div class="card admin-verification-hero p-4 p-md-5 text-white mb-4 position-relative">
            <div class="row align-items-center g-4 position-relative" style="z-index: 1;">
                <div class="col-lg-8">
                    <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1.5 small fw-bold mb-3 border border-white border-opacity-25">
                        <i class="bi bi-shield-check-fill me-1 text-warning"></i> Super Admin Compliance Portal
                    </span>
                    <h2 class="display-6 fw-extrabold text-white mb-2">
                        Seller KYC Verification Panel <span class="text-warning">(ভেরিফিকেশন রিভিউ)</span>
                    </h2>
                    <p class="text-white text-opacity-90 mb-0 small" style="max-width: 620px;">
                        Inspect submitted government NIDs, passports, driving licenses, face selfies, and verification videos to approve seller identity applications.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('admin.resources.index') }}" class="btn btn-light rounded-pill px-4 py-2.5 fw-bold text-primary shadow-sm">
                        <i class="bi bi-layers-fill me-1"></i> Asset Moderation Queue
                    </a>
                </div>
            </div>
        </div>

        <!-- TOP FOUR STATISTICS CARDS -->
        <div class="row g-3 g-md-4 mb-5">
            
            <!-- Card 1: Pending Reviews -->
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('admin.verifications.index', ['status' => 'pending']) }}" class="text-decoration-none">
                    <div class="card admin-stat-card p-3.5 border-start border-warning border-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted extra-small fw-bold text-uppercase tracking-wider mb-1">Pending Reviews</div>
                                <div class="display-6 fw-extrabold text-warning mb-0">{{ $pendingCount }}</div>
                            </div>
                            <div class="admin-stat-icon bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-clock-history"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 2: Approved Sellers -->
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('admin.verifications.index', ['status' => 'approved']) }}" class="text-decoration-none">
                    <div class="card admin-stat-card p-3.5 border-start border-success border-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted extra-small fw-bold text-uppercase tracking-wider mb-1">Approved Sellers</div>
                                <div class="display-6 fw-extrabold text-success mb-0">{{ $approvedCount }}</div>
                            </div>
                            <div class="admin-stat-icon bg-success bg-opacity-10 text-success">
                                <i class="bi bi-patch-check-fill"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 3: Rejected Sellers -->
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('admin.verifications.index', ['status' => 'rejected']) }}" class="text-decoration-none">
                    <div class="card admin-stat-card p-3.5 border-start border-danger border-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted extra-small fw-bold text-uppercase tracking-wider mb-1">Rejected Sellers</div>
                                <div class="display-6 fw-extrabold text-danger mb-0">{{ $rejectedCount }}</div>
                            </div>
                            <div class="admin-stat-icon bg-danger bg-opacity-10 text-danger">
                                <i class="bi bi-x-circle-fill"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 4: Total Verifications -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card admin-stat-card p-3.5 border-start border-primary border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted extra-small fw-bold text-uppercase tracking-wider mb-1">Total Applications</div>
                            <div class="display-6 fw-extrabold text-dark mb-0">{{ $totalCount }}</div>
                        </div>
                        <div class="admin-stat-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-shield-person"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- SEARCH AND FILTER BAR SECTION -->
        <div class="card p-3.5 border-0 shadow-sm rounded-4 bg-white mb-4">
            <form action="{{ route('admin.verifications.index') }}" method="GET" class="row g-3 align-items-center">
                <!-- Status Filter Pills -->
                <div class="col-12 col-lg-7 d-flex flex-wrap align-items-center gap-2">
                    <span class="small fw-bold text-muted me-2"><i class="bi bi-funnel-fill text-primary me-1"></i> Filter Status:</span>
                    <a href="{{ route('admin.verifications.index') }}" class="btn admin-filter-pill {{ !request('status') ? 'btn-primary text-white' : 'btn-light border text-dark' }}">
                        All ({{ $totalCount }})
                    </a>
                    <a href="{{ route('admin.verifications.index', ['status' => 'pending']) }}" class="btn admin-filter-pill {{ request('status') === 'pending' ? 'btn-warning text-dark' : 'btn-light border text-dark' }}">
                        Pending ({{ $pendingCount }})
                    </a>
                    <a href="{{ route('admin.verifications.index', ['status' => 'approved']) }}" class="btn admin-filter-pill {{ request('status') === 'approved' ? 'btn-success text-white' : 'btn-light border text-dark' }}">
                        Approved ({{ $approvedCount }})
                    </a>
                    <a href="{{ route('admin.verifications.index', ['status' => 'rejected']) }}" class="btn admin-filter-pill {{ request('status') === 'rejected' ? 'btn-danger text-white' : 'btn-light border text-dark' }}">
                        Rejected ({{ $rejectedCount }})
                    </a>
                </div>

                <!-- Search Input Field -->
                <div class="col-12 col-lg-5">
                    <div class="input-group">
                        @if(request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        <span class="input-group-text bg-light border-end-0 text-muted ps-3">
                            <i class="bi bi-search text-primary"></i>
                        </span>
                        <input type="text" name="search" class="form-control bg-light border-start-0 ps-1 rounded-end fs-6" placeholder="Search by name, country, or email..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-purple-cta rounded-end px-4 fw-bold">Search</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- VERIFICATION TABLE CONTAINER -->
        <div class="admin-table-card mb-5">
            <div class="p-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="fw-extrabold text-dark mb-0">Seller Identity Verification Applications</h5>
                <span class="badge bg-light text-dark border px-3 py-1.5 rounded-pill small fw-semibold">
                    Showing {{ $verifications->firstItem() ?? 0 }} - {{ $verifications->lastItem() ?? 0 }} of {{ $verifications->total() }} Records
                </span>
            </div>

            @if($verifications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted extra-small text-uppercase tracking-wider">
                            <tr>
                                <th class="ps-4">Seller Account</th>
                                <th>Document Type</th>
                                <th>Submitted Date</th>
                                <th>Status</th>
                                <th>Country</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($verifications as $item)
                                <tr>
                                    <!-- Seller Account -->
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-placeholder bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 42px; height: 42px; font-size: 1.1rem;">
                                                {{ strtoupper(substr($item->full_name ?? ($item->user ? $item->user->name : 'S'), 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark fs-6">{{ $item->full_name }}</div>
                                                <div class="extra-small text-muted">{{ $item->user ? $item->user->email : 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Document Type -->
                                    <td>
                                        <span class="badge bg-light text-primary border rounded-pill px-3 py-1.5 fw-semibold text-uppercase">
                                            <i class="bi bi-file-earmark-person me-1"></i> {{ $item->document_type ?? 'NID' }}
                                        </span>
                                    </td>

                                    <!-- Submitted Date -->
                                    <td class="small text-muted font-monospace">
                                        {{ $item->submitted_at ? $item->submitted_at->format('M d, Y h:i A') : $item->created_at->format('M d, Y') }}
                                    </td>

                                    <!-- Status -->
                                    <td>
                                        @if($item->status === 'approved')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-1 fw-bold">
                                                <i class="bi bi-check-circle-fill me-1"></i> Approved
                                            </span>
                                        @elseif($item->status === 'pending')
                                            <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 rounded-pill px-3 py-1 fw-bold">
                                                <i class="bi bi-clock-history me-1"></i> Pending
                                            </span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-1 fw-bold">
                                                <i class="bi bi-x-circle-fill me-1"></i> Rejected
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Country -->
                                    <td class="fw-semibold text-dark small">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $item->country }}
                                    </td>

                                    <!-- Actions -->
                                    <td class="text-end pe-4">
                                        <div class="d-inline-flex gap-1.5">
                                            <!-- Review Details Trigger Button -->
                                            <a href="{{ route('admin.verifications.show', $item->id) }}" class="btn btn-light border btn-sm rounded-pill px-3 fw-bold text-primary">
                                                <i class="bi bi-eye-fill me-1"></i> Review Details
                                            </a>

                                            <!-- Direct Approve Button -->
                                            @if($item->status !== 'approved')
                                                <form action="{{ route('admin.verifications.approve', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-2.5 fw-bold" title="Approve Verification">
                                                        <i class="bi bi-check-lg"></i> Approve
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Direct Reject Trigger Modal -->
                                            @if($item->status !== 'rejected')
                                                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-2.5 fw-bold" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $item->id }}" title="Reject Verification">
                                                    <i class="bi bi-x-lg"></i> Reject
                                                </button>
                                            @endif
                                        </div>

                                        <!-- REJECT REASON MODAL -->
                                        <div class="modal fade text-start" id="rejectModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content rounded-4 border-0 shadow-lg">
                                                    <form action="{{ route('admin.verifications.reject', $item->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header border-0 pb-0">
                                                            <h5 class="modal-title fw-bold text-dark"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i> Reject Seller Verification</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body py-3">
                                                            <p class="text-secondary small mb-3">
                                                                Provide a clear rejection reason for seller <strong>"{{ $item->full_name }}"</strong>:
                                                            </p>
                                                            <textarea name="admin_note" class="form-control rounded-3 border fs-6" rows="3" placeholder="e.g. Document image is blurry or NID numbers do not match legal full name." required>{{ $item->admin_note }}</textarea>
                                                        </div>
                                                        <div class="modal-footer border-0 pt-0">
                                                            <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Reject Verification</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION CONTROLS -->
                <div class="p-4 border-top d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="small text-muted">
                        Showing {{ $verifications->firstItem() ?? 0 }} to {{ $verifications->lastItem() ?? 0 }} of {{ $verifications->total() }} records
                    </div>
                    <div>
                        {{ $verifications->links() }}
                    </div>
                </div>
            @else
                <!-- EMPTY STATE WHEN ZERO RECORDS MATCH -->
                <div class="p-5 text-center">
                    <div class="p-4 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-shield-slash fs-1"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">No Verification Applications Found</h4>
                    <p class="text-secondary small mb-3" style="max-width: 450px; margin: 0 auto;">
                        There are currently no seller verification submissions matching the selected status or search filter.
                    </p>
                    <a href="{{ route('admin.verifications.index') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-bold btn-sm">
                        Reset Filters
                    </a>
                </div>
            @endif
        </div>

    </div>
</section>

@endsection
