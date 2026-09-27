<!-- CONTRIBUTOR / SELLER SIDEBAR NAVIGATION -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 seller-sidebar-card">
    <!-- Contributor Profile Mini Widget -->
    <div class="p-4 bg-gradient-purple text-white text-center position-relative">
        <div class="position-relative d-inline-block mb-2">
            @if(auth()->user()->avatar)
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="rounded-circle border border-3 border-white shadow-sm" style="width: 72px; height: 72px; object-fit: cover;" alt="{{ auth()->user()->name }}">
            @else
                <div class="rounded-circle bg-white text-primary fw-extrabold d-flex align-items-center justify-content-center border border-3 border-white shadow-sm mx-auto" style="width: 72px; height: 72px; font-size: 1.75rem;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            @endif
            @if(isset($verification) && $verification->status === 'approved')
                <span class="position-absolute bottom-0 end-0 badge bg-success rounded-circle p-1 border border-2 border-white" title="Verified Author">
                    <i class="bi bi-patch-check-fill text-warning" style="font-size: 0.9rem;"></i>
                </span>
            @endif
        </div>

        <h6 class="fw-extrabold text-white mb-0 text-truncate px-2">{{ auth()->user()->name }}</h6>
        <div class="d-flex align-items-center justify-content-center gap-1 mt-1">
            <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-2.5 py-0.5 extra-small fw-bold">
                <i class="bi bi-palette-fill me-1"></i> Contributor
            </span>
            <span class="badge bg-success bg-opacity-90 text-white rounded-pill px-2 py-0.5 extra-small fw-bold">
                <i class="bi bi-shield-check me-1"></i> {{ auth()->user()->trust_score ?? 99.4 }}%
            </span>
        </div>
    </div>

    <!-- Quick Balance Pill -->
    <div class="px-3 py-2.5 bg-light border-bottom d-flex align-items-center justify-content-between">
        <span class="extra-small text-muted fw-bold">
            <i class="bi bi-wallet2 text-primary me-1"></i> Wallet Balance:
        </span>
        <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-2.5 py-1">
            ৳{{ number_format($totalEarnings ?? 0, 2) }}
        </span>
    </div>

    <!-- Navigation Menu Links -->
    <div class="p-2 seller-nav-menu">
        <ul class="nav flex-column gap-1" id="sellerNavList">
            <!-- 1. Dashboard -->
            <li class="nav-item">
                <a href="{{ route('seller.dashboard') }}#dashboard" 
                   class="nav-link seller-sidebar-link {{ request()->routeIs('seller.dashboard') && !request()->has('tab') ? 'active' : '' }}" 
                   data-tab="tab-dashboard">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- 2. Upload Design -->
            <li class="nav-item">
                <a href="{{ route('resource.create') }}" 
                   class="nav-link seller-sidebar-link {{ request()->routeIs('resource.create') ? 'active' : '' }}"
                   style="{{ request()->routeIs('resource.create') ? 'background: #6C4CF1; color: #fff !important;' : '' }}">
                    <i class="bi bi-cloud-arrow-up-fill text-warning"></i>
                    <span class="fw-bold">Upload Design</span>
                    <span class="badge bg-warning text-dark ms-auto extra-small">New</span>
                </a>
            </li>

            <!-- 3. Manage Designs -->
            <li class="nav-item">
                <a href="{{ route('seller.dashboard') }}#designs" 
                   class="nav-link seller-sidebar-link" 
                   data-tab="tab-designs">
                    <i class="bi bi-collection-fill"></i>
                    <span>Manage Designs</span>
                    @if(isset($totalResources) && $totalResources > 0)
                        <span class="badge bg-secondary bg-opacity-10 text-secondary ms-auto extra-small">{{ $totalResources }}</span>
                    @endif
                </a>
            </li>

            <!-- 4. Download History -->
            <li class="nav-item">
                <a href="{{ route('seller.dashboard') }}#downloads" 
                   class="nav-link seller-sidebar-link" 
                   data-tab="tab-downloads">
                    <i class="bi bi-clock-history"></i>
                    <span>Download History</span>
                    @if(isset($totalDownloads) && $totalDownloads > 0)
                        <span class="badge bg-success bg-opacity-10 text-success ms-auto extra-small">{{ number_format($totalDownloads) }}</span>
                    @endif
                </a>
            </li>

            <!-- 5. Earnings & Payouts -->
            <li class="nav-item">
                <a href="{{ route('seller.dashboard') }}#earnings" 
                   class="nav-link seller-sidebar-link" 
                   data-tab="tab-earnings">
                    <i class="bi bi-wallet2"></i>
                    <span>Earnings & Payouts</span>
                </a>
            </li>

            <!-- 6. Support Ticket -->
            <li class="nav-item">
                <a href="{{ route('seller.dashboard') }}#support" 
                   class="nav-link seller-sidebar-link" 
                   data-tab="tab-support">
                    <i class="bi bi-headset"></i>
                    <span>Support Desk</span>
                </a>
            </li>

            <!-- 7. Account Settings -->
            <li class="nav-item">
                <a href="{{ route('seller.dashboard') }}#account" 
                   class="nav-link seller-sidebar-link" 
                   data-tab="tab-account">
                    <i class="bi bi-person-gear"></i>
                    <span>Account Settings</span>
                </a>
            </li>
        </ul>

        <hr class="my-2 opacity-10">

        <!-- Back to Marketplace & Public Profile -->
        <div class="px-2 pt-1 pb-2 d-flex flex-column gap-1">
            <a href="{{ route('seller.demo') }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill w-100 fw-bold extra-small py-1.5 text-truncate">
                <i class="bi bi-box-arrow-up-right me-1"></i> Public Profile
            </a>
            <a href="{{ route('home') }}" class="btn btn-light btn-sm rounded-pill w-100 fw-bold extra-small py-1.5 text-muted text-truncate">
                <i class="bi bi-arrow-left me-1"></i> Marketplace
            </a>
        </div>
    </div>
</div>

<style>
.bg-gradient-purple {
    background: linear-gradient(135deg, #6C4CF1 0%, #8B5CF6 50%, #9F7AEA 100%);
}
.seller-sidebar-card {
    background: #ffffff;
    border: 1px solid rgba(108, 76, 241, 0.12) !important;
}
.seller-nav-menu .seller-sidebar-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border-radius: 0.85rem;
    font-size: 0.9rem;
    font-weight: 600;
    color: #4B5563;
    transition: all 0.25s ease;
    text-decoration: none;
}
.seller-nav-menu .seller-sidebar-link:hover {
    background: rgba(108, 76, 241, 0.08);
    color: #6C4CF1;
    transform: translateX(3px);
}
.seller-nav-menu .seller-sidebar-link.active {
    background: #6C4CF1;
    color: #ffffff !important;
    box-shadow: 0 4px 14px rgba(108, 76, 241, 0.35);
}
.seller-nav-menu .seller-sidebar-link.active i {
    color: #ffffff !important;
}
.seller-nav-menu .seller-sidebar-link i {
    font-size: 1.15rem;
    color: #6C4CF1;
    transition: color 0.25s ease;
}
html.dark .seller-sidebar-card {
    background: #1E293B !important;
    border-color: #334155 !important;
}
html.dark .seller-nav-menu .seller-sidebar-link {
    color: #94A3B8;
}
html.dark .seller-nav-menu .seller-sidebar-link:hover {
    background: rgba(108, 76, 241, 0.2);
    color: #E2E8F0;
}
html.dark .seller-nav-menu .seller-sidebar-link.active {
    background: #6C4CF1;
    color: #ffffff !important;
}
</style>
