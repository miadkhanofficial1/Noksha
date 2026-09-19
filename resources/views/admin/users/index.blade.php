@extends('layouts.admin')

@section('title', 'User & KYC Verification Hub - Noksha Admin HQ')
@section('page_title', 'User Management')
@section('page_heading', 'User & KYC Verification Hub')

@section('content')
<div class="space-y-6">

    <!-- STATS OVERVIEW CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm">
            <span class="text-xs text-gray-500 uppercase font-semibold">Total Registered</span>
            <div class="text-2xl font-black text-gray-900 dark:text-white mt-1">{{ number_format($totalUsers) }}</div>
        </div>

        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm">
            <span class="text-xs text-emerald-600 dark:text-emerald-400 uppercase font-semibold">Creators / Sellers</span>
            <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ number_format($totalSellers) }}</div>
        </div>

        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm">
            <span class="text-xs text-sky-600 dark:text-sky-400 uppercase font-semibold">Buyers</span>
            <div class="text-2xl font-black text-sky-600 dark:text-sky-400 mt-1">{{ number_format($totalBuyers) }}</div>
        </div>

        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm">
            <span class="text-xs text-rose-600 dark:text-rose-400 uppercase font-semibold">Suspended Accounts</span>
            <div class="text-2xl font-black text-rose-600 dark:text-rose-400 mt-1">{{ number_format($totalSuspended) }}</div>
        </div>

        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm {{ $totalPendingKyc > 0 ? 'border-amber-400 dark:border-amber-500/50 bg-amber-500/[0.03]' : '' }}">
            <span class="text-xs text-amber-600 dark:text-amber-400 uppercase font-semibold">Pending KYC Queue</span>
            <div class="text-2xl font-black text-amber-600 dark:text-amber-400 mt-1 flex items-center gap-2">
                <span>{{ number_format($totalPendingKyc) }}</span>
                @if($totalPendingKyc > 0)
                    <span class="text-[10px] bg-amber-500/20 text-amber-600 dark:text-amber-300 px-2 py-0.5 rounded-full animate-pulse">Action Required</span>
                @endif
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH BAR -->
    <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            
            <!-- Search -->
            <div class="sm:col-span-5 relative">
                <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search name, username, email, or phone..."
                       class="w-full pl-9 pr-3 py-2 text-xs rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:outline-none focus:border-brand-500">
            </div>

            <!-- Role Filter -->
            <div class="sm:col-span-3">
                <select name="role" class="w-full px-3 py-2 text-xs rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:outline-none focus:border-brand-500">
                    <option value="">All Roles</option>
                    <option value="seller" {{ request('role') === 'seller' ? 'selected' : '' }}>Sellers / Creators</option>
                    <option value="buyer" {{ in_array(request('role'), ['buyer', 'user']) ? 'selected' : '' }}>Buyers</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrators</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div class="sm:col-span-3">
                <select name="status" class="w-full px-3 py-2 text-xs rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:outline-none focus:border-brand-500">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Accounts</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended Accounts</option>
                    <option value="pending_kyc" {{ request('status') === 'pending_kyc' ? 'selected' : '' }}>Pending KYC Submissions</option>
                </select>
            </div>

            <!-- Submit -->
            <div class="sm:col-span-1 flex items-center gap-1.5">
                <button type="submit" class="w-full py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs transition-colors shadow-sm">
                    Filter
                </button>
                @if(request()->has('search') || request()->has('role') || request()->has('status'))
                    <a href="{{ route('admin.users.index') }}" class="p-2 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:text-rose-500 text-xs" title="Clear">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- USERS DATA TABLE -->
    <div class="bg-white dark:bg-[#0F1623] rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 dark:bg-gray-800/40 text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="px-5 py-3.5">User Identity</th>
                        <th class="px-4 py-3.5">System Role</th>
                        <th class="px-4 py-3.5">Account Status</th>
                        <th class="px-4 py-3.5">KYC Verification</th>
                        <th class="px-4 py-3.5">Assets</th>
                        <th class="px-4 py-3.5">Joined</th>
                        <th class="px-5 py-3.5 text-right">Executive Controls</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                            
                            <!-- Identity -->
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-brand-600 to-indigo-600 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-gray-900 dark:text-white truncate flex items-center gap-1.5">
                                            <span>{{ $user->name }}</span>
                                            @if($user->is_verified)
                                                <i class="bi bi-patch-check-fill text-emerald-500" title="Verified Creator"></i>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-gray-400 font-mono">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Role -->
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ $user->role === 'admin' ? 'bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20' : ($user->role === 'seller' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300') }}">
                                    {{ $user->role }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @if($user->status === 'suspended')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                        <i class="bi bi-slash-circle"></i> Suspended
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                        <i class="bi bi-check-circle-fill"></i> Active
                                    </span>
                                @endif
                            </td>

                            <!-- KYC Verification Status -->
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @if($user->verification)
                                    @if($user->verification->status === 'approved' || $user->is_verified)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                            <i class="bi bi-patch-check-fill"></i> Verified
                                        </span>
                                    @elseif($user->verification->status === 'rejected')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                            <i class="bi bi-x-circle"></i> Declined
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 animate-pulse">
                                            <i class="bi bi-hourglass-split"></i> Pending Review
                                        </span>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-[11px]">—</span>
                                @endif
                            </td>

                            <!-- Assets Uploaded -->
                            <td class="px-4 py-3.5 text-gray-600 dark:text-gray-300 font-mono">
                                {{ $user->resources ? $user->resources->count() : 0 }}
                            </td>

                            <!-- Joined Date -->
                            <td class="px-4 py-3.5 text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    
                                    <!-- KYC Review Trigger (if verification record exists) -->
                                    @if($user->verification)
                                        <button type="button"
                                                onclick="openKycModal({{ json_encode([
                                                    'user_id' => $user->id,
                                                    'name' => $user->name,
                                                    'email' => $user->email,
                                                    'full_name' => $user->verification->full_name,
                                                    'date_of_birth' => $user->verification->date_of_birth ? $user->verification->date_of_birth->format('M d, Y') : 'N/A',
                                                    'country' => $user->verification->country ?? 'Bangladesh',
                                                    'id_type' => strtoupper($user->verification->document_type ?? 'NID'),
                                                    'doc_url' => $user->verification->document_file ? asset('storage/' . $user->verification->document_file) : null,
                                                    'selfie_url' => $user->verification->selfie_file ? asset('storage/' . $user->verification->selfie_file) : null,
                                                    'submitted_at' => $user->verification->created_at->format('M d, Y h:i A'),
                                                    'status' => $user->verification->status,
                                                    'admin_note' => $user->verification->admin_note,
                                                    'approve_url' => route('admin.users.kyc.approve', $user->id),
                                                    'reject_url' => route('admin.users.kyc.reject', $user->id),
                                                ]) }})"
                                                class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all flex items-center gap-1
                                                {{ $user->verification->status === 'pending' ? 'bg-amber-500 text-white hover:bg-amber-600 shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}">
                                            <i class="bi bi-person-badge"></i>
                                            <span>{{ $user->verification->status === 'pending' ? 'Review KYC' : 'View KYC' }}</span>
                                        </button>
                                    @endif

                                    <!-- Account Suspension Toggle -->
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.toggleStatus', $user->id) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    onclick="return confirm('Toggle account status for {{ addslashes($user->name) }}?')"
                                                    class="px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors
                                                    {{ $user->status === 'suspended' ? 'bg-emerald-600 text-white hover:bg-emerald-700' : 'bg-rose-500/10 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white border border-rose-500/30' }}">
                                                {{ $user->status === 'suspended' ? 'Activate' : 'Suspend' }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[11px] text-gray-400 italic">Current User</span>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-gray-500 dark:text-gray-400">
                                <i class="bi bi-people text-4xl text-gray-400 block mb-2"></i>
                                No users found matching the given filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>

<!-- KYC DOCUMENT INSPECTION MODAL -->
<div id="kycInspectModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-[#0F1623] rounded-2xl max-w-2xl w-full p-6 border border-gray-200 dark:border-gray-800 shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-800 mb-4">
            <h3 class="font-bold text-sm text-gray-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-shield-check text-sky-500"></i> KYC Document Verification
            </h3>
            <button type="button" onclick="closeKycModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-white">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-1 text-xs">
            
            <!-- Applicant details card -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 p-3.5 rounded-xl bg-gray-50 dark:bg-gray-900/60 border border-gray-200 dark:border-gray-800">
                <div>
                    <span class="text-[10px] text-gray-400 block uppercase font-bold">Legal Name</span>
                    <strong id="kycFullName" class="text-gray-900 dark:text-white text-xs"></strong>
                </div>
                <div>
                    <span class="text-[10px] text-gray-400 block uppercase font-bold">Document Type</span>
                    <strong id="kycIdType" class="text-brand-500 text-xs"></strong>
                </div>
                <div>
                    <span class="text-[10px] text-gray-400 block uppercase font-bold">Country</span>
                    <strong id="kycCountry" class="text-gray-900 dark:text-white text-xs"></strong>
                </div>
                <div>
                    <span class="text-[10px] text-gray-400 block uppercase font-bold">Submitted At</span>
                    <span id="kycSubmittedAt" class="text-gray-600 dark:text-gray-300 font-mono text-[11px]"></span>
                </div>
            </div>

            <!-- Documents side-by-side -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Government ID Document -->
                <div>
                    <span class="font-bold text-gray-700 dark:text-gray-300 block mb-1">1. Government Identity Document</span>
                    <div id="kycDocContainer" class="h-52 rounded-xl bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden flex items-center justify-center">
                        <img id="kycDocImg" src="" class="w-full h-full object-contain" alt="Government ID">
                    </div>
                </div>

                <!-- Face Selfie Photo -->
                <div>
                    <span class="font-bold text-gray-700 dark:text-gray-300 block mb-1">2. Verification Face Selfie</span>
                    <div id="kycSelfieContainer" class="h-52 rounded-xl bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden flex items-center justify-center">
                        <img id="kycSelfieImg" src="" class="w-full h-full object-contain" alt="Face Selfie">
                    </div>
                </div>
            </div>

            <!-- Rejection Prompt Section (collapsible) -->
            <div id="kycRejectBox" class="hidden p-3 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800">
                <form id="kycRejectForm" method="POST" action="">
                    @csrf
                    <label class="block font-bold text-rose-800 dark:text-rose-300 mb-1">Reason for Rejection / Compliance Feedback</label>
                    <textarea name="admin_note" rows="2" required placeholder="State why documents were declined (e.g. Blurry photo, expired NID)..." class="w-full p-2 text-xs rounded-lg border border-rose-300 dark:border-rose-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white"></textarea>
                    <div class="flex justify-end gap-2 mt-2">
                        <button type="button" onclick="toggleRejectBox(false)" class="px-3 py-1 rounded-lg text-gray-600 dark:text-gray-400">Cancel</button>
                        <button type="submit" class="px-3 py-1 rounded-lg bg-rose-600 text-white font-bold">Confirm Decline</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Action Footer -->
        <div class="mt-5 pt-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
            <button type="button" onclick="closeKycModal()" class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800">
                Close
            </button>

            <div class="flex items-center gap-2">
                <!-- Decline Button -->
                <button type="button" onclick="toggleRejectBox(true)" class="px-4 py-2 rounded-xl bg-rose-500/10 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white font-bold text-xs transition-colors border border-rose-500/30">
                    <i class="bi bi-x-circle me-1"></i> Reject KYC
                </button>

                <!-- Approve Button -->
                <form id="kycApproveForm" method="POST" action="">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition-colors">
                        <i class="bi bi-check-circle-fill me-1"></i> Approve KYC & Unlock Badges
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openKycModal(data) {
        document.getElementById('kycFullName').textContent = data.full_name || data.name;
        document.getElementById('kycIdType').textContent = data.id_type;
        document.getElementById('kycCountry').textContent = data.country;
        document.getElementById('kycSubmittedAt').textContent = data.submitted_at;

        const docImg = document.getElementById('kycDocImg');
        if (data.doc_url) {
            docImg.src = data.doc_url;
            docImg.classList.remove('hidden');
        } else {
            docImg.classList.add('hidden');
        }

        const selfieImg = document.getElementById('kycSelfieImg');
        if (data.selfie_url) {
            selfieImg.src = data.selfie_url;
            selfieImg.classList.remove('hidden');
        } else {
            selfieImg.classList.add('hidden');
        }

        document.getElementById('kycApproveForm').action = data.approve_url;
        document.getElementById('kycRejectForm').action = data.reject_url;
        toggleRejectBox(false);

        document.getElementById('kycInspectModal').classList.remove('hidden');
    }

    function closeKycModal() {
        document.getElementById('kycInspectModal').classList.add('hidden');
    }

    function toggleRejectBox(show) {
        const box = document.getElementById('kycRejectBox');
        if (show) {
            box.classList.remove('hidden');
        } else {
            box.classList.add('hidden');
        }
    }
</script>
@endsection
