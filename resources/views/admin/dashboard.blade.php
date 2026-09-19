@extends('layouts.admin')

@section('title', 'Admin Executive Command Center - Noksha (নকশা)')
@section('page_title', 'Overview')
@section('page_heading', 'Executive Command Center')

@section('content')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="space-y-6">

    <!-- TOP ROW: HIGH-IMPACT STAT CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        
        <!-- Total Platform Users -->
        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-5 border border-gray-200 dark:border-gray-800 shadow-sm relative overflow-hidden group hover:border-brand-500/50 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Users</span>
                <div class="w-9 h-9 rounded-xl bg-sky-500/10 text-sky-500 flex items-center justify-center">
                    <i class="bi bi-people-fill text-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($totalUsers) }}</div>
                <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1.5">
                    <span class="text-emerald-500 font-semibold">{{ $activeSellers }} sellers</span>
                    <span>•</span>
                    <span>{{ $totalBuyers }} buyers</span>
                </div>
            </div>
        </div>

        <!-- Active Sellers -->
        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-5 border border-gray-200 dark:border-gray-800 shadow-sm relative overflow-hidden group hover:border-emerald-500/50 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Active Creators</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                    <i class="bi bi-patch-check-fill text-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($activeSellers) }}</div>
                <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1.5">
                    @if($pendingKyc > 0)
                        <span class="text-amber-500 font-semibold flex items-center gap-1">
                            <i class="bi bi-clock-history"></i> {{ $pendingKyc }} KYC awaiting
                        </span>
                    @else
                        <span class="text-emerald-500 font-semibold">100% KYC clear</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pending Moderation -->
        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-5 border {{ $pendingResources > 0 ? 'border-amber-400/80 dark:border-amber-500/50 bg-amber-500/[0.02]' : 'border-gray-200 dark:border-gray-800' }} shadow-sm relative overflow-hidden group transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold {{ $pendingResources > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-gray-500 dark:text-gray-400' }} uppercase tracking-wider">
                    Pending Approval
                </span>
                <div class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center {{ $pendingResources > 0 ? 'animate-bounce' : '' }}">
                    <i class="bi bi-hourglass-split text-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($pendingResources) }}</div>
                <div class="text-[11px] mt-1">
                    @if($pendingResources > 0)
                        <a href="{{ route('admin.resources.index', ['status' => 'pending']) }}" class="text-amber-600 dark:text-amber-400 font-bold hover:underline flex items-center gap-1">
                            Moderate Now <i class="bi bi-arrow-right"></i>
                        </a>
                    @else
                        <span class="text-emerald-500 font-semibold">Queue clean</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Ongoing Contests -->
        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-5 border border-gray-200 dark:border-gray-800 shadow-sm relative overflow-hidden group hover:border-purple-500/50 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Live Contests</span>
                <div class="w-9 h-9 rounded-xl bg-purple-500/10 text-purple-500 flex items-center justify-center">
                    <i class="bi bi-trophy-fill text-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($ongoingContests) }}</div>
                <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                    <a href="{{ route('admin.contests.index') }}" class="text-purple-600 dark:text-purple-400 font-medium hover:underline">
                        {{ $totalContests }} total tournaments
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Revenue & Platform Cut -->
        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-5 border border-gray-200 dark:border-gray-800 shadow-sm relative overflow-hidden group hover:border-teal-500/50 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Platform Cut (20%)</span>
                <div class="w-9 h-9 rounded-xl bg-teal-500/10 text-teal-500 flex items-center justify-center">
                    <i class="bi bi-wallet2 text-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-teal-600 dark:text-teal-400">৳{{ number_format($platformCut, 2) }}</div>
                <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                    GMV: <span class="font-semibold text-gray-800 dark:text-gray-200">৳{{ number_format($totalRevenue, 2) }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- ROW 2: ACTIONABLE QUEUE & QUICK BROADCAST -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left: Actionable Pending Resource Approvals Table (2 cols) -->
        <div class="lg:col-span-2 bg-white dark:bg-[#0F1623] rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 dark:border-gray-800/80 flex items-center justify-between flex-wrap gap-2">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center">
                        <i class="bi bi-shield-exclamation text-base"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-gray-900 dark:text-white">Resource Moderation Queue</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Latest assets pending Super Admin moderation</p>
                    </div>
                </div>
                <a href="{{ route('admin.resources.index') }}" class="text-xs font-bold text-brand-600 dark:text-brand-400 hover:underline flex items-center gap-1">
                    View All Assets <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50/80 dark:bg-gray-800/40 text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold border-b border-gray-100 dark:border-gray-800">
                        <tr>
                            <th class="px-4 py-3">Asset</th>
                            <th class="px-4 py-3">Creator</th>
                            <th class="px-4 py-3">Category / Price</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Moderation Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($recentPendingResources as $res)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-800 flex-shrink-0 border border-gray-200 dark:border-gray-700">
                                            @if($res->preview_image)
                                                <img src="{{ asset('storage/' . $res->preview_image) }}" class="w-full h-full object-cover" alt="{{ $res->title }}">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <i class="bi bi-file-earmark-image"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="max-w-[200px]">
                                            <div class="font-bold text-gray-900 dark:text-white truncate" title="{{ $res->title }}">{{ $res->title }}</div>
                                            <div class="text-[10px] text-gray-400 font-mono uppercase">{{ $res->file_type }} • {{ $res->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-gray-900 dark:text-gray-200">{{ $res->owner->name ?? 'Unknown' }}</div>
                                    <div class="text-[11px] text-gray-400">{{ $res->owner->email ?? 'No email' }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-block px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-[11px] font-medium">
                                        {{ $res->category->name ?? 'General' }}
                                    </span>
                                    <div class="font-bold text-gray-900 dark:text-gray-100 mt-0.5">
                                        {{ $res->is_paid ? '৳' . number_format($res->price, 2) : 'Free' }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($res->status === 'approved')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                            Approved
                                        </span>
                                    @elseif($res->status === 'rejected')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                            Rejected
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 animate-pulse">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Admin Free Download -->
                                        <a href="{{ route('admin.resources.download', $res->id) }}" title="Free Admin Download (Bypass Paywall)" class="p-1.5 text-gray-500 hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-400 hover:bg-brand-50 dark:hover:bg-brand-500/10 rounded-lg transition-colors">
                                            <i class="bi bi-download text-sm"></i>
                                        </a>

                                        @if($res->status !== 'approved')
                                            <!-- Approve -->
                                            <form method="POST" action="{{ route('admin.resources.approve', $res->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" title="Approve & Publish" class="px-2 py-1 rounded bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] transition-colors">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($res->status !== 'rejected')
                                            <!-- Reject with Reason Trigger -->
                                            <button type="button" onclick="openRejectModal('{{ $res->id }}', '{{ addslashes($res->title) }}')" title="Reject Asset" class="px-2 py-1 rounded bg-rose-600/10 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white font-bold text-[11px] transition-colors border border-rose-500/30">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <i class="bi bi-check-circle-fill text-3xl text-emerald-500 block mb-1"></i>
                                    Moderation queue is completely clear.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right: Broadcast Notification Card (1 col) -->
        <div class="space-y-6">
            <!-- Broadcast Card -->
            <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-5 border border-gray-200 dark:border-gray-800 shadow-sm">
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-500 flex items-center justify-center">
                        <i class="bi bi-megaphone-fill text-base"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-gray-900 dark:text-white">Broadcast Alert</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Send system announcement to all users</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.broadcast') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Announcement Title</label>
                        <input type="text" name="title" required placeholder="e.g., Scheduled Platform Maintenance" class="w-full px-3 py-2 rounded-xl text-xs bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:outline-none focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Message Body</label>
                        <textarea name="message" rows="3" required placeholder="Write message details..." class="w-full px-3 py-2 rounded-xl text-xs bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:outline-none focus:border-brand-500"></textarea>
                    </div>
                    <button type="submit" class="w-full py-2 rounded-xl bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-700 hover:to-indigo-700 text-white font-bold text-xs shadow-sm transition-all flex items-center justify-center gap-1.5">
                        <i class="bi bi-send-fill"></i> Broadcast to All Users
                    </button>
                </form>
            </div>

            <!-- Quick Activity Stream -->
            <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-5 border border-gray-200 dark:border-gray-800 shadow-sm">
                <h4 class="font-bold text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3">Live Platform Feed</h4>
                <div class="space-y-3">
                    @forelse($recentActivity as $act)
                        <div class="flex items-start gap-2.5 text-xs">
                            <div class="p-1.5 rounded-lg bg-gray-100 dark:bg-gray-800 flex-shrink-0">
                                <i class="bi {{ $act['icon'] }}"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-gray-900 dark:text-white truncate">{{ $act['title'] }}</div>
                                <div class="text-[11px] text-gray-500 dark:text-gray-400 truncate">{{ $act['desc'] }}</div>
                            </div>
                            <span class="text-[10px] text-gray-400 flex-shrink-0">{{ $act['time'] }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400">No recent activity logged.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <!-- ROW 3: RECENT USERS TABLE WITH 1-CLICK STATUS TOGGLE -->
    <div class="bg-white dark:bg-[#0F1623] rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 dark:border-gray-800/80 flex items-center justify-between flex-wrap gap-2">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-sky-500/10 text-sky-500 flex items-center justify-center">
                    <i class="bi bi-person-lines-fill text-base"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-gray-900 dark:text-white">Recent User Registrations</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Inspect newly onboarded accounts and toggle access status</p>
                </div>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-brand-600 dark:text-brand-400 hover:underline flex items-center gap-1">
                Manage All Users <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 dark:bg-gray-800/40 text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="px-5 py-3">User Profile</th>
                        <th class="px-5 py-3">Role</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Joined</th>
                        <th class="px-5 py-3 text-right">Account Control</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($recentUsers as $usr)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-brand-600/20 text-brand-600 dark:text-brand-400 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                        {{ strtoupper(substr($usr->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $usr->name }}</div>
                                        <div class="text-[11px] text-gray-400 font-mono">{{ $usr->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ $usr->role === 'admin' ? 'bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20' : ($usr->role === 'seller' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300') }}">
                                    {{ $usr->role }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                @if($usr->status === 'suspended')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                        Suspended
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                        Active
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400">
                                {{ $usr->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                @if($usr->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.toggleStatus', $usr->id) }}" class="inline">
                                        @csrf
                                        <button type="submit"
                                                onclick="return confirm('Change account status for {{ addslashes($usr->name) }}?')"
                                                class="px-3 py-1 rounded-lg text-xs font-semibold transition-colors
                                                {{ $usr->status === 'suspended' ? 'bg-emerald-600 text-white hover:bg-emerald-700' : 'bg-rose-500/10 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white border border-rose-500/30' }}">
                                            {{ $usr->status === 'suspended' ? 'Activate' : 'Suspend' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[11px] text-gray-400 italic">Current Admin</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- REJECTION MODAL (For rejecting resources with prompt reason) -->
<div id="rejectResourceModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-[#0F1623] rounded-2xl max-w-md w-full p-6 border border-gray-200 dark:border-gray-800 shadow-2xl">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-800 mb-4">
            <h3 class="font-bold text-sm text-gray-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill text-rose-500"></i> Reject Resource Asset
            </h3>
            <button type="button" onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-white">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="rejectResourceForm" method="POST" action="">
            @csrf
            <p class="text-xs text-gray-600 dark:text-gray-300 mb-3">
                Specify why <strong id="rejectResourceTitle" class="text-gray-900 dark:text-white"></strong> is being rejected. This feedback will be sent directly to the creator.
            </p>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Rejection Reason</label>
                <textarea name="rejection_reason" rows="3" required placeholder="e.g., Low preview resolution, corrupted zip archive, or missing source components." class="w-full px-3 py-2 rounded-xl text-xs bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:outline-none focus:border-rose-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition-colors">
                    Confirm Rejection
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectModal(resourceId, resourceTitle) {
        document.getElementById('rejectResourceTitle').textContent = `"${resourceTitle}"`;
        document.getElementById('rejectResourceForm').action = `/admin/resources/${resourceId}/reject`;
        document.getElementById('rejectResourceModal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectResourceModal').classList.add('hidden');
    }
</script>
@endsection
