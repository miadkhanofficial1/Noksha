@extends('layouts.app')

@section('title', 'Super Admin Dashboard - Noksha (নকশা)')

@section('content')

<!-- CHART.JS INTEGRATION -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- CUSTOM SUPER ADMIN STYLES -->
<style>
    .admin-layout-bg {
        background: #F4F3FA;
        min-height: 100vh;
    }

    .admin-sidebar {
        background: #1E1B4B;
        color: #ffffff;
        border-radius: 1.5rem;
        position: sticky;
        top: 90px;
    }

    .admin-sidebar .nav-link {
        color: rgba(255, 255, 255, 0.75);
        font-weight: 600;
        border-radius: 0.75rem;
        padding: 0.75rem 1.25rem;
        transition: all 0.25s ease;
        margin-bottom: 0.25rem;
    }

    .admin-sidebar .nav-link:hover, .admin-sidebar .nav-link.active {
        color: #ffffff;
        background: rgba(108, 76, 241, 0.35);
        transform: translateX(4px);
    }

    .stat-card-admin {
        background: #ffffff;
        border-radius: 1.25rem !important;
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        box-shadow: 0 10px 25px -5px rgba(108, 76, 241, 0.08) !important;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card-admin:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px -10px rgba(108, 76, 241, 0.18) !important;
    }

    .btn-purple-cta {
        background: linear-gradient(135deg, #6C4CF1 0%, #5A3DE0 100%);
        color: #ffffff !important;
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-purple-cta:hover {
        background: linear-gradient(135deg, #5A3DE0 0%, #4327C6 100%);
        transform: translateY(-2px);
        color: #ffffff !important;
    }
</style>

<div class="admin-layout-bg py-4 py-lg-5">
    <div class="container-fluid px-lg-5">
        
        <div class="row g-4">
            
            <!-- LEFT SIDEBAR (3 COLUMNS) -->
            <div class="col-12 col-lg-3 col-xl-2">
                <div class="admin-sidebar p-3 shadow-lg">
                    <div class="p-3 mb-3 border-bottom border-white border-opacity-10 d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-warning text-dark p-2 fw-extrabold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-extrabold text-white mb-0">Super Admin</h6>
                            <span class="extra-small text-white text-opacity-60 font-monospace">Control Center</span>
                        </div>
                    </div>

                    <nav class="nav flex-column">
                        <a class="nav-link active" href="#overview"><i class="bi bi-speedometer2 me-2"></i> Overview</a>
                        <a class="nav-link" href="#analytics"><i class="bi bi-bar-chart-line-fill me-2"></i> Analytics</a>
                        <a class="nav-link" href="#resources-table"><i class="bi bi-layers-fill me-2"></i> Resources</a>
                        <a class="nav-link" href="#users-table"><i class="bi bi-people-fill me-2"></i> Users</a>
                        <a class="nav-link" href="{{ route('admin.resources.index') }}"><i class="bi bi-check-circle-fill me-2 text-info"></i> Approvals</a>
                        <a class="nav-link" href="{{ route('admin.verifications.index') }}"><i class="bi bi-person-check-fill me-2 text-success"></i> KYC Panel</a>
                        <a class="nav-link" href="{{ route('admin.contests.index') }}"><i class="bi bi-trophy-fill me-2 text-warning"></i> Contests</a>
                        <a class="nav-link" href="{{ route('notifications.index') }}"><i class="bi bi-bell-fill me-2 text-danger"></i> Notifications</a>
                        <a class="nav-link" href="#broadcast" data-bs-toggle="modal" data-bs-target="#broadcastModal"><i class="bi bi-broadcast me-2 text-warning"></i> Broadcast</a>
                    </nav>
                </div>
            </div>

            <!-- RIGHT MAIN CONTENT (9 COLUMNS) -->
            <div class="col-12 col-lg-9 col-xl-10">
                
                <!-- HEADER & QUICK ACTION BUTTONS -->
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
                    <div>
                        <span class="badge px-3 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold extra-small" style="background: rgba(108, 76, 241, 0.1); color: #6C4CF1;">
                            <i class="bi bi-cpu-fill me-1"></i> Central Management Console
                        </span>
                        <h2 class="display-6 fw-extrabold text-dark mt-1 mb-0">Marketplace Executive Dashboard</h2>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <a href="{{ route('admin.contests.index') }}" class="btn btn-warning rounded-pill px-3.5 py-2.5 fw-bold btn-sm text-dark shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> Create Contest
                        </a>
                        <a href="{{ route('admin.resources.index', ['status' => 'pending']) }}" class="btn btn-outline-primary rounded-pill px-3.5 py-2.5 fw-bold btn-sm shadow-sm">
                            <i class="bi bi-clock-history me-1"></i> Pending Resources ({{ $pendingResources }})
                        </a>
                        <button type="button" class="btn btn-purple-cta rounded-pill px-3.5 py-2.5 fw-bold btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#broadcastModal">
                            <i class="bi bi-broadcast me-1"></i> Broadcast Alert
                        </button>
                    </div>
                </div>

                <!-- 6 STATISTIC METRIC CARDS -->
                <div id="overview" class="row g-3 g-md-4 mb-5">
                    <!-- 1. Total Users -->
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="card stat-card-admin p-3.5 text-center">
                            <div class="p-2.5 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex mb-2" style="width:48px; height:48px; justify-content:center; align-items:center;">
                                <i class="bi bi-people-fill fs-4"></i>
                            </div>
                            <div class="display-6 fw-extrabold text-dark mb-0">{{ number_format($totalUsers) }}</div>
                            <div class="extra-small text-uppercase tracking-wider fw-bold text-muted mt-1">Total Users</div>
                        </div>
                    </div>

                    <!-- 2. Total Resources -->
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="card stat-card-admin p-3.5 text-center">
                            <div class="p-2.5 bg-info bg-opacity-10 text-info rounded-circle d-inline-flex mb-2" style="width:48px; height:48px; justify-content:center; align-items:center;">
                                <i class="bi bi-layers-fill fs-4"></i>
                            </div>
                            <div class="display-6 fw-extrabold text-dark mb-0">{{ number_format($totalResources) }}</div>
                            <div class="extra-small text-uppercase tracking-wider fw-bold text-muted mt-1">Resources</div>
                        </div>
                    </div>

                    <!-- 3. Total Orders -->
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="card stat-card-admin p-3.5 text-center">
                            <div class="p-2.5 bg-success bg-opacity-10 text-success rounded-circle d-inline-flex mb-2" style="width:48px; height:48px; justify-content:center; align-items:center;">
                                <i class="bi bi-bag-check-fill fs-4"></i>
                            </div>
                            <div class="display-6 fw-extrabold text-dark mb-0">{{ number_format($totalOrders) }}</div>
                            <div class="extra-small text-uppercase tracking-wider fw-bold text-muted mt-1">Orders</div>
                        </div>
                    </div>

                    <!-- 4. Revenue -->
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="card stat-card-admin p-3.5 text-center">
                            <div class="p-2.5 bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex mb-2" style="width:48px; height:48px; justify-content:center; align-items:center;">
                                <i class="bi bi-cash-stack fs-4 text-warning"></i>
                            </div>
                            <div class="fw-extrabold text-dark fs-4 mb-0">৳{{ number_format($revenue, 2) }}</div>
                            <div class="extra-small text-uppercase tracking-wider fw-bold text-muted mt-1">Revenue</div>
                        </div>
                    </div>

                    <!-- 5. Pending KYC -->
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="card stat-card-admin p-3.5 text-center">
                            <div class="p-2.5 bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex mb-2" style="width:48px; height:48px; justify-content:center; align-items:center;">
                                <i class="bi bi-shield-exclamation fs-4"></i>
                            </div>
                            <div class="display-6 fw-extrabold text-danger mb-0">{{ $pendingVerifications }}</div>
                            <div class="extra-small text-uppercase tracking-wider fw-bold text-muted mt-1">Pending KYC</div>
                        </div>
                    </div>

                    <!-- 6. Pending Assets -->
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="card stat-card-admin p-3.5 text-center">
                            <div class="p-2.5 bg-secondary bg-opacity-10 text-secondary rounded-circle d-inline-flex mb-2" style="width:48px; height:48px; justify-content:center; align-items:center;">
                                <i class="bi bi-clock-history fs-4"></i>
                            </div>
                            <div class="display-6 fw-extrabold text-dark mb-0">{{ $pendingResources }}</div>
                            <div class="extra-small text-uppercase tracking-wider fw-bold text-muted mt-1">Pending Assets</div>
                        </div>
                    </div>
                </div>

                <!-- ANALYTICS SECTION (CHARTS) -->
                <div id="analytics" class="row g-4 mb-5">
                    <!-- Chart 1: Monthly Uploads & Orders Line Chart -->
                    <div class="col-12 col-xl-8">
                        <div class="card stat-card-admin p-4 h-100">
                            <h5 class="fw-extrabold text-dark mb-3"><i class="bi bi-graph-up-arrow text-primary me-2"></i>Monthly Uploads & Orders Trend</h5>
                            <div style="height: 280px;">
                                <canvas id="trendsChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Chart 2: Top Categories Doughnut Chart -->
                    <div class="col-12 col-xl-4">
                        <div class="card stat-card-admin p-4 h-100">
                            <h5 class="fw-extrabold text-dark mb-3"><i class="bi bi-pie-chart-fill text-warning me-2"></i>Top Categories</h5>
                            <div style="height: 280px;">
                                <canvas id="categoriesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RECENT ACTIVITY & RECENT VERIFICATIONS ROW -->
                <div class="row g-4 mb-5">
                    <!-- Recent Activity Feed -->
                    <div class="col-12 col-lg-6">
                        <div class="card stat-card-admin p-4 h-100">
                            <h5 class="fw-extrabold text-dark mb-3 pb-2 border-bottom"><i class="bi bi-activity text-primary me-2"></i>Real-time Marketplace Activity</h5>
                            
                            <div class="d-flex flex-column gap-3">
                                @if(isset($recentActivity) && $recentActivity->count() > 0)
                                    @foreach($recentActivity as $act)
                                        <div class="d-flex align-items-start gap-3 p-2.5 rounded-3 bg-light border">
                                            <div class="p-2 rounded-circle bg-white shadow-sm extra-small">
                                                <i class="bi {{ $act['icon'] }} fs-5"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold text-dark small mb-0">{{ $act['title'] }}</div>
                                                <div class="extra-small text-secondary">{{ $act['desc'] }}</div>
                                            </div>
                                            <span class="extra-small text-muted font-monospace">{{ $act['time'] }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center text-muted py-4 small">No activity logged yet.</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Quick Verifications Inspector -->
                    <div class="col-12 col-lg-6">
                        <div class="card stat-card-admin p-4 h-100">
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                <h5 class="fw-extrabold text-dark mb-0"><i class="bi bi-shield-check text-success me-2"></i>KYC Approvals Queue</h5>
                                <a href="{{ route('admin.verifications.index') }}" class="extra-small fw-bold text-primary text-decoration-none">View All</a>
                            </div>

                            @if($pendingVerifications > 0)
                                <div class="alert alert-warning rounded-4 small border-0 mb-3">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $pendingVerifications }} seller identity verification applications waiting for compliance review.
                                </div>
                                <a href="{{ route('admin.verifications.index') }}" class="btn btn-outline-primary rounded-pill w-100 py-2.5 fw-bold btn-sm">
                                    Open Verification Inspector Panel <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            @else
                                <div class="text-center py-5">
                                    <i class="bi bi-patch-check-fill text-success fs-1 mb-2 d-block"></i>
                                    <h6 class="fw-bold text-dark mb-1">Queue Clear!</h6>
                                    <p class="extra-small text-muted mb-0">All seller KYC requests have been processed.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- POPULAR SEARCH & AUTO TAGS ANALYTICS CARD -->
                <div class="card stat-card-admin p-4 mb-5">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <h5 class="fw-extrabold text-dark mb-0"><i class="bi bi-tags-fill text-primary me-2"></i>Popular Search & AI Indexed Tags</h5>
                        <span class="extra-small text-muted font-monospace">Top Keywords</span>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        @if(isset($popularSearchTags) && count($popularSearchTags) > 0)
                            @foreach($popularSearchTags as $tag => $count)
                                <a href="{{ route('search.index', ['tag' => $tag]) }}" class="badge bg-primary bg-opacity-10 text-primary text-decoration-none rounded-pill px-3 py-2 extra-small fw-bold border border-primary border-opacity-10">
                                    #{{ $tag }} <span class="badge bg-primary rounded-circle ms-1 extra-small">{{ $count }}</span>
                                </a>
                            @endforeach
                        @else
                            <span class="extra-small text-muted">No tag telemetry available yet.</span>
                        @endif
                    </div>
                </div>

                <!-- RESOURCE MANAGEMENT TABLE -->
                <div id="resources-table" class="card stat-card-admin p-4 mb-5">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <h5 class="fw-extrabold text-dark mb-0"><i class="bi bi-layers-fill text-primary me-2"></i>Resource Management Table</h5>
                        <a href="{{ route('admin.resources.index') }}" class="extra-small fw-bold text-primary text-decoration-none">Full Table</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-uppercase extra-small text-muted fw-bold">
                                <tr>
                                    <th>Preview</th>
                                    <th>Title</th>
                                    <th>Seller</th>
                                    <th>Status</th>
                                    <th>Views</th>
                                    <th>Downloads</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resources as $res)
                                    <tr>
                                        <td>
                                            <div class="rounded-3 overflow-hidden" style="width: 50px; height: 36px; background: #1E1B4B;">
                                                @if($res->preview_image)
                                                    <img src="{{ asset('storage/' . $res->preview_image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $res->title }}">
                                                @else
                                                    <div class="w-100 h-100 bg-primary p-1 text-white text-center"><i class="bi bi-box"></i></div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark small text-truncate" style="max-width: 220px;">{{ $res->title }}</div>
                                            <div class="extra-small text-muted">{{ $res->category ? $res->category->name : 'General' }}</div>
                                        </td>
                                        <td class="small">{{ $res->owner ? $res->owner->name : 'Noksha Creator' }}</td>
                                        <td>
                                            @if($res->status === 'approved')
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 extra-small fw-bold">Approved</span>
                                            @elseif($res->status === 'pending')
                                                <span class="badge bg-warning bg-opacity-10 text-dark rounded-pill px-2.5 py-1 extra-small fw-bold">Pending</span>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2.5 py-1 extra-small fw-bold">Rejected</span>
                                            @endif
                                        </td>
                                        <td class="extra-small font-monospace text-muted">{{ number_format($res->views) }}</td>
                                        <td class="extra-small font-monospace text-muted">{{ number_format($res->downloads) }}</td>
                                        <td class="text-end">
                                            <div class="d-inline-flex gap-1">
                                                @if($res->status === 'pending')
                                                    <form action="{{ route('admin.resources.approve', $res->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm rounded-circle p-1" title="Approve">
                                                            <i class="bi bi-check-lg"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.resources.reject', $res->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle p-1" title="Reject">
                                                            <i class="bi bi-x-lg"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('resource.show', $res->slug ?? $res->id) }}" class="btn btn-light border btn-sm rounded-circle p-1" title="View">
                                                    <i class="bi bi-eye-fill text-primary"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $resources->appends(['users_page' => $users->currentPage()])->links() }}
                    </div>
                </div>

                <!-- USER MANAGEMENT TABLE -->
                <div id="users-table" class="card stat-card-admin p-4 mb-4">
                    <h5 class="fw-extrabold text-dark mb-3 pb-2 border-bottom"><i class="bi bi-people-fill text-primary me-2"></i>User Management Table</h5>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-uppercase extra-small text-muted fw-bold">
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>KYC Status</th>
                                    <th>User Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $usr)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2.5">
                                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold extra-small d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                                    {{ strtoupper(substr($usr->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark small mb-0">{{ $usr->name }}</div>
                                                    <div class="extra-small text-muted">@ {{ $usr->username }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="small text-muted font-monospace">{{ $usr->email }}</td>
                                        <td>
                                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                {{ ucfirst($usr->role ?? 'user') }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($usr->is_verified)
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 extra-small fw-bold">Verified</span>
                                            @else
                                                <span class="badge bg-light text-secondary rounded-pill px-2.5 py-1 extra-small">Unverified</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(($usr->status ?? 'active') === 'suspended')
                                                <span class="badge bg-danger text-white rounded-pill px-2.5 py-1 extra-small fw-bold">Suspended</span>
                                            @else
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 extra-small fw-bold">Active</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ route('admin.users.toggleStatus', $usr->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @if(($usr->status ?? 'active') === 'suspended')
                                                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 py-1 extra-small fw-bold">
                                                        Activate
                                                    </button>
                                                @else
                                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 py-1 extra-small fw-bold">
                                                        Suspend
                                                    </button>
                                                @endif
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $users->appends(['resources_page' => $resources->currentPage()])->links() }}
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

<!-- BROADCAST NOTIFICATION MODAL -->
<div class="modal fade text-start" id="broadcastModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-broadcast text-primary me-2"></i> Broadcast System Notification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.broadcast') }}" method="POST">
                @csrf
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold text-uppercase text-muted">Notification Title</label>
                        <input type="text" name="title" class="form-control rounded-3" placeholder="e.g. Scheduled Marketplace Maintenance" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold text-uppercase text-muted">Message Content</label>
                        <textarea name="message" rows="3" class="form-control rounded-3" placeholder="Enter message to broadcast to all registered users..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-purple-cta rounded-pill px-4 fw-bold">
                        <i class="bi bi-send-fill me-1"></i> Broadcast Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CHART INITIALIZATION SCRIPT -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // 1. Line Chart: Trends
        const trendsCtx = document.getElementById('trendsChart').getContext('2d');
        new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: @json($chartData['monthlyLabels']),
                datasets: [
                    {
                        label: 'Resource Uploads',
                        data: @json($chartData['monthlyUploads']),
                        borderColor: '#6C4CF1',
                        backgroundColor: 'rgba(108, 76, 241, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Completed Orders',
                        data: @json($chartData['monthlyOrders']),
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } }
            }
        });

        // 2. Doughnut Chart: Categories
        const catCtx = document.getElementById('categoriesChart').getContext('2d');
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: @json($chartData['topCategories']),
                datasets: [{
                    data: @json($chartData['categoryCounts']),
                    backgroundColor: ['#6C4CF1', '#3B82F6', '#10B981', '#F59E0B', '#EC4899']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    });
</script>
@endsection
