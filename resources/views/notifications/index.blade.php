@extends('layouts.app')

@section('title', 'Notification Center - Noksha (নকশা)')

@section('content')

<!-- CUSTOM NOTIFICATION CENTER STYLES -->
<style>
    .notif-bg {
        background: linear-gradient(180deg, #F8F5FF 0%, #FFFFFF 100%);
        min-height: 100vh;
    }

    .notif-card-figma {
        background: #ffffff;
        border-radius: 1.25rem !important;
        border: 1px solid rgba(108, 76, 241, 0.12) !important;
        box-shadow: 0 10px 25px -5px rgba(108, 76, 241, 0.06) !important;
    }

    .notif-item {
        border-radius: 1rem;
        transition: all 0.25s ease;
        background: #ffffff;
        border: 1px solid rgba(108, 76, 241, 0.08);
    }

    .notif-item:hover {
        background: rgba(108, 76, 241, 0.03);
        border-color: rgba(108, 76, 241, 0.25);
        transform: translateX(4px);
    }

    .notif-unread {
        background: rgba(108, 76, 241, 0.05) !important;
        border-left: 4px solid #6C4CF1 !important;
    }

    .btn-purple-cta {
        background: linear-gradient(135deg, #6C4CF1 0%, #5A3DE0 100%);
        color: #ffffff !important;
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
</style>

<div class="notif-bg py-4 py-lg-5">
    <div class="container py-2" style="max-width: 840px;">
        
        <!-- BREADCRUMB NAVIGATION -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small fw-semibold text-muted mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-primary"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item active text-dark" aria-current="page">Notification Center</li>
            </ol>
        </nav>

        <!-- PAGE HEADER -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <span class="badge px-3 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold extra-small" style="background: rgba(108, 76, 241, 0.08); color: #6C4CF1;">
                    <i class="bi bi-bell-fill me-1"></i> Real-time Alerts
                </span>
                <h2 class="display-6 fw-extrabold text-dark mt-1 mb-0">Notification Center</h2>
            </div>

            @if(isset($unreadCount) && $unreadCount > 0)
                <form action="{{ route('notifications.readAll') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary rounded-pill btn-sm px-3.5 fw-bold">
                        <i class="bi bi-check2-all me-1"></i> Mark All as Read
                    </button>
                </form>
            @endif
        </div>

        <div class="card notif-card-figma p-4 mb-4">
            @if(isset($notifications) && $notifications->count() > 0)

                <!-- SECTION 1: TODAY -->
                @if(isset($today) && $today->count() > 0)
                    <div class="mb-4">
                        <div class="extra-small text-uppercase tracking-wider fw-extrabold text-muted mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-calendar-event text-primary"></i> Today
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-0.5 extra-small">{{ $today->count() }}</span>
                        </div>
                        <div class="d-flex flex-column gap-2.5">
                            @foreach($today as $n)
                                @include('notifications._item', ['notification' => $n])
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- SECTION 2: YESTERDAY -->
                @if(isset($yesterday) && $yesterday->count() > 0)
                    <div class="mb-4">
                        <div class="extra-small text-uppercase tracking-wider fw-extrabold text-muted mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-clock-history text-secondary"></i> Yesterday
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2 py-0.5 extra-small">{{ $yesterday->count() }}</span>
                        </div>
                        <div class="d-flex flex-column gap-2.5">
                            @foreach($yesterday as $n)
                                @include('notifications._item', ['notification' => $n])
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- SECTION 3: EARLIER -->
                @if(isset($earlier) && $earlier->count() > 0)
                    <div>
                        <div class="extra-small text-uppercase tracking-wider fw-extrabold text-muted mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-archive text-muted"></i> Earlier
                            <span class="badge bg-light text-dark border rounded-pill px-2 py-0.5 extra-small">{{ $earlier->count() }}</span>
                        </div>
                        <div class="d-flex flex-column gap-2.5">
                            @foreach($earlier as $n)
                                @include('notifications._item', ['notification' => $n])
                            @endforeach
                        </div>
                    </div>
                @endif

            @else
                <!-- EMPTY STATE -->
                <div class="text-center py-5">
                    <div class="p-4 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-bell-slash fs-1"></i>
                    </div>
                    <h5 class="fw-extrabold text-dark mb-1">No Notifications Yet</h5>
                    <p class="text-secondary small mb-0">System alerts, sales notifications, and order updates will appear here.</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
