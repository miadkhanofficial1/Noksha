@extends('layouts.app')

@section('title', 'Admin Contest Panel - Noksha (নকশা)')

@section('content')

<!-- CUSTOM ADMIN CONTEST STYLES -->
<style>
    .admin-bg {
        background: linear-gradient(180deg, #F8F5FF 0%, #FFFFFF 100%);
        min-height: 100vh;
    }

    .admin-card-figma {
        background: #ffffff;
        border-radius: 1.25rem !important;
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        box-shadow: 0 10px 25px -5px rgba(108, 76, 241, 0.06) !important;
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
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 12px 25px -4px rgba(108, 76, 241, 0.45);
        color: #ffffff !important;
    }
</style>

<div class="admin-bg py-4 py-lg-5">
    <div class="container py-2">
        
        <!-- BREADCRUMB NAVIGATION -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small fw-semibold text-muted mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-primary"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.resources.index') }}" class="text-decoration-none text-primary">Admin Panel</a></li>
                <li class="breadcrumb-item active text-dark" aria-current="page">Design Contests</li>
            </ol>
        </nav>

        <!-- PAGE HEADER -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <span class="badge px-3 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold extra-small" style="background: rgba(108, 76, 241, 0.08); color: #6C4CF1;">
                    <i class="bi bi-trophy-fill me-1"></i> Arena Compliance Moderation
                </span>
                <h2 class="display-6 fw-extrabold text-dark mt-1 mb-0">Super Admin Contest Panel</h2>
            </div>
        </div>

        <!-- 4 METRIC CARDS -->
        <div class="row g-3 g-md-4 mb-5">
            <div class="col-6 col-md-3">
                <div class="card admin-card-figma p-4 text-center">
                    <div class="display-6 fw-extrabold text-primary mb-0">{{ $totalContests }}</div>
                    <div class="extra-small text-uppercase tracking-wider fw-bold text-muted mt-1">Total Contests</div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card admin-card-figma p-4 text-center">
                    <div class="display-6 fw-extrabold text-success mb-0">{{ $activeContests }}</div>
                    <div class="extra-small text-uppercase tracking-wider fw-bold text-muted mt-1">Active Contests</div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card admin-card-figma p-4 text-center">
                    <div class="display-6 fw-extrabold text-dark mb-0">{{ $completedContests }}</div>
                    <div class="extra-small text-uppercase tracking-wider fw-bold text-muted mt-1">Completed</div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card admin-card-figma p-4 text-center">
                    <div class="display-6 fw-extrabold text-warning mb-0">{{ $totalSubmissions }}</div>
                    <div class="extra-small text-uppercase tracking-wider fw-bold text-muted mt-1">Total Entries</div>
                </div>
            </div>
        </div>

        <!-- CREATE NEW CONTEST FORM CARD -->
        <div class="card admin-card-figma p-4 p-md-5 mb-5">
            <h4 class="fw-extrabold text-dark mb-3 pb-2 border-bottom"><i class="bi bi-plus-circle-fill text-primary me-2"></i>Create New Design Contest</h4>

            <form action="{{ route('admin.contests.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label extra-small fw-bold text-uppercase text-muted">Contest Title</label>
                        <input type="text" name="title" class="form-control rounded-3" placeholder="e.g. Fintech App Redesign Challenge" required>
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label extra-small fw-bold text-uppercase text-muted">Category</label>
                        <select name="category_id" class="form-select rounded-3">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label extra-small fw-bold text-uppercase text-muted">Prize Amount (৳)</label>
                        <input type="number" step="0.01" name="prize_amount" class="form-control rounded-3" placeholder="50000" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label extra-small fw-bold text-uppercase text-muted">Start Date</label>
                        <input type="datetime-local" name="start_date" class="form-control rounded-3" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label extra-small fw-bold text-uppercase text-muted">End Date</label>
                        <input type="datetime-local" name="end_date" class="form-control rounded-3" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label extra-small fw-bold text-uppercase text-muted">Contest Description & Rules</label>
                        <textarea name="description" rows="3" class="form-control rounded-3" placeholder="Provide guidelines, requirements, and evaluation criteria..." required></textarea>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-purple-cta rounded-pill px-5 py-3 fw-bold">
                            <i class="bi bi-trophy-fill me-2"></i> Launch Contest Now
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- CONTESTS MANAGEMENT TABLE -->
        <div class="card admin-card-figma p-4 mb-4">
            <h4 class="fw-extrabold text-dark mb-3 pb-2 border-bottom"><i class="bi bi-layers-fill text-primary me-2"></i>All Contests Management</h4>

            @if($contests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-uppercase extra-small text-muted fw-bold">
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Prize</th>
                                <th>Entries</th>
                                <th>Status</th>
                                <th>Winner</th>
                                <th class="text-end">Select Winner</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contests as $contest)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $contest->title }}</div>
                                        <div class="extra-small text-muted font-monospace">Ends: {{ $contest->end_date ? $contest->end_date->format('M d, Y') : 'N/A' }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2.5 py-1 extra-small fw-bold">
                                            {{ $contest->category ? $contest->category->name : 'General' }}
                                        </span>
                                    </td>
                                    <td class="fw-extrabold text-success">
                                        ৳{{ number_format($contest->prize_amount, 2) }}
                                    </td>
                                    <td class="fw-bold text-dark font-monospace">
                                        {{ $contest->submissions->count() }} Entries
                                    </td>
                                    <td>
                                        @if($contest->status === 'active')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-1 extra-small fw-bold">Active</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-3 py-1 extra-small fw-bold">Completed</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($contest->winner)
                                            <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                🏆 {{ $contest->winner->name }}
                                            </span>
                                        @else
                                            <span class="extra-small text-muted">None Selected</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($contest->submissions->count() > 0 && $contest->status === 'active')
                                            <form action="{{ route('admin.contests.winner', $contest->id) }}" method="POST" class="d-inline-flex gap-2">
                                                @csrf
                                                <select name="submission_id" class="form-select form-select-sm rounded-pill extra-small" required style="max-width: 180px;">
                                                    <option value="">Select Entry...</option>
                                                    @foreach($contest->submissions as $sub)
                                                        <option value="{{ $sub->id }}">{{ $sub->title }} (by {{ $sub->user ? $sub->user->name : 'Seller' }})</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-warning btn-sm rounded-pill fw-bold text-dark px-3 extra-small">
                                                    Select
                                                </button>
                                            </form>
                                        @else
                                            <span class="extra-small text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4 text-muted small">No contests created yet. Use the form above to launch your first contest.</div>
            @endif
        </div>

    </div>
</div>
@endsection
