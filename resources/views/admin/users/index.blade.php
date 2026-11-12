@extends('layouts.admin')

@section('title', 'User & KYC Verification Hub - Noksha Admin HQ')
@section('page_title', 'User Management')
@section('page_heading', 'User & KYC Verification Hub')

@section('content')
<div class="space-y-6">

    <!-- STATS OVERVIEW CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 uppercase font-semibold">Total Registered</span>
            <div class="text-2xl font-black text-[#121826] mt-1">{{ number_format($totalUsers) }}</div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
            <span class="text-xs text-emerald-600 uppercase font-semibold">Creators / Sellers</span>
            <div class="text-2xl font-black text-emerald-600 mt-1">{{ number_format($totalSellers) }}</div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
            <span class="text-xs text-sky-600 uppercase font-semibold">Buyers</span>
            <div class="text-2xl font-black text-sky-600 mt-1">{{ number_format($totalBuyers) }}</div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
            <span class="text-xs text-rose-600 uppercase font-semibold">Suspended Accounts</span>
            <div class="text-2xl font-black text-rose-600 mt-1">{{ number_format($totalSuspended) }}</div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm {{ $totalPendingKyc > 0 ? 'border-amber-300 bg-amber-50/20' : '' }}">
            <span class="text-xs text-amber-600 uppercase font-semibold">Pending KYC Queue</span>
            <div class="text-2xl font-black text-amber-600 mt-1 flex items-center gap-2">
                <span>{{ number_format($totalPendingKyc) }}</span>
                @if($totalPendingKyc > 0)
                    <span class="text-[10px] bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full animate-pulse border border-amber-200 font-bold">Action Required</span>
                @endif
            </div>
        </div>
    </div>

    <!-- PENDING KYC APPLICATIONS QUEUE (Action Required by Admin) -->
    @if(isset($pendingKycUsers) && $pendingKycUsers->count() > 0)
        <div class="bg-amber-50/60 border border-amber-300 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between flex-wrap gap-2 mb-4">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-sm font-bold border border-amber-200">
                        <i class="bi bi-shield-exclamation"></i>
                    </span>
                    <div>
                        <h3 class="text-sm font-black text-[#121826]">Pending Contributor KYC Applications</h3>
                        <p class="text-xs text-slate-500">Review applicants requesting Contributor / Seller Studio access</p>
                    </div>
                </div>
                <span class="text-xs bg-amber-600 text-white font-bold px-3 py-1 rounded-full shadow-sm">
                    {{ $pendingKycUsers->count() }} Pending Review
                </span>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3">Applicant Name</th>
                                <th class="px-3 py-3">Contact</th>
                                <th class="px-3 py-3">Document / Details</th>
                                <th class="px-3 py-3">Submitted</th>
                                <th class="px-4 py-3 text-right">Moderation Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($pendingKycUsers as $pUser)
                                @php
                                    $verification = $pUser->verification;
                                    $idPath = $verification->id_file_path ?? $verification->id_document_path ?? $verification->document_file ?? null;
                                    $pDocUrl = $idPath ? route('admin.kyc.document', ['type' => 'ids', 'filename' => basename($idPath)]) : null;
                                    $pDocIsPdf = $idPath && str_ends_with(strtolower($idPath), '.pdf');
                                    $selfie = $verification->selfie_file_path ?? $verification->selfie_path ?? $verification->selfie_file ?? null;
                                    $pSelfieUrl = $selfie ? route('admin.kyc.document', ['type' => 'selfies', 'filename' => basename($selfie)]) : null;
                                    $pKycData = [
                                        'name' => $pUser->name,
                                        'full_name' => $pUser->verification?->full_name ?? $pUser->name,
                                        'email' => $pUser->email,
                                        'phone' => $pUser->phone ?? '—',
                                        'id_number' => $pUser->verification?->id_number ?? '—',
                                        'portfolio_link' => $pUser->verification?->portfolio_link ?? null,
                                        'id_type' => strtoupper($pUser->verification?->document_type ?? 'NID'),
                                        'country' => $pUser->verification?->country ?? 'Bangladesh',
                                        'submitted_at' => $pUser->verification?->submitted_at?->format('M d, Y h:i A') ?? $pUser->created_at->format('M d, Y h:i A'),
                                        'doc_url' => $pDocUrl,
                                        'doc_is_pdf' => $pDocIsPdf,
                                        'selfie_url' => $pSelfieUrl,
                                        'approve_url' => route('admin.users.kyc.approve', $pUser),
                                        'reject_url' => route('admin.users.kyc.reject', $pUser),
                                    ];
                                @endphp
                                <tr class="hover:bg-slate-50/60">
                                    <td class="px-4 py-3">
                                        <div class="font-bold text-[#121826]">{{ $pUser->name }}</div>
                                        <div class="text-[11px] text-slate-500 font-mono">{{ $pUser->email }}</div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="font-semibold text-slate-800">{{ $pUser->phone ?? '—' }}</div>
                                        <div class="text-[10px] text-slate-500">Country: {{ $pUser->verification?->country ?? 'Bangladesh' }}</div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="font-semibold text-[#121826] uppercase">{{ $pUser->verification?->document_type ?? 'NID/Passport' }}</div>
                                        @if($pUser->verification?->id_number)
                                            <div class="text-[11px] font-mono text-slate-500">ID: {{ $pUser->verification->id_number }}</div>
                                        @endif
                                        @if($pUser->verification?->portfolio_link)
                                            <a href="{{ $pUser->verification->portfolio_link }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] text-[#20165c] hover:underline font-semibold">
                                                <i class="bi bi-link-45deg"></i> Portfolio
                                            </a>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 text-slate-500 whitespace-nowrap text-[11px]">
                                        {{ $pUser->verification?->submitted_at?->diffForHumans() ?? $pUser->created_at->diffForHumans() }}
                                    </td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            <!-- Inspect KYC Documents Modal Button -->
                                            <button type="button"
                                                    onclick='openKycModal(@json($pKycData))'
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-[#20165c] hover:bg-[#181045] text-white font-bold text-xs transition shadow-sm">
                                                <i class="bi bi-eye-fill"></i> Inspect Documents
                                            </button>

                                            <!-- Direct Approve Button -->
                                            <form action="{{ route('admin.users.kyc.approve', $pUser) }}" method="POST" onsubmit="return confirm('Approve {{ $pUser->name }} as an official Contributor / Seller?');" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-sm" title="Quick Approve">
                                                    <i class="bi bi-check-lg"></i> Approve
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- FILTER & SEARCH BAR -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            
            <!-- Search -->
            <div class="sm:col-span-5 relative">
                <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search name, username, email, or phone..."
                       class="w-full pl-9 pr-3 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 text-[#121826] placeholder-slate-400 focus:bg-white focus:outline-none focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c]">
            </div>

            <!-- Role Filter -->
            <div class="sm:col-span-3">
                <select name="role" class="w-full px-3 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 text-[#121826] focus:bg-white focus:outline-none focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c]">
                    <option value="">All Roles</option>
                    <option value="seller" {{ request('role') === 'seller' ? 'selected' : '' }}>Sellers / Creators</option>
                    <option value="buyer" {{ in_array(request('role'), ['buyer', 'user']) ? 'selected' : '' }}>Buyers</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div class="sm:col-span-3">
                <select name="status" class="w-full px-3 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 text-[#121826] focus:bg-white focus:outline-none focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c]">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Accounts</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended Accounts</option>
                    <option value="pending_kyc" {{ request('status') === 'pending_kyc' ? 'selected' : '' }}>Pending KYC Submissions</option>
                </select>
            </div>

            <!-- Submit -->
            <div class="sm:col-span-1 flex items-center gap-1.5">
                <button type="submit" class="w-full py-2 rounded-xl bg-[#20165c] hover:bg-[#181045] text-white font-bold text-xs transition-colors shadow-sm">
                    Filter
                </button>
                @if(request()->has('search') || request()->has('role') || request()->has('status'))
                    <a href="{{ route('admin.users.index') }}" class="p-2 rounded-xl bg-slate-100 text-slate-600 hover:text-rose-500 text-xs border border-slate-200" title="Clear">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- USERS DATA TABLE -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
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
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            
                            <!-- Identity -->
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-purple-100 text-[#20165c] flex items-center justify-center font-bold text-xs flex-shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-[#121826] truncate flex items-center gap-1.5">
                                            <span>{{ $user->name }}</span>
                                            @if($user->is_verified)
                                                <i class="bi bi-patch-check-fill text-emerald-600" title="Verified Creator"></i>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-slate-500 font-mono">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Role -->
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ ($user->is_contributor || $user->role === 'seller') ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                    {{ ($user->is_contributor || $user->role === 'seller') ? 'Seller / Creator' : 'Buyer' }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @if($user->status === 'suspended')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <i class="bi bi-slash-circle"></i> Suspended
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="bi bi-check-circle-fill"></i> Active
                                    </span>
                                @endif
                            </td>

                            <!-- KYC Verification Status -->
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @if($user->verification)
                                    @if($user->verification->status === 'approved' || $user->is_verified)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i class="bi bi-patch-check-fill"></i> Verified
                                        </span>
                                    @elseif($user->verification->status === 'rejected')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <i class="bi bi-x-circle"></i> Declined
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 animate-pulse">
                                            <i class="bi bi-hourglass-split"></i> Pending Review
                                        </span>
                                    @endif
                                @else
                                    <span class="text-slate-400 text-[11px]">—</span>
                                @endif
                            </td>

                            <!-- Assets Uploaded -->
                            <td class="px-4 py-3.5 text-slate-700 font-mono">
                                {{ $user->resources ? $user->resources->count() : 0 }}
                            </td>

                            <!-- Joined Date -->
                            <td class="px-4 py-3.5 text-slate-500 whitespace-nowrap">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    
                                    <!-- KYC Review Trigger (if verification record exists) -->
                                    @if($user->verification)
                                        @php
                                            $verification = $user->verification;
                                            $idPath = $verification->id_file_path ?? $verification->id_document_path ?? $verification->document_file ?? null;
                                            $uDocUrl = $idPath ? route('admin.kyc.document', ['type' => 'ids', 'filename' => basename($idPath)]) : null;
                                            $uDocIsPdf = $idPath && str_ends_with(strtolower($idPath), '.pdf');
                                            $selfie = $verification->selfie_file_path ?? $verification->selfie_path ?? $verification->selfie_file ?? null;
                                            $uSelfieUrl = $selfie ? route('admin.kyc.document', ['type' => 'selfies', 'filename' => basename($selfie)]) : null;
                                            $uKycData = [
                                                'user_id' => $user->id,
                                                'name' => $user->name,
                                                'email' => $user->email,
                                                'phone' => $user->phone ?? '—',
                                                'full_name' => $user->verification->full_name,
                                                'id_number' => $user->verification->id_number ?? '—',
                                                'portfolio_link' => $user->verification->portfolio_link ?? null,
                                                'date_of_birth' => $user->verification->date_of_birth ? $user->verification->date_of_birth->format('M d, Y') : 'N/A',
                                                'country' => $user->verification->country ?? 'Bangladesh',
                                                'id_type' => strtoupper($user->verification->document_type ?? 'NID'),
                                                'doc_url' => $uDocUrl,
                                                'doc_is_pdf' => $uDocIsPdf,
                                                'selfie_url' => $uSelfieUrl,
                                                'submitted_at' => $user->verification->submitted_at ? $user->verification->submitted_at->format('M d, Y h:i A') : $user->verification->created_at->format('M d, Y h:i A'),
                                                'status' => $user->verification->status,
                                                'admin_note' => $user->verification->admin_note,
                                                'approve_url' => route('admin.users.kyc.approve', $user->id),
                                                'reject_url' => route('admin.users.kyc.reject', $user->id),
                                            ];
                                        @endphp
                                        <button type="button"
                                                onclick='openKycModal(@json($uKycData))'
                                                class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all flex items-center gap-1
                                                {{ $user->verification->status === 'pending' ? 'bg-[#f47402] text-white hover:bg-orange-600 shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200' }}">
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
                                                    {{ $user->status === 'suspended' ? 'bg-emerald-600 text-white hover:bg-emerald-700' : 'bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white border border-rose-200' }}">
                                                {{ $user->status === 'suspended' ? 'Activate' : 'Suspend' }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[11px] text-slate-400 italic">Current User</span>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-500">
                                <i class="bi bi-people text-4xl text-slate-400 block mb-2"></i>
                                No users found matching the given filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>

<!-- KYC DOCUMENT INSPECTION MODAL (Option 1B Synced) -->
<div id="kycInspectModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-3xl w-full p-6 border border-slate-200 shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <h3 class="font-bold text-sm text-[#121826] flex items-center gap-2">
                <i class="bi bi-shield-check text-[#20165c]"></i> KYC Document Verification
            </h3>
            <button type="button" onclick="closeKycModal()" class="text-slate-400 hover:text-slate-600">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-1 text-xs">
            
            <!-- Applicant Details Card: Legal Name, Phone/WhatsApp, Document Number, Portfolio Link, User Email, Submission Date -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                <div>
                    <span class="text-[10px] text-slate-500 block uppercase font-bold">Legal Name</span>
                    <strong id="kycFullName" class="text-[#121826] text-xs"></strong>
                </div>
                <div>
                    <span class="text-[10px] text-slate-500 block uppercase font-bold">User Email</span>
                    <span id="kycEmail" class="text-slate-700 font-mono text-xs block truncate"></span>
                </div>
                <div>
                    <span class="text-[10px] text-slate-500 block uppercase font-bold">Phone / WhatsApp</span>
                    <strong id="kycPhone" class="text-[#121826] text-xs"></strong>
                </div>
                <div>
                    <span class="text-[10px] text-slate-500 block uppercase font-bold">Document Number</span>
                    <strong id="kycIdNumber" class="text-[#20165c] font-mono text-xs"></strong>
                </div>
                <div>
                    <span class="text-[10px] text-slate-500 block uppercase font-bold">Portfolio Link</span>
                    <a id="kycPortfolioLink" href="#" target="_blank" class="text-[#20165c] hover:underline text-xs truncate block font-semibold">
                        View Portfolio &rarr;
                    </a>
                </div>
                <div>
                    <span class="text-[10px] text-slate-500 block uppercase font-bold">Submission Date</span>
                    <span id="kycSubmittedAt" class="text-slate-600 font-mono text-[11px]"></span>
                </div>
            </div>

            <!-- Documents Side-by-Side: Box 1 & Box 2 -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                
                <!-- Box 1: Government Identity Document Preview (JPG/PNG viewable lightbox or PDF download button) -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-bold text-slate-700">1. Government Identity Document</span>
                        <a id="kycDocDownloadLink" href="#" target="_blank" class="text-[11px] text-[#20165c] hover:underline flex items-center gap-1 font-semibold">
                            <i class="bi bi-box-arrow-up-right"></i> Open Original
                        </a>
                    </div>
                    <div id="kycDocContainer" class="h-60 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center p-2 relative">
                        <img id="kycDocImg" src="" class="w-full h-full object-contain cursor-zoom-in" alt="Government ID" onclick="window.open(this.src, '_blank')" onerror="this.onerror=null; this.src='/assets/images/placeholder-avatar.png';">
                        <div id="kycDocPdfBox" class="hidden text-center p-4">
                            <i class="bi bi-file-earmark-pdf-fill text-rose-500 text-5xl mb-2 d-block"></i>
                            <div class="font-bold text-slate-800 mb-2">PDF Document Attached</div>
                            <a id="kycDocPdfBtn" href="#" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-rose-600 text-white font-bold text-xs shadow-sm hover:bg-rose-700 transition">
                                <i class="bi bi-file-arrow-down-fill"></i> Download / View PDF Scan
                            </a>
                        </div>
                        <div id="kycDocEmpty" class="hidden text-slate-400 text-xs text-center">
                            <i class="bi bi-image text-3xl mb-1 d-block opacity-40"></i>
                            No document file uploaded
                        </div>
                    </div>
                </div>

                <!-- Box 2: Verification Face Selfie Preview (Properly renders uploaded selfie image) -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-bold text-slate-700">2. Verification Face Selfie</span>
                        <a id="kycSelfieDownloadLink" href="#" target="_blank" class="text-[11px] text-[#20165c] hover:underline flex items-center gap-1 font-semibold">
                            <i class="bi bi-box-arrow-up-right"></i> Open Original
                        </a>
                    </div>
                    <div id="kycSelfieContainer" class="h-60 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center p-2 relative">
                        <img id="kycSelfieImg" src="" class="w-full h-full object-contain cursor-zoom-in rounded-lg" alt="Verification Face Selfie" onclick="window.open(this.src, '_blank')" onerror="this.onerror=null; this.src='/assets/images/placeholder-avatar.png';">
                        <div id="kycSelfieEmpty" class="hidden text-slate-400 text-xs text-center">
                            <i class="bi bi-person-bounding-box text-3xl mb-1 d-block opacity-40"></i>
                            No selfie photo uploaded
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rejection Prompt Section (7-Day Cooldown Lock) -->
            <div id="kycRejectBox" class="hidden p-3.5 rounded-xl bg-rose-50 border border-rose-200">
                <form id="kycRejectForm" method="POST" action="">
                    @csrf
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block font-bold text-rose-900">
                            Reason for Rejection / Compliance Feedback (Enforces 7-Day Cooldown)
                        </label>
                        <span class="text-[10px] text-rose-700 font-bold bg-rose-100 px-2 py-0.5 rounded border border-rose-200">
                            7-Day Lock
                        </span>
                    </div>
                    <textarea name="admin_note" rows="2" required placeholder="State why documents were declined (e.g. Blurry photo, mismatched NID number, illegible selfie)..." class="w-full p-2.5 text-xs rounded-lg border border-rose-300 bg-white text-[#121826] placeholder-slate-400 focus:outline-none focus:border-rose-500"></textarea>
                    <div class="flex items-center justify-between mt-2.5">
                        <span class="text-[11px] text-rose-700">
                            User will be locked from resubmitting for exactly 7 days.
                        </span>
                        <div class="flex gap-2">
                            <button type="button" onclick="toggleRejectBox(false)" class="px-3 py-1.5 rounded-lg text-slate-600 font-semibold hover:bg-slate-100 transition">Cancel</button>
                            <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-rose-600 text-white font-bold hover:bg-rose-700 transition shadow-sm">Confirm Rejection & Lock 7 Days</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Action Footer -->
        <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between">
            <button type="button" onclick="closeKycModal()" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                Close
            </button>

            <div class="flex items-center gap-2">
                <!-- Decline Button -->
                <button type="button" onclick="toggleRejectBox(true)" class="px-4 py-2 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white font-bold text-xs transition-colors border border-rose-200">
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
        document.getElementById('kycEmail').textContent = data.email || '—';
        document.getElementById('kycPhone').textContent = data.phone || '—';
        document.getElementById('kycIdNumber').textContent = data.id_number || '—';
        document.getElementById('kycSubmittedAt').textContent = data.submitted_at;

        const pLink = document.getElementById('kycPortfolioLink');
        if (data.portfolio_link) {
            pLink.href = data.portfolio_link;
            pLink.textContent = data.portfolio_link;
            pLink.classList.remove('hidden');
        } else {
            pLink.href = '#';
            pLink.textContent = 'None Provided';
        }

        const docImg = document.getElementById('kycDocImg');
        const docPdfBox = document.getElementById('kycDocPdfBox');
        const docPdfBtn = document.getElementById('kycDocPdfBtn');
        const docEmpty = document.getElementById('kycDocEmpty');
        const docDownloadLink = document.getElementById('kycDocDownloadLink');

        if (data.doc_url) {
            docDownloadLink.href = data.doc_url;
            docDownloadLink.classList.remove('hidden');

            if (data.doc_is_pdf) {
                docImg.classList.add('hidden');
                docPdfBox.classList.remove('hidden');
                docPdfBtn.href = data.doc_url;
                docEmpty.classList.add('hidden');
            } else {
                docImg.src = data.doc_url;
                docImg.onerror = function() {
                    this.onerror = null;
                    this.src = '/assets/images/placeholder-avatar.png';
                };
                docImg.classList.remove('hidden');
                docPdfBox.classList.add('hidden');
                docEmpty.classList.add('hidden');
            }
        } else {
            docImg.classList.add('hidden');
            docPdfBox.classList.add('hidden');
            docEmpty.classList.remove('hidden');
            docDownloadLink.classList.add('hidden');
        }

        const selfieImg = document.getElementById('kycSelfieImg');
        const selfieEmpty = document.getElementById('kycSelfieEmpty');
        const selfieDownloadLink = document.getElementById('kycSelfieDownloadLink');

        if (data.selfie_url) {
            selfieImg.src = data.selfie_url;
            selfieImg.onerror = function() {
                this.onerror = null;
                this.src = '/assets/images/placeholder-avatar.png';
            };
            selfieImg.classList.remove('hidden');
            selfieEmpty.classList.add('hidden');
            selfieDownloadLink.href = data.selfie_url;
            selfieDownloadLink.classList.remove('hidden');
        } else {
            selfieImg.classList.add('hidden');
            selfieEmpty.classList.remove('hidden');
            selfieDownloadLink.classList.add('hidden');
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