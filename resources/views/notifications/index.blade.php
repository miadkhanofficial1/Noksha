@extends('layouts.app')

@section('title', 'Notification Center - Noksha')

@section('content')

<!-- CUSTOM NOTIFICATION CENTER STYLES -->
<style>
    .notif-item-wrapper {
        border-radius: 0.85rem;
        transition: all 0.2s ease;
    }

    .notif-item-wrapper:hover {
        border-color: #20165c !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(32, 22, 92, 0.08) !important;
    }

    .notif-unread-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background-color: #f47402;
        box-shadow: 0 0 0 3px rgba(244, 116, 2, 0.25);
        display: inline-block;
        flex-shrink: 0;
    }
</style>

<div class="min-h-screen bg-[#f8fafc] py-10 px-4 sm:px-6 lg:px-8 text-[#121826]">
    <div class="max-w-4xl mx-auto">
        
        <!-- BREADCRUMB NAVIGATION -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small fw-semibold text-slate-500 mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-[#20165c] hover:underline"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item active text-slate-700" aria-current="page">Notification Center</li>
            </ol>
        </nav>

        <!-- PAGE HEADER -->
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <div>
                <span class="badge px-3 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold extra-small bg-purple-50 text-[#20165c] border border-purple-200">
                    <i class="bi bi-bell-fill me-1 text-[#f47402]"></i> Real-time Alerts
                </span>
                <h2 class="display-6 fw-extrabold text-[#121826] mt-1 mb-0">Notification Center</h2>
            </div>

            @if(isset($unreadCount) && $unreadCount > 0)
                <form action="{{ route('notifications.readAll') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn rounded-pill btn-sm px-4 py-2 fw-bold text-white bg-[#20165c] hover:bg-[#181045] transition-all shadow-sm">
                        <i class="bi bi-check2-all me-1.5"></i> Mark All as Read
                    </button>
                </form>
            @endif
        </div>

        <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-4 sm:p-6 mb-4">
            @if(isset($notifications) && $notifications->count() > 0)

                <!-- SECTION 1: TODAY -->
                @if(isset($today) && $today->count() > 0)
                    <div class="mb-4">
                        <div class="extra-small text-uppercase tracking-wider fw-extrabold text-slate-500 mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-calendar-event text-[#20165c]"></i> Today
                            <span class="badge bg-purple-50 text-[#20165c] border border-purple-200 rounded-pill px-2 py-0.5 extra-small">{{ $today->count() }}</span>
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
                        <div class="extra-small text-uppercase tracking-wider fw-extrabold text-slate-500 mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-clock-history text-[#f47402]"></i> Yesterday
                            <span class="badge bg-amber-50 text-[#f47402] border border-amber-200 rounded-pill px-2 py-0.5 extra-small">{{ $yesterday->count() }}</span>
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
                        <div class="extra-small text-uppercase tracking-wider fw-extrabold text-slate-500 mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-archive text-slate-400"></i> Earlier
                            <span class="badge bg-slate-100 text-slate-600 border border-slate-200 rounded-pill px-2 py-0.5 extra-small">{{ $earlier->count() }}</span>
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
                    <div class="p-4 bg-purple-50 text-[#20165c] border border-purple-200 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-bell-slash fs-1"></i>
                    </div>
                    <h5 class="fw-extrabold text-[#121826] mb-1">No Notifications Yet</h5>
                    <p class="text-slate-500 small mb-0">System alerts, sales notifications, and order updates will appear here.</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
