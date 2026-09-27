@php
    $type = $notification->type ?? 'system';
    $isBroadcast = in_array($type, ['broadcast', 'admin']) ||
                   str_contains(strtolower($notification->title), 'broadcast') ||
                   str_contains(strtolower($notification->title), 'announcement');
    $isContest = in_array($type, ['contest', 'success']) ||
                 str_contains(strtolower($notification->title), 'contest');

    if ($isBroadcast) {
        $wrapperBorder = 'border-start border-4 border-indigo';
        $wrapperStyle = 'border-left: 4px solid #6366f1 !important; background: linear-gradient(135deg, rgba(99, 102, 241, 0.07) 0%, rgba(139, 92, 246, 0.03) 100%); box-shadow: 0 4px 15px rgba(99, 102, 241, 0.1);';
        $icon = 'bi-megaphone-fill';
        $iconClass = 'text-indigo-500';
        $iconBg = 'background: rgba(99, 102, 241, 0.15); color: #6366f1;';
        $badge = '<span class="badge text-white rounded-pill px-2.5 py-1 extra-small" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); font-size: 0.65rem;"><i class="bi bi-megaphone-fill me-1"></i>Broadcast Announcement</span>';
    } elseif ($isContest) {
        $wrapperBorder = 'border-start border-4 border-warning';
        $wrapperStyle = 'border-left: 4px solid #f59e0b !important; background: linear-gradient(135deg, rgba(245, 158, 11, 0.07) 0%, rgba(249, 115, 22, 0.03) 100%); box-shadow: 0 4px 15px rgba(245, 158, 11, 0.1);';
        $icon = 'bi-trophy-fill';
        $iconClass = 'text-warning';
        $iconBg = 'background: rgba(245, 158, 11, 0.15); color: #f59e0b;';
        $badge = '<span class="badge text-white rounded-pill px-2.5 py-1 extra-small" style="background: linear-gradient(135deg, #f59e0b, #ea580c); font-size: 0.65rem;"><i class="bi bi-trophy-fill me-1"></i>Contest Alert</span>';
    } else {
        $wrapperBorder = !$notification->is_read ? 'border-start border-4 border-primary' : '';
        $wrapperStyle = !$notification->is_read ? 'background: rgba(108, 76, 241, 0.03);' : 'background: #ffffff;';
        $icon = match($type) {
            'seller' => 'bi-shop-fill',
            'buyer'  => 'bi-bag-check-fill',
            'warning'=> 'bi-exclamation-triangle-fill',
            default  => 'bi-bell-fill',
        };
        $iconClass = match($type) {
            'seller' => 'text-primary',
            'buyer'  => 'text-success',
            'warning'=> 'text-warning',
            default  => 'text-info',
        };
        $iconBg = 'background: rgba(108, 76, 241, 0.08);';
        $badge = '';
    }
@endphp

{{-- NOTIFICATION ITEM WRAPPER --}}
<div class="notif-item-wrapper rounded-3 {{ $wrapperBorder }} {{ !$notification->is_read ? 'notif-unread' : '' }}"
     style="{{ $wrapperStyle }}"
     data-notif-id="{{ $notification->id }}"
     data-notif-type="{{ $type }}"
     data-notif-title="{{ e($notification->title) }}"
     data-notif-message="{{ e($notification->message) }}"
     data-notif-date="{{ $notification->created_at->format('d M Y, g:i A') }}"
     data-is-broadcast="{{ $isBroadcast ? 'true' : 'false' }}"
     data-is-unread="{{ !$notification->is_read ? 'true' : 'false' }}"
     data-ajax-read-url="{{ route('notifications.markAsRead', $notification->id) }}"
     data-redirect-url="{{ $notification->action_url ? url(parse_url($notification->action_url, PHP_URL_PATH) ?: '/') : '' }}"
     style="cursor: pointer;">

    <a href="{{ $notification->action_url ? url(parse_url($notification->action_url, PHP_URL_PATH) ?: '/') : 'javascript:void(0)' }}"
       class="notif-item d-flex align-items-start gap-3 p-3 text-decoration-none bg-transparent border-0"
       data-notif-link>

        {{-- Icon Badge --}}
        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
             style="width: 44px; height: 44px; {{ $iconBg }}">
            <i class="bi {{ $icon }} fs-5 {{ $iconClass }}"></i>
        </div>

        {{-- Content --}}
        <div class="flex-grow-1 min-w-0">
            <div class="d-flex align-items-center justify-content-between mb-1 gap-2">
                <h6 class="fw-bold text-dark mb-0 small text-truncate">{{ $notification->title }}</h6>

                {{-- Unread Glowing Green Dot --}}
                @if(!$notification->is_read)
                    <span class="notif-unread-dot flex-shrink-0" title="Unread"></span>
                @else
                    <i class="bi bi-check2 text-muted extra-small flex-shrink-0"></i>
                @endif
            </div>

            <p class="text-secondary extra-small mb-1 lh-sm">{{ $notification->message }}</p>

            <div class="d-flex align-items-center gap-2 flex-wrap mt-1">
                <span class="extra-small text-primary font-monospace">{{ $notification->created_at->diffForHumans() }}</span>
                {!! $badge !!}
            </div>
        </div>
    </a>
</div>
