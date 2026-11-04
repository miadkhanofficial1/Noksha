@extends('layouts.app')

@section('title', 'Notification Center - Noksha')

@section('content')

<!-- CUSTOM NOTIFICATION CENTER STYLES -->
<style>
    .notif-item-wrapper {
        border-radius: 1rem;
        transition: all 0.25s ease;
    }

    .notif-item-wrapper:hover {
        background: #131f3d !important;
        border-color: rgba(99, 102, 241, 0.4) !important;
        transform: translateX(4px);
    }

    .notif-unread-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #22c55e;
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7), 0 0 8px #22c55e;
        animation: greenDotPulse 2s infinite cubic-bezier(0.4, 0, 0.6, 1);
        display: inline-block;
        flex-shrink: 0;
    }
    @keyframes greenDotPulse {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7), 0 0 8px #22c55e;
            transform: scale(1);
        }
        50% {
            box-shadow: 0 0 0 5px rgba(34, 197, 94, 0), 0 0 12px rgba(34, 197, 94, 0.9);
            transform: scale(1.15);
        }
    }
</style>

<div class="min-h-screen bg-[#070b14] py-12 px-4 sm:px-6 lg:px-8 text-white">
    <div class="max-w-4xl mx-auto">
        
        <!-- BREADCRUMB NAVIGATION -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small fw-semibold text-gray-400 mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-indigo-400 hover:text-indigo-300"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item active text-gray-300" aria-current="page">Notification Center</li>
            </ol>
        </nav>

        <!-- PAGE HEADER -->
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <div>
                <span class="badge px-3 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold extra-small bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                    <i class="bi bi-bell-fill me-1"></i> Real-time Alerts
                </span>
                <h2 class="display-6 fw-extrabold text-white mt-1 mb-0">Notification Center</h2>
            </div>

            @if(isset($unreadCount) && $unreadCount > 0)
                <form action="{{ route('notifications.readAll') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn rounded-pill btn-sm px-3.5 py-2 fw-bold text-indigo-300 border border-indigo-500/40 hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                        <i class="bi bi-check2-all me-1"></i> Mark All as Read
                    </button>
                </form>
            @endif
        </div>

        <div class="bg-[#0b1329] border border-gray-800/80 shadow-2xl rounded-2xl p-4 sm:p-6 mb-4">
            @if(isset($notifications) && $notifications->count() > 0)

                <!-- SECTION 1: TODAY -->
                @if(isset($today) && $today->count() > 0)
                    <div class="mb-4">
                        <div class="extra-small text-uppercase tracking-wider fw-extrabold text-gray-400 mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-calendar-event text-indigo-400"></i> Today
                            <span class="badge bg-indigo-500/15 text-indigo-300 border border-indigo-500/30 rounded-pill px-2 py-0.5 extra-small">{{ $today->count() }}</span>
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
                        <div class="extra-small text-uppercase tracking-wider fw-extrabold text-gray-400 mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-clock-history text-purple-400"></i> Yesterday
                            <span class="badge bg-purple-500/15 text-purple-300 border border-purple-500/30 rounded-pill px-2 py-0.5 extra-small">{{ $yesterday->count() }}</span>
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
                        <div class="extra-small text-uppercase tracking-wider fw-extrabold text-gray-400 mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-archive text-gray-400"></i> Earlier
                            <span class="badge bg-gray-800 text-gray-300 border border-gray-700/60 rounded-pill px-2 py-0.5 extra-small">{{ $earlier->count() }}</span>
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
                    <div class="p-4 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-bell-slash fs-1"></i>
                    </div>
                    <h5 class="fw-extrabold text-white mb-1">No Notifications Yet</h5>
                    <p class="text-gray-400 small mb-0">System alerts, sales notifications, and order updates will appear here.</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
