@extends('layouts.admin')

@section('title', 'Contributor Verification Hub - Noksha Admin HQ')
@section('page_title', 'Seller Verifications')
@section('page_heading', 'Contributor Verification Hub')

@section('content')
<div class="space-y-6">

    <!-- OVERVIEW STATS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Pending -->
        <a href="{{ route('admin.verifications.index', ['status' => 'pending']) }}" class="block group">
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:border-[#f47402]/60 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pending Reviews</span>
                    <div class="w-9 h-9 rounded-xl bg-orange-50 text-[#f47402] flex items-center justify-center">
                        <i class="bi bi-clock-history text-lg"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-2xl font-black text-[#f47402]">{{ number_format($pendingCount) }}</div>
                    <div class="text-[11px] text-slate-500 mt-1">Awaiting Super Admin inspection</div>
                </div>
            </div>
        </a>

        <!-- Approved -->
        <a href="{{ route('admin.verifications.index', ['status' => 'approved']) }}" class="block group">
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:border-emerald-500/60 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Approved Sellers</span>
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="bi bi-patch-check-fill text-lg"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-2xl font-black text-emerald-600">{{ number_format($approvedCount) }}</div>
                    <div class="text-[11px] text-slate-500 mt-1">Verified creator credentials</div>
                </div>
            </div>
        </a>

        <!-- Rejected -->
        <a href="{{ route('admin.verifications.index', ['status' => 'rejected']) }}" class="block group">
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:border-rose-500/60 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Rejected Submissions</span>
                    <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                        <i class="bi bi-x-circle-fill text-lg"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-2xl font-black text-rose-600">{{ number_format($rejectedCount) }}</div>
                    <div class="text-[11px] text-slate-500 mt-1">Locked under 7-day cooldown</div>
                </div>
            </div>
        </a>

        <!-- Total -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Submissions</span>
                <div class="w-9 h-9 rounded-xl bg-purple-50 text-[#20165c] flex items-center justify-center">
                    <i class="bi bi-shield-person text-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-[#121826]">{{ number_format($totalCount) }}</div>
                <div class="text-[11px] text-slate-500 mt-1">All historical KYC applications</div>
            </div>
        </div>

    </div>

    <!-- FILTER & SEARCH BAR -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm space-y-3">
        <form action="{{ route('admin.verifications.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            
            <!-- Status Tabs / Filter Pills -->
            <div class="md:col-span-7 flex flex-wrap items-center gap-2">
                <span class="text-xs font-bold text-slate-500 me-1">Status:</span>
                <a href="{{ route('admin.verifications.index') }}"
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all
                   {{ !request('status') ? 'bg-[#20165c] text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    All ({{ $totalCount }})
                </a>
                <a href="{{ route('admin.verifications.index', ['status' => 'pending']) }}"
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all
                   {{ request('status') === 'pending' ? 'bg-[#f47402] text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    Pending ({{ $pendingCount }})
                </a>
                <a href="{{ route('admin.verifications.index', ['status' => 'approved']) }}"
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all
                   {{ request('status') === 'approved' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    Approved ({{ $approvedCount }})
                </a>
                <a href="{{ route('admin.verifications.index', ['status' => 'rejected']) }}"
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all
                   {{ request('status') === 'rejected' ? 'bg-rose-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    Rejected ({{ $rejectedCount }})
                </a>
            </div>

            <!-- Search Field -->
            <div class="md:col-span-5 flex items-center gap-2">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="relative flex-1">
                    <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search name, country, or email..."
                           class="w-full pl-9 pr-3 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 text-[#121826] placeholder-slate-400 focus:bg-white focus:outline-none focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c]">
                </div>
                <button type="submit" class="px-4 py-2 rounded-xl bg-[#20165c] hover:bg-[#181045] text-white font-bold text-xs transition-colors shadow-sm">
                    Search
                </button>
                @if(request()->filled('search') || request()->filled('status'))
                    <a href="{{ route('admin.verifications.index') }}" class="p-2 rounded-xl bg-slate-100 text-slate-600 hover:text-rose-600 text-xs border border-slate-200" title="Reset Filters">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- VERIFICATIONS TABLE -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between flex-wrap gap-2">
            <div>
                <h3 class="font-bold text-sm text-[#121826]">Contributor Verification Applications</h3>
                <p class="text-xs text-slate-500">Government identity documents & face selfie reviews</p>
            </div>
            <span class="text-xs text-slate-500">
                Showing {{ $verifications->firstItem() ?? 0 }} - {{ $verifications->lastItem() ?? 0 }} of {{ $verifications->total() }} records
            </span>
        </div>

        @if($verifications->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3.5">Applicant</th>
                            <th class="px-4 py-3.5">NID / Document Number</th>
                            <th class="px-4 py-3.5">Portfolio</th>
                            <th class="px-4 py-3.5">KYC Document</th>
                            <th class="px-4 py-3.5">Submitted</th>
                            <th class="px-4 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Moderation Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($verifications as $item)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <!-- Applicant -->
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-purple-100 text-[#20165c] flex items-center justify-center font-bold text-xs flex-shrink-0">
                                            {{ strtoupper(substr($item->full_name ?? ($item->user ? $item->user->name : 'C'), 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-[#121826]">{{ $item->full_name }}</div>
                                            <div class="text-[11px] text-slate-500 font-mono">{{ $item->user ? $item->user->email : 'N/A' }}</div>
                                            @if($item->user && $item->user->phone)
                                                <div class="text-[10px] text-slate-400 font-mono">{{ $item->user->phone }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- NID / Document -->
                                <td class="px-4 py-3.5">
                                    <div class="font-mono font-bold text-[#121826]">
                                        {{ $item->id_number ?? $item->user?->nid_or_passport_number ?? 'N/A' }}
                                    </div>
                                    <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[10px] uppercase font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $item->document_type ?? 'NID' }}
                                    </span>
                                </td>

                                <!-- Portfolio Link -->
                                <td class="px-4 py-3.5">
                                    @php
                                        $portfolio = $item->portfolio_link ?? $item->user?->portfolio_link;
                                    @endphp
                                    @if($portfolio)
                                        <a href="{{ $portfolio }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-[11px] text-[#20165c] font-semibold hover:underline">
                                            <i class="bi bi-box-arrow-up-right"></i>
                                            <span>{{ Str::limit(str_replace(['https://', 'http://', 'www.'], '', $portfolio), 20) }}</span>
                                        </a>
                                    @else
                                        <span class="text-slate-400 text-[11px]">—</span>
                                    @endif
                                </td>

                                <!-- KYC Document -->
                                <td class="px-4 py-3.5">
                                    @php
                                        $idPath = $item->id_file_path ?? $item->id_document_path ?? $item->document_file ?? $item->user?->kyc_document_path;
                                    @endphp
                                    @if($idPath)
                                        <a href="{{ route('admin.kyc.document', ['type' => 'ids', 'filename' => basename($idPath)]) }}" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-purple-50 text-[#20165c] hover:bg-purple-100 text-[11px] font-semibold border border-purple-200/50 transition">
                                            <i class="bi bi-file-earmark-arrow-down-fill text-emerald-600"></i>
                                            <span>Download ID</span>
                                        </a>
                                    @else
                                        <span class="text-slate-400 text-[11px]">No file</span>
                                    @endif
                                </td>

                                <!-- Submitted Date -->
                                <td class="px-4 py-3.5 text-slate-500 whitespace-nowrap text-[11px] font-mono">
                                    {{ $item->submitted_at ? $item->submitted_at->format('M d, Y h:i A') : $item->created_at->format('M d, Y') }}
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    @if($item->status === 'approved')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i class="bi bi-check-circle-fill"></i> Approved
                                        </span>
                                    @elseif($item->status === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 animate-pulse">
                                            <i class="bi bi-clock-history"></i> Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <i class="bi bi-x-circle-fill"></i> Rejected
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Review Details Button -->
                                        <a href="{{ route('admin.verifications.show', $item->id) }}" class="px-2.5 py-1.5 rounded-lg bg-slate-100 text-[#20165c] hover:bg-purple-50 text-xs font-semibold transition border border-slate-200 flex items-center gap-1">
                                            <i class="bi bi-eye-fill"></i> Details
                                        </a>

                                        @if($item->status !== 'approved')
                                            <!-- Approve -->
                                            <form action="{{ route('admin.verifications.approve', $item->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Approve verification for {{ addslashes($item->full_name) }}?')" class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center gap-1 shadow-sm">
                                                    <i class="bi bi-check-lg"></i> Approve
                                                </button>
                                            </form>
                                        @endif

                                        @if($item->status !== 'rejected')
                                            <!-- Reject Trigger -->
                                            <button type="button" onclick="openRejectVerificationModal('{{ $item->id }}', '{{ addslashes($item->full_name) }}', '{{ addslashes($item->admin_note ?? '') }}')" class="px-2.5 py-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white text-xs font-bold transition border border-rose-200 flex items-center gap-1">
                                                <i class="bi bi-x-lg"></i> Reject
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($verifications->hasPages())
                <div class="p-4 border-t border-slate-100 flex items-center justify-between flex-wrap gap-2">
                    <div class="text-xs text-slate-500">
                        Showing {{ $verifications->firstItem() ?? 0 }} to {{ $verifications->lastItem() ?? 0 }} of {{ $verifications->total() }} records
                    </div>
                    <div>
                        {{ $verifications->links() }}
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-purple-50 text-[#20165c] flex items-center justify-center text-2xl">
                    <i class="bi bi-shield-slash"></i>
                </div>
                <h4 class="font-bold text-[#121826] text-base mb-1">No Verification Applications Found</h4>
                <p class="text-xs text-slate-500 max-w-md mx-auto mb-4">
                    There are currently no contributor verification submissions matching the selected status or search filter.
                </p>
                <a href="{{ route('admin.verifications.index') }}" class="inline-flex items-center gap-1 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold border border-slate-200">
                    Reset Filters
                </a>
            </div>
        @endif
    </div>

</div>

<!-- REJECT REASON MODAL -->
<div id="rejectVerificationModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 border border-slate-200 shadow-2xl">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <h3 class="font-bold text-sm text-[#121826] flex items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill text-rose-500"></i> Reject Contributor Verification
            </h3>
            <button type="button" onclick="closeRejectVerificationModal()" class="text-slate-400 hover:text-slate-600">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="rejectVerificationForm" method="POST" action="">
            @csrf
            <p class="text-xs text-slate-600 mb-3">
                Provide a clear rejection feedback for applicant <strong id="rejectVerificationName" class="text-[#121826]"></strong> (User will be locked under 7-day cooldown):
            </p>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Compliance Feedback</label>
                <textarea id="rejectVerificationNote" name="admin_note" rows="3" required placeholder="e.g., Document image is blurry, expired ID, or full name does not match government record." class="w-full px-3 py-2 rounded-xl text-xs bg-slate-50 border border-slate-200 text-[#121826] placeholder-slate-400 focus:bg-white focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2">
                <button type="button" onclick="closeRejectVerificationModal()" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-sm">
                    Reject Application & Lock
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectVerificationModal(id, name, note) {
        document.getElementById('rejectVerificationName').textContent = `"${name}"`;
        document.getElementById('rejectVerificationNote').value = note || '';
        document.getElementById('rejectVerificationForm').action = `/admin/verifications/${id}/reject`;
        document.getElementById('rejectVerificationModal').classList.remove('hidden');
    }

    function closeRejectVerificationModal() {
        document.getElementById('rejectVerificationModal').classList.add('hidden');
    }
</script>
@endsection