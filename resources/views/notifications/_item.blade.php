@php
    $type = $notification->type ?? 'system';
    $iconClass = match($type) {
        'seller' => 'bi-shop text-primary bg-primary',
        'buyer' => 'bi-bag-check-fill text-success bg-success',
        'admin' => 'bi-shield-lock-fill text-warning bg-warning',
        default => 'bi-bell-fill text-info bg-info',
    };
@endphp

<form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="m-0">
    @csrf
    <button type="submit" class="w-100 text-start border-0 p-0 bg-transparent">
        <div class="notif-item p-3 d-flex align-items-start gap-3 {{ !$notification->is_read ? 'notif-unread' : '' }}">
            <!-- Icon -->
            <div class="p-2.5 rounded-circle bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0 {{ explode(' ', $iconClass)[2] ?? 'bg-primary' }}" style="width: 42px; height: 42px;">
                <i class="bi {{ explode(' ', $iconClass)[0] }} fs-5 {{ explode(' ', $iconClass)[1] }}"></i>
            </div>

            <!-- Content -->
            <div class="flex-grow-1">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <h6 class="fw-bold text-dark mb-0 small">{{ $notification->title }}</h6>
                    <span class="extra-small text-muted font-monospace">{{ $notification->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-secondary extra-small mb-0 lh-sm">{{ $notification->message }}</p>
            </div>

            <!-- Read Status Badge -->
            <div class="flex-shrink-0">
                @if(!$notification->is_read)
                    <span class="badge bg-primary rounded-circle p-1" style="width: 8px; height: 8px;" title="Unread"></span>
                @else
                    <i class="bi bi-check2 text-muted extra-small"></i>
                @endif
            </div>
        </div>
    </button>
</form>
