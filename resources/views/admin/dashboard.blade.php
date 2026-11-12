@extends('layouts.admin')

@section('title', 'Admin Executive Command Center - Noksha')
@section('page_title', 'Overview')
@section('page_heading', 'Executive Command Center')

@section('content')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="space-y-6">

    <!-- TOP ROW: HIGH-IMPACT STAT CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        
        <!-- Total Platform Users -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-[#20165c]/40 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Users</span>
                <div class="w-9 h-9 rounded-xl bg-purple-50 text-[#20165c] flex items-center justify-center">
                    <i class="bi bi-people-fill text-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-[#121826]">{{ number_format($totalUsers) }}</div>
                <div class="text-[11px] text-slate-500 mt-1 flex items-center gap-1.5">
                    <span class="text-emerald-600 font-semibold">{{ $activeSellers }} sellers</span>
                    <span>•</span>
                    <span>{{ $totalBuyers }} buyers</span>
                </div>
            </div>
        </div>

        <!-- Active Sellers -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-emerald-500/50 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Active Creators</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="bi bi-patch-check-fill text-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-[#121826]">{{ number_format($activeSellers) }}</div>
                <div class="text-[11px] text-slate-500 mt-1 flex items-center gap-1.5">
                    @if($pendingKyc > 0)
                        <span class="text-amber-600 font-semibold flex items-center gap-1">
                            <i class="bi bi-clock-history"></i> {{ $pendingKyc }} KYC awaiting
                        </span>
                    @else
                        <span class="text-emerald-600 font-semibold">100% KYC clear</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pending Moderation -->
        <div class="bg-white rounded-2xl p-5 border {{ $pendingResources > 0 ? 'border-amber-300 bg-amber-50/20' : 'border-slate-200' }} shadow-sm relative overflow-hidden group transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold {{ $pendingResources > 0 ? 'text-amber-700' : 'text-slate-500' }} uppercase tracking-wider">
                    Pending Approval
                </span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center {{ $pendingResources > 0 ? 'animate-bounce' : '' }}">
                    <i class="bi bi-hourglass-split text-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-[#121826]">{{ number_format($pendingResources) }}</div>
                <div class="text-[11px] mt-1">
                    @if($pendingResources > 0)
                        <a href="{{ route('admin.resources.index', ['status' => 'pending']) }}" class="text-amber-600 font-bold hover:underline flex items-center gap-1">
                            Moderate Now <i class="bi bi-arrow-right"></i>
                        </a>
                    @else
                        <span class="text-emerald-600 font-semibold">Queue clean</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Ongoing Contests -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-[#f47402]/50 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Live Contests</span>
                <div class="w-9 h-9 rounded-xl bg-orange-50 text-[#f47402] flex items-center justify-center">
                    <i class="bi bi-trophy-fill text-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-[#121826]">{{ number_format($ongoingContests) }}</div>
                <div class="text-[11px] text-slate-500 mt-1">
                    <a href="{{ route('admin.contests.index') }}" class="text-[#20165c] font-medium hover:underline">
                        {{ $totalContests }} total tournaments
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Revenue & Platform Cut -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-[#20165c]/40 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Platform Cut (20%)</span>
                <div class="w-9 h-9 rounded-xl bg-purple-50 text-[#20165c] flex items-center justify-center">
                    <i class="bi bi-wallet2 text-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-[#20165c]">৳{{ number_format($platformCut, 2) }}</div>
                <div class="text-[11px] text-slate-500 mt-1">
                    GMV: <span class="font-semibold text-slate-800">৳{{ number_format($totalRevenue, 2) }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- ROW 2: ACTIONABLE QUEUE & QUICK BROADCAST -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left: Actionable Pending Resource Approvals Table (2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between flex-wrap gap-2">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i class="bi bi-shield-exclamation text-base"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-[#121826]">Resource Moderation Queue</h3>
                        <p class="text-xs text-slate-500">Latest assets pending Super Admin moderation</p>
                    </div>
                </div>
                <a href="{{ route('admin.resources.index') }}" class="text-xs font-bold text-[#20165c] hover:text-[#f47402] hover:underline flex items-center gap-1">
                    View All Assets <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3">Asset</th>
                            <th class="px-4 py-3">Creator</th>
                            <th class="px-4 py-3">Category / Price</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Moderation Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentPendingResources as $res)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-lg overflow-hidden bg-slate-100 flex-shrink-0 border border-slate-200">
                                            @if($res->preview_image)
                                                <img src="{{ asset('storage/' . $res->preview_image) }}"
                                                     onerror="this.onerror=null; this.src='https://placehold.co/100x100/20165c/ffffff?text=PSD';"
                                                     class="w-full h-full object-cover"
                                                     alt="{{ $res->title }}">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                    <i class="bi bi-file-earmark-image"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="max-w-[200px]">
                                            <div class="font-bold text-[#121826] truncate" title="{{ $res->title }}">{{ $res->title }}</div>
                                            <div class="text-[10px] text-slate-400 font-mono uppercase">{{ $res->file_type }} • {{ $res->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-[#121826]">{{ $res->owner->name ?? 'Unknown' }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $res->owner->email ?? 'No email' }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-block px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[11px] font-medium border border-slate-200">
                                        {{ $res->category->name ?? 'General' }}
                                    </span>
                                    <div class="font-bold text-[#121826] mt-0.5">
                                        {{ $res->is_paid ? '৳' . number_format($res->price, 2) : 'Free' }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($res->status === 'approved')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Approved
                                        </span>
                                    @elseif($res->status === 'rejected')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            Rejected
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 animate-pulse">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Admin Free Download -->
                                        <a href="{{ route('admin.resources.download', $res->id) }}" title="Free Admin Download (Bypass Paywall)" class="p-1.5 text-slate-500 hover:text-[#20165c] hover:bg-purple-50 rounded-lg transition-colors">
                                            <i class="bi bi-download text-sm"></i>
                                        </a>

                                        @if($res->status !== 'approved')
                                            <!-- Approve -->
                                            <form method="POST" action="{{ route('admin.resources.approve', $res->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" title="Approve & Publish" class="px-2 py-1 rounded bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] transition-colors shadow-sm">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($res->status !== 'rejected')
                                            <!-- Reject with Reason Trigger -->
                                            <button type="button" onclick="openRejectModal('{{ $res->id }}', '{{ addslashes($res->title) }}')" title="Reject Asset" class="px-2 py-1 rounded bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white font-bold text-[11px] transition-colors border border-rose-200">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-500">
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
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-purple-50 text-[#20165c] flex items-center justify-center">
                            <i class="bi bi-megaphone-fill text-base"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-sm text-[#121826]">Broadcast Alert</h3>
                            <p class="text-xs text-slate-500">Send system announcement to all users</p>
                        </div>
                    </div>
                    <!-- Broadcast History Link in Card Header -->
                    <a href="{{ route('admin.broadcastHistory') }}"
                       class="px-2.5 py-1 rounded-lg bg-purple-50 text-[#20165c] hover:bg-purple-100 text-xs font-semibold flex items-center gap-1.5 transition-colors border border-purple-200/50"
                       title="View Broadcast History">
                        <i class="bi bi-clock-history"></i>
                        <span>History</span>
                    </a>
                </div>

                <form method="POST" action="{{ route('admin.broadcast') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Announcement Title</label>
                        <input type="text" name="title" required placeholder="e.g., Scheduled Platform Maintenance" class="w-full px-3 py-2 rounded-xl text-xs bg-slate-50 border border-slate-200 text-[#121826] placeholder-slate-400 focus:bg-white focus:outline-none focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Message Body</label>
                        <textarea name="message" rows="3" required placeholder="Write message details..." class="w-full px-3 py-2 rounded-xl text-xs bg-slate-50 border border-slate-200 text-[#121826] placeholder-slate-400 focus:bg-white focus:outline-none focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c]"></textarea>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1">
                        <button type="submit" class="w-full py-2 rounded-xl bg-[#20165c] hover:bg-[#181045] text-white font-bold text-xs shadow-sm transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                            <i class="bi bi-send-fill"></i> Broadcast Alert
                        </button>
                        <a href="{{ route('admin.broadcastHistory') }}"
                           class="w-full py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-all flex items-center justify-center gap-1.5 border border-slate-200">
                            <i class="bi bi-clock-history"></i> Broadcast History
                        </a>
                    </div>
                </form>
            </div>

            <!-- Quick Activity Stream -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                <h4 class="font-bold text-xs uppercase tracking-wider text-slate-500 mb-3">Live Platform Feed</h4>
                <div class="space-y-3">
                    @forelse($recentActivity as $act)
                        <div class="flex items-start gap-2.5 text-xs">
                            <div class="p-1.5 rounded-lg bg-slate-100 text-[#20165c] flex-shrink-0">
                                <i class="bi {{ $act['icon'] }}"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-[#121826] truncate">{{ $act['title'] }}</div>
                                <div class="text-[11px] text-slate-500 truncate">{{ $act['desc'] }}</div>
                            </div>
                            <span class="text-[10px] text-slate-400 flex-shrink-0">{{ $act['time'] }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400">No recent activity logged.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <!-- ROW 3: RECENT USERS TABLE WITH 1-CLICK STATUS TOGGLE -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between flex-wrap gap-2">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-purple-50 text-[#20165c] flex items-center justify-center">
                    <i class="bi bi-person-lines-fill text-base"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-[#121826]">Recent User Registrations</h3>
                    <p class="text-xs text-slate-500">Inspect newly onboarded accounts and toggle access status</p>
                </div>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-[#20165c] hover:text-[#f47402] hover:underline flex items-center gap-1">
                Manage All Users <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3">User Profile</th>
                        <th class="px-5 py-3">Role</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Joined</th>
                        <th class="px-5 py-3 text-right">Account Control</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($recentUsers as $usr)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-purple-100 text-[#20165c] flex items-center justify-center font-bold text-xs flex-shrink-0">
                                        {{ strtoupper(substr($usr->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-[#121826]">{{ $usr->name }}</div>
                                        <div class="text-[11px] text-slate-500 font-mono">{{ $usr->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ $usr->role === 'admin' ? 'bg-purple-50 text-[#20165c] border border-purple-200' : ($usr->role === 'seller' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200') }}">
                                    {{ $usr->role }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                @if($usr->status === 'suspended')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        Suspended
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Active
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-slate-500">
                                {{ $usr->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                @if($usr->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.toggleStatus', $usr->id) }}" class="inline">
                                        @csrf
                                        <button type="submit"
                                                onclick="return confirm('Change account status for {{ addslashes($usr->name) }}?')"
                                                class="px-3 py-1 rounded-lg text-xs font-semibold transition-colors
                                                {{ $usr->status === 'suspended' ? 'bg-emerald-600 text-white hover:bg-emerald-700' : 'bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white border border-rose-200' }}">
                                            {{ $usr->status === 'suspended' ? 'Activate' : 'Suspend' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[11px] text-slate-400 italic">Current Admin</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- DANGER ZONE: 1-CLICK SYSTEM PURGE & FACTORY RESET (SUPER ADMIN ONLY) -->
    <div id="danger-zone" x-data="{ openPurgeModal: false, confirmPhrase: '' }" class="bg-rose-50/60 border border-rose-200 rounded-2xl p-6 relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-rose-100 text-rose-700 border border-rose-200">
                        <i class="bi bi-shield-slash-fill me-1"></i> Danger Zone
                    </span>
                    <span class="text-xs font-mono text-rose-600">Super Admin Only</span>
                </div>
                <h3 class="text-lg font-black text-rose-950">System Purge & Factory Reset</h3>
                <p class="text-xs text-rose-700/80 max-w-2xl leading-relaxed">
                    Wipe all marketplace resources, reviews, contests, transactions, and non-admin users in 1 click.
                </p>
            </div>
            <div class="flex-shrink-0">
                <button type="button"
                        @click="openPurgeModal = true; confirmPhrase = ''"
                        class="px-5 py-2.5 rounded-xl font-bold text-xs bg-rose-600 hover:bg-rose-700 text-white shadow-sm flex items-center gap-2 transition-all transform hover:scale-[1.02] cursor-pointer">
                    <i class="bi bi-radioactive"></i>
                    <span>Factory Reset System</span>
                </button>
            </div>
        </div>

        <!-- Alpine.js Confirmation Modal -->
        <div x-show="openPurgeModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             style="display: none;"
             @keydown.escape.window="openPurgeModal = false">
            <div class="bg-white border border-rose-200 rounded-2xl max-w-lg w-full p-6 shadow-2xl relative"
                 @click.away="openPurgeModal = false">
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2 text-rose-600">
                        <i class="bi bi-exclamation-octagon-fill text-xl"></i>
                        <h4 class="font-extrabold text-sm text-[#121826] uppercase tracking-wider">Confirm Factory System Purge</h4>
                    </div>
                    <button type="button" @click="openPurgeModal = false" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <form method="POST" action="{{ route('admin.system.purge') }}">
                    @csrf
                    <div class="space-y-3 mb-5">
                        <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-900 leading-relaxed">
                            <strong class="text-rose-700 flex items-center gap-1.5 mb-1 font-bold">
                                <i class="bi bi-exclamation-triangle-fill"></i> Warning: This action cannot be undone!
                            </strong>
                            Wipe all marketplace resources, reviews, contests, transactions, and non-admin users in 1 click. Your Super Admin account will remain preserved.
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Type <span class="font-mono text-rose-600 font-bold">RESET-NOKSHA</span> to confirm:
                            </label>
                            <input type="text"
                                   name="confirm_phrase"
                                   x-model="confirmPhrase"
                                   placeholder="RESET-NOKSHA"
                                   required
                                   autocomplete="off"
                                   class="w-full px-3.5 py-2.5 rounded-xl text-sm font-mono bg-slate-50 border border-rose-300 text-[#121826] placeholder-slate-400 focus:outline-none focus:bg-white focus:border-rose-500 focus:ring-1 focus:ring-rose-500">
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button"
                                @click="openPurgeModal = false"
                                class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit"
                                :disabled="confirmPhrase !== 'RESET-NOKSHA'"
                                :class="confirmPhrase === 'RESET-NOKSHA' ? 'bg-rose-600 hover:bg-rose-700 text-white cursor-pointer' : 'bg-slate-100 text-slate-400 cursor-not-allowed border border-slate-200'"
                                class="px-5 py-2 rounded-xl text-xs font-extrabold flex items-center gap-1.5 transition-all shadow-sm">
                            <i class="bi bi-trash3-fill"></i>
                            <span>Purge All Data Now</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<!-- REJECTION MODAL (For rejecting resources with prompt reason) -->
<div id="rejectResourceModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 border border-slate-200 shadow-2xl">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <h3 class="font-bold text-sm text-[#121826] flex items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill text-rose-500"></i> Reject Resource Asset
            </h3>
            <button type="button" onclick="closeRejectModal()" class="text-slate-400 hover:text-slate-600">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="rejectResourceForm" method="POST" action="">
            @csrf
            <p class="text-xs text-slate-600 mb-3">
                Specify why <strong id="rejectResourceTitle" class="text-[#121826]"></strong> is being rejected. This feedback will be sent directly to the creator.
            </p>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Rejection Reason</label>
                <textarea name="rejection_reason" rows="3" required placeholder="e.g., Low preview resolution, corrupted zip archive, or missing source components." class="w-full px-3 py-2 rounded-xl text-xs bg-slate-50 border border-slate-200 text-[#121826] placeholder-slate-400 focus:bg-white focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition-colors shadow-sm">
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