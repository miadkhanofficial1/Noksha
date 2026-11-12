@php
    $rawType = strtolower($notification->type ?? 'system');
    $rawTitle = strtolower($notification->title ?? '');

    $isBroadcast = in_array($rawType, ['broadcast', 'admin', 'announcement']) ||
                   str_contains($rawTitle, 'broadcast') ||
                   str_contains($rawTitle, 'announcement');

    $isSale = in_array($rawType, ['sale', 'template_sale', 'seller', 'order', 'buyer', 'purchase']) ||
              str_contains($rawTitle, 'sold') ||
              str_contains($rawTitle, 'sale') ||
              str_contains($rawTitle, 'order') ||
              str_contains($rawTitle, 'purchased');

    $isUpload = in_array($rawType, ['upload', 'resource', 'asset']) ||
                str_contains($rawTitle, 'asset') ||
                str_contains($rawTitle, 'submission') ||
                str_contains($rawTitle, 'approved') ||
                str_contains($rawTitle, 'declined') ||
                str_contains($rawTitle, 'template upload');

    $isWallet = in_array($rawType, ['wallet', 'finance', 'payout', 'withdrawal']) ||
                str_contains($rawTitle, 'payout') ||
                str_contains($rawTitle, 'withdrawal') ||
                str_contains($rawTitle, 'balance') ||
                str_contains($rawTitle, 'earnings');

    $isKyc = in_array($rawType, ['kyc', 'verification', 'contributor']) ||
             str_contains($rawTitle, 'verification') ||
             str_contains($rawTitle, 'identity') ||
             str_contains($rawTitle, 'contributor');

    $isContest = in_array($rawType, ['contest', 'success']) ||
                 str_contains($rawTitle, 'contest');

    if ($isBroadcast) {
        $wrapperBorder = 'border-l-4 border-l-indigo-600 border-slate-200';
        $wrapperBg = !$notification->is_read ? 'bg-indigo-50/40' : 'bg-white';
        $iconContainerClass = 'bg-indigo-100 text-indigo-600';
        $badge = '<span class="badge rounded-pill px-2.5 py-1 bg-indigo-50 text-indigo-700 border border-indigo-200 extra-small" style="font-size: 0.65rem;"><i class="bi bi-megaphone-fill me-1"></i>Broadcast</span>';
        $iconSvg = '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>';
    } elseif ($isSale) {
        $wrapperBorder = 'border-l-4 border-l-emerald-600 border-slate-200';
        $wrapperBg = !$notification->is_read ? 'bg-emerald-50/40' : 'bg-white';
        $iconContainerClass = 'bg-emerald-100 text-emerald-600';
        $badge = '<span class="badge rounded-pill px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 extra-small" style="font-size: 0.65rem;"><i class="bi bi-bag-check-fill me-1"></i>Sale / Order</span>';
        $iconSvg = '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>';
    } elseif ($isUpload) {
        $wrapperBorder = 'border-l-4 border-l-[#20165c] border-slate-200';
        $wrapperBg = !$notification->is_read ? 'bg-purple-50/40' : 'bg-white';
        $iconContainerClass = 'bg-purple-100 text-[#20165c]';
        $badge = '<span class="badge rounded-pill px-2.5 py-1 bg-purple-50 text-[#20165c] border border-purple-200 extra-small" style="font-size: 0.65rem;"><i class="bi bi-cloud-arrow-up-fill me-1"></i>Asset</span>';
        $iconSvg = '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>';
    } elseif ($isWallet) {
        $wrapperBorder = 'border-l-4 border-l-[#f47402] border-slate-200';
        $wrapperBg = !$notification->is_read ? 'bg-orange-50/40' : 'bg-white';
        $iconContainerClass = 'bg-orange-100 text-[#f47402]';
        $badge = '<span class="badge rounded-pill px-2.5 py-1 bg-orange-50 text-[#f47402] border border-orange-200 extra-small" style="font-size: 0.65rem;"><i class="bi bi-wallet2 me-1"></i>Finance</span>';
        $iconSvg = '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>';
    } elseif ($isKyc) {
        $wrapperBorder = 'border-l-4 border-l-sky-600 border-slate-200';
        $wrapperBg = !$notification->is_read ? 'bg-sky-50/40' : 'bg-white';
        $iconContainerClass = 'bg-sky-100 text-sky-600';
        $badge = '<span class="badge rounded-pill px-2.5 py-1 bg-sky-50 text-sky-700 border border-sky-200 extra-small" style="font-size: 0.65rem;"><i class="bi bi-shield-check me-1"></i>KYC</span>';
        $iconSvg = '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>';
    } elseif ($isContest) {
        $wrapperBorder = 'border-l-4 border-l-amber-500 border-slate-200';
        $wrapperBg = !$notification->is_read ? 'bg-amber-50/40' : 'bg-white';
        $iconContainerClass = 'bg-amber-100 text-amber-600';
        $badge = '<span class="badge rounded-pill px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 extra-small" style="font-size: 0.65rem;"><i class="bi bi-trophy-fill me-1"></i>Contest</span>';
        $iconSvg = '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2 0h4m5-18v4m-2-2h4m3 6v1a6 6 0 01-6 6H9a6 6 0 01-6-6V7h14zm0 0a3 3 0 003-3V3.5a.5.5 0 00-.5-.5h-2.5M7 7H4.5a.5.5 0 00-.5.5V5a3 3 0 003 3z"/></svg>';
    } else {
        $wrapperBorder = !$notification->is_read ? 'border-l-4 border-l-[#20165c] border-slate-200' : 'border-slate-200';
        $wrapperBg = !$notification->is_read ? 'bg-purple-50/30' : 'bg-white';
        $iconContainerClass = 'bg-slate-100 text-slate-600';
        $badge = '';
        $iconSvg = '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>';
    }
@endphp

{{-- NOTIFICATION ITEM ROW (Clean White Card) --}}
<div class="notif-item-wrapper rounded-xl {{ $wrapperBorder }} {{ $wrapperBg }} border p-4 sm:p-5 shadow-sm mb-3 flex items-start gap-4 hover:border-[#20165c]/40 transition {{ !$notification->is_read ? 'notif-unread' : '' }}"
     data-notif-id="{{ $notification->id }}"
     data-notif-type="{{ $notification->type }}"
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
        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 {{ $iconContainerClass }}"
             style="width: 44px; height: 44px;">
            {!! $iconSvg !!}
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
