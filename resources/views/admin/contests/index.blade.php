@extends('layouts.admin')

@section('title', 'Design Contest Moderation & Control - Noksha Admin HQ')
@section('page_title', 'Contest Control')
@section('page_heading', 'Design Contest Moderation Hub')

@section('content')
<div class="space-y-6">


    <!-- STATS OVERVIEW CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm">
            <span class="text-xs text-gray-500 uppercase font-semibold">Total Tournaments</span>
            <div class="text-2xl font-black text-gray-900 dark:text-white mt-1">{{ number_format($totalContests) }}</div>
        </div>

        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-4 border {{ $pendingCount > 0 ? 'border-amber-400 bg-amber-50/20 dark:bg-amber-950/20' : 'border-gray-200 dark:border-gray-800' }} shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs text-amber-500 uppercase font-semibold">Pending Approval</span>
                @if($pendingCount > 0)
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                    </span>
                @endif
            </div>
            <div class="text-2xl font-black text-amber-500 mt-1">{{ number_format($pendingCount) }}</div>
        </div>

        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm">
            <span class="text-xs text-emerald-500 uppercase font-semibold">Active & Live</span>
            <div class="text-2xl font-black text-emerald-500 mt-1">{{ number_format($activeCount) }}</div>
        </div>

        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm">
            <span class="text-xs text-blue-500 uppercase font-semibold">In Judging</span>
            <div class="text-2xl font-black text-blue-500 mt-1">{{ number_format($judgingCount) }}</div>
        </div>

        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm">
            <span class="text-xs text-purple-500 uppercase font-semibold">Completed</span>
            <div class="text-2xl font-black text-purple-500 mt-1">{{ number_format($completedCount) }}</div>
        </div>

        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm">
            <span class="text-xs text-teal-500 uppercase font-semibold">Prize Bounty Pool</span>
            <div class="text-2xl font-black text-teal-500 mt-1 font-mono">৳{{ number_format($totalPrizePool, 0) }}</div>
        </div>
    </div>

    <!-- TABS, SEARCH, & ACTIONS BAR -->
    <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm flex items-center justify-between flex-wrap gap-3">
        <!-- Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto">
            <a href="{{ route('admin.contests.index', ['status' => 'all']) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $activeTab === 'all' ? 'bg-brand-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                All Contests
            </a>

            <a href="{{ route('admin.contests.index', ['status' => 'pending_approval']) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $activeTab === 'pending_approval' ? 'bg-amber-500 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                <i class="bi bi-clock-history"></i>
                <span>Pending Verification</span>
                @if($pendingCount > 0)
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $activeTab === 'pending_approval' ? 'bg-white text-amber-600' : 'bg-amber-500 text-white' }} font-bold">
                        {{ $pendingCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('admin.contests.index', ['status' => 'active']) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $activeTab === 'active' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                Active Live
            </a>

            <a href="{{ route('admin.contests.index', ['status' => 'judging']) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $activeTab === 'judging' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                In Judging
            </a>

            <a href="{{ route('admin.contests.index', ['status' => 'completed']) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $activeTab === 'completed' ? 'bg-purple-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                Completed
            </a>

            <a href="{{ route('admin.contests.index', ['status' => 'rejected']) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $activeTab === 'rejected' ? 'bg-rose-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                Rejected
            </a>
        </div>

        <!-- Right Side: Search & Launch Trigger -->
        <div class="flex items-center gap-3 flex-wrap">
            <form action="{{ route('admin.contests.index') }}" method="GET" class="relative">
                <input type="hidden" name="status" value="{{ $activeTab }}">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search title, TrxID, buyer..."
                       class="w-48 sm:w-64 pl-8 pr-3 py-1.5 text-xs rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:outline-none focus:border-brand-500">
                <i class="bi bi-search absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            </form>

            <button type="button" onclick="openCreateContestModal()" class="px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-700 hover:to-indigo-700 text-white text-xs font-bold shadow-md shadow-brand-500/20 transition-all flex items-center gap-1.5">
                <i class="bi bi-plus-circle-fill"></i> + Launch Noksha Contest
            </button>
        </div>
    </div>

    <!-- CONTESTS TABLE -->
    <div class="bg-white dark:bg-[#0F1623] rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 dark:bg-gray-800/40 text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="px-5 py-3.5">Contest & Organizer</th>
                        <th class="px-4 py-3.5">Category</th>
                        <th class="px-4 py-3.5">Prize Bounty</th>
                        <th class="px-4 py-3.5">Payment Ref / TrxID</th>
                        <th class="px-4 py-3.5">Submissions</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5">Deadline</th>
                        <th class="px-5 py-3.5 text-right">Moderation Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($contests as $contest)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors {{ $contest->status === 'pending_approval' ? 'bg-amber-50/30 dark:bg-amber-950/10' : '' }}">
                            
                            <!-- Title & Organizer -->
                            <td class="px-5 py-3.5 max-w-xs">
                                <a href="{{ route('contests.show', $contest->slug) }}" target="_blank" class="font-bold text-gray-900 dark:text-white hover:text-brand-500 block truncate" title="{{ $contest->title }}">
                                    {{ $contest->title }}
                                </a>
                                <div class="text-[11px] text-gray-400 flex items-center gap-1.5 mt-0.5">
                                    <i class="bi bi-person text-gray-500"></i>
                                    <span>{{ $contest->organizer ? $contest->organizer->name : 'Noksha Official' }}</span>
                                    <span class="text-gray-300 dark:text-gray-700">•</span>
                                    <span>{{ $contest->created_at->format('M d, Y') }}</span>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-medium">
                                    {{ $contest->display_category }}
                                </span>
                            </td>

                            <!-- Prize -->
                            <td class="px-4 py-3.5 whitespace-nowrap font-mono font-bold text-teal-600 dark:text-teal-400">
                                <div>৳{{ number_format($contest->prize_amount, 0) }}</div>
                                @if($contest->posting_fee > 0)
                                    <div class="text-[10px] text-gray-400 font-normal">+৳{{ number_format($contest->posting_fee, 0) }} fee</div>
                                @endif
                            </td>

                            <!-- Payment Reference / TrxID -->
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @if($contest->payment_reference)
                                    <span class="px-2 py-1 rounded bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-400 font-mono text-[11px] font-bold select-all" title="Verify in bKash/Nagad merchant panel">
                                        {{ $contest->payment_reference }}
                                    </span>
                                @else
                                    <span class="text-gray-400 italic">Admin bypass</span>
                                @endif
                            </td>

                            <!-- Entries Count -->
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <a href="{{ route('admin.contests.submissions', $contest->id) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-300 font-bold hover:underline">
                                    <i class="bi bi-images"></i>
                                    <span>{{ $contest->entries ? $contest->entries->count() : 0 }} designs</span>
                                </a>
                            </td>

                            <!-- Status & Guaranteed Shield -->
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <div class="space-y-1">
                                    @if($contest->status === 'pending_approval')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/15 text-amber-700 dark:text-amber-400 border border-amber-500/30">
                                            <i class="bi bi-clock-history"></i> Pending Review
                                        </span>
                                    @elseif($contest->status === 'active')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                            <i class="bi bi-play-fill"></i> Active Live
                                        </span>
                                    @elseif($contest->status === 'judging')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">
                                            <i class="bi bi-hourglass-split"></i> In Judging
                                        </span>
                                    @elseif($contest->status === 'completed')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20">
                                            <i class="bi bi-trophy-fill"></i> Completed
                                        </span>
                                    @elseif($contest->status === 'rejected')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20" title="{{ $contest->rejection_reason }}">
                                            <i class="bi bi-x-circle"></i> Rejected
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-500/10 text-gray-600 border border-gray-500/20">
                                            Cancelled
                                        </span>
                                    @endif

                                    @if($contest->is_guaranteed)
                                        <div>
                                            <span class="inline-flex items-center gap-0.5 text-[10px] text-emerald-600 font-semibold">
                                                <i class="bi bi-shield-fill-check"></i> Guaranteed
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Deadline -->
                            <td class="px-4 py-3.5 text-gray-500 dark:text-gray-400 whitespace-nowrap text-[11px]">
                                <div>{{ $contest->effective_deadline ? $contest->effective_deadline->format('M d, Y') : 'N/A' }}</div>
                                <div class="text-[10px] text-gray-400 font-mono">{{ $contest->remaining_time }}</div>
                            </td>

                            <!-- Moderation Controls -->
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    
                                    @if($contest->status === 'pending_approval')
                                        <!-- APPROVE & PUBLISH -->
                                        <form action="{{ route('admin.contests.approve', $contest->id) }}" method="POST" class="inline" onsubmit="return confirm('Verify TrxID and publish contest with Guaranteed Escrow?');">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-1 shadow-sm">
                                                <i class="bi bi-check2-circle"></i> Approve & Publish
                                            </button>
                                        </form>

                                        <!-- REJECT WITH NOTE -->
                                        <button type="button"
                                                onclick="openRejectModal({{ $contest->id }}, '{{ addslashes($contest->title) }}')"
                                                class="px-2.5 py-1 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800 rounded-lg text-xs font-bold transition-all">
                                            <i class="bi bi-x-circle"></i> Reject
                                        </button>
                                    @else
                                        <!-- VIEW PUBLIC -->
                                        <a href="{{ route('contests.show', $contest->slug) }}" target="_blank"
                                           class="p-1.5 text-gray-500 hover:text-brand-500 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors"
                                           title="View Public Page">
                                            <i class="bi bi-box-arrow-up-right text-sm"></i>
                                        </a>

                                        <!-- SUBMISSIONS -->
                                        <a href="{{ route('admin.contests.submissions', $contest->id) }}"
                                           class="p-1.5 text-purple-600 hover:bg-purple-50 dark:hover:bg-purple-950/40 rounded-lg transition-colors"
                                           title="Manage Submissions & Award Winner">
                                            <i class="bi bi-trophy text-sm"></i>
                                        </a>

                                        <!-- FORCE DELETE -->
                                        <form action="{{ route('admin.contests.destroy', $contest->id) }}" method="POST" class="inline" onsubmit="return confirm('Permanently delete contest and submissions?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-lg transition-colors" title="Delete Contest">
                                                <i class="bi bi-trash-fill text-sm"></i>
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-gray-500 dark:text-gray-400">
                                <i class="bi bi-trophy text-4xl text-gray-400 block mb-2"></i>
                                No contests found under this status filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($contests->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                {{ $contests->links() }}
            </div>
        @endif
    </div>

</div>

<!-- CREATE OFFICIAL NOKSHA CONTEST MODAL (SUPER ADMIN DIRECT PUBLISH) -->
<div id="createContestModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-[#0F1623] rounded-2xl max-w-lg w-full p-6 border border-gray-200 dark:border-gray-800 shadow-2xl">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-800 mb-4">
            <h3 class="font-bold text-sm text-gray-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-trophy-fill text-brand-500"></i> Launch Official Noksha Contest
            </h3>
            <button type="button" onclick="closeCreateContestModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-white">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.contests.store') }}" class="space-y-3 text-xs">
            @csrf
            <div>
                <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Contest Title *</label>
                <input type="text" name="title" required placeholder="e.g., Bangladeshi FinTech Mobile App UI Challenge" class="w-full px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:outline-none focus:border-brand-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Category *</label>
                    <select name="category" required class="w-full px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white">
                        <option value="UI/UX Design">UI/UX Design</option>
                        <option value="Logo & Branding">Logo & Branding</option>
                        <option value="Vector & Illustration">Vector & Illustration</option>
                        <option value="Social Media Design">Social Media Design</option>
                        <option value="Print & Packaging">Print & Packaging</option>
                        <option value="Typography">Typography & Bangla</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Prize Bounty (৳ BDT) *</label>
                    <input type="number" step="100" min="1000" name="prize_amount" required placeholder="5000" class="w-full px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white font-mono">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Duration (Days) *</label>
                    <select name="deadline_days" required class="w-full px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white">
                        <option value="3">3 Days (Sprint)</option>
                        <option value="7" selected>7 Days (1 Week)</option>
                        <option value="14">14 Days (2 Weeks)</option>
                        <option value="30">30 Days (1 Month)</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Required Dimensions</label>
                    <input type="text" name="required_dimensions" placeholder="e.g. 1920x1080px or Vector" class="w-full px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>

            <div>
                <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Contest Brief & Creative Guidelines *</label>
                <textarea name="description" rows="4" required placeholder="Outline specifications, target audience, required artboards, color schemes..." class="w-full px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeCreateContestModal()" class="px-4 py-2 rounded-xl font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold transition-colors">
                    Publish Instantly
                </button>
            </div>
        </form>
    </div>
</div>

<!-- REJECT CONTEST MODAL -->
<div id="rejectContestModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-[#0F1623] rounded-2xl max-w-md w-full p-6 border border-gray-200 dark:border-gray-800 shadow-2xl">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-800 mb-4">
            <h3 class="font-bold text-sm text-rose-600 flex items-center gap-2">
                <i class="bi bi-x-circle-fill"></i> Reject Contest Submission
            </h3>
            <button type="button" onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-white">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="rejectContestForm" method="POST" action="" class="space-y-3 text-xs">
            @csrf
            <div>
                <p class="text-gray-600 dark:text-gray-300 mb-2 font-medium" id="rejectContestTitle">Reject contest</p>
                <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Reason for Rejection *</label>
                <textarea name="rejection_reason" rows="3" required placeholder="e.g. Payment TrxID could not be verified on bKash merchant statement." class="w-full px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 rounded-xl font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold transition-colors">
                    Confirm Rejection
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateContestModal() {
        document.getElementById('createContestModal').classList.remove('hidden');
    }
    function closeCreateContestModal() {
        document.getElementById('createContestModal').classList.add('hidden');
    }

    function openRejectModal(contestId, title) {
        document.getElementById('rejectContestTitle').textContent = 'Contest: ' + title;
        document.getElementById('rejectContestForm').action = '/admin/contests/' + contestId + '/reject';
        document.getElementById('rejectContestModal').classList.remove('hidden');
    }
    function closeRejectModal() {
        document.getElementById('rejectContestModal').classList.add('hidden');
    }
</script>
@endsection
