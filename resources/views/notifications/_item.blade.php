@php
    $type = $notification->type ?? 'system';
    $isBroadcast = in_array($type, ['broadcast', 'admin']) ||
                   str_contains(strtolower($notification->title), 'broadcast') ||
                   str_contains(strtolower($notification->title), 'announcement');
    $isContest = in_array($type, ['contest', 'success']) ||
                 str_contains(strtolower($notification->title), 'contest');

    if ($isBroadcast) {
        $wrapperBorder = 'border-l-4 border-l-[#20165c] border-slate-200';
        $wrapperBg = !$notification->is_read ? 'bg-purple-50/50' : 'bg-white';
        $icon = 'bi-megaphone-fill';
        $iconClass = 'text-[#20165c]';
        $iconBg = 'background: rgba(32, 22, 92, 0.08); color: #20165c; border: 1px solid rgba(32, 22, 92, 0.15);';
        $badge = '<span class="badge text-white rounded-pill px-2.5 py-0.5 extra-small" style="background: #20165c; font-size: 0.65rem;"><i class="bi bi-megaphone-fill me-1"></i>Broadcast Announcement</span>';
    } elseif ($isContest) {
        $wrapperBorder = 'border-l-4 border-l-[#f47402] border-slate-200';
        $wrapperBg = !$notification->is_read ? 'bg-amber-50/50' : 'bg-white';
        $icon = 'bi-trophy-fill';
        $iconClass = 'text-[#f47402]';
        $iconBg = 'background: rgba(244, 116, 2, 0.1); color: #f47402; border: 1px solid rgba(244, 116, 2, 0.25);';
        $badge = '<span class="badge text-white rounded-pill px-2.5 py-0.5 extra-small" style="background: #f47402; font-size: 0.65rem;"><i class="bi bi-trophy-fill me-1"></i>Contest Alert</span>';
    } else {
        $wrapperBorder = !$notification->is_read ? 'border-l-4 border-l-[#20165c] border-slate-200' : 'border-slate-200';
        $wrapperBg = !$notification->is_read ? 'bg-purple-50/30' : 'bg-white';
        $icon = match($type) {
            'seller' => 'bi-shop-fill',
            'buyer'  => 'bi-bag-check-fill',
            'warning'=> 'bi-exclamation-triangle-fill',
            default  => 'bi-bell-fill',
        };
        $iconClass = match($type) {
            'seller' => 'text-[#20165c]',
            'buyer'  => 'text-emerald-600',
            'warning'=> 'text-[#f47402]',
            default  => 'text-[#20165c]',
        };
        $iconBg = 'background: rgba(32, 22, 92, 0.06); border: 1px solid rgba(32, 22, 92, 0.12);';
        $badge = '';
    }
@endphp

{{-- NOTIFICATION ITEM ROW (Clean White Card) --}}
<div class="notif-item-wrapper rounded-xl {{ $wrapperBorder }} {{ $wrapperBg }} border p-4 sm:p-5 shadow-sm mb-3 flex items-start gap-4 hover:border-[#20165c]/40 transition {{ !$notification->is_read ? 'notif-unread' : '' }}"
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
       class="d-flex align-items-start gap-3 w-100 text-decoration-none bg-transparent border-0"
       data-notif-link>

        {{-- Icon Badge --}}
        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
             style="width: 44px; height: 44px; {{ $iconBg }}">
            <i class="bi {{ $icon }} fs-5 {{ $iconClass }}"></i>
        </div>

        {{-- Content --}}
        <div class="flex-grow-1 min-w-0">
            <div class="d-flex align-items-center justify-content-between mb-1 gap-2">
                <h6 class="fw-bold text-[#121826] mb-0 small text-truncate">{{ $notification->title }}</h6>

                {{-- Unread Indicator: Tangerine Dot --}}
                @if(!$notification->is_read)
                    <span class="notif-unread-dot flex-shrink-0" title="Unread"></span>
                @else
                    <i class="bi bi-check2 text-slate-400 extra-small flex-shrink-0"></i>
                @endif
            </div>

            <p class="text-[#475569] extra-small mb-1.5 lh-sm">{{ $notification->message }}</p>

            <div class="d-flex align-items-center gap-2 flex-wrap mt-1">
                <span class="extra-small text-slate-400 font-monospace">{{ $notification->created_at->diffForHumans() }}</span>
                {!! $badge !!}
            </div>
        </div>
    </a>
</div>
