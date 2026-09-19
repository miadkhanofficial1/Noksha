@extends('layouts.admin')

@section('title', 'Resource Moderation Center - Noksha Admin HQ')
@section('page_title', 'Moderation')
@section('page_heading', 'Resource Moderation Center')

@section('content')
<div class="space-y-6">

    <!-- TOP FILTER & TABS BAR -->
    <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm space-y-4">
        
        <!-- Status Tabs -->
        <div class="flex items-center justify-between flex-wrap gap-3 pb-3 border-b border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-2 overflow-x-auto">
                <!-- All -->
                <a href="{{ route('admin.resources.index', array_merge(request()->query(), ['status' => 'all'])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2
                   {{ $activeTab === 'all' ? 'bg-brand-600 text-white shadow-md shadow-brand-500/20' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    <span>All Resources</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $activeTab === 'all' ? 'bg-white/20 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                        {{ $totalCount }}
                    </span>
                </a>

                <!-- Pending -->
                <a href="{{ route('admin.resources.index', array_merge(request()->query(), ['status' => 'pending'])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2
                   {{ $activeTab === 'pending' ? 'bg-amber-500 text-white shadow-md shadow-amber-500/20' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    <span>Pending Moderation</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $activeTab === 'pending' ? 'bg-white/20 text-white' : 'bg-amber-500/20 text-amber-600 dark:text-amber-400 font-bold' }}">
                        {{ $pendingCount }}
                    </span>
                </a>

                <!-- Approved -->
                <a href="{{ route('admin.resources.index', array_merge(request()->query(), ['status' => 'approved'])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2
                   {{ $activeTab === 'approved' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-500/20' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    <span>Approved & Live</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $activeTab === 'approved' ? 'bg-white/20 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                        {{ $approvedCount }}
                    </span>
                </a>

                <!-- Rejected -->
                <a href="{{ route('admin.resources.index', array_merge(request()->query(), ['status' => 'rejected'])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2
                   {{ $activeTab === 'rejected' ? 'bg-rose-600 text-white shadow-md shadow-rose-500/20' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    <span>Rejected</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $activeTab === 'rejected' ? 'bg-white/20 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                        {{ $rejectedCount }}
                    </span>
                </a>
            </div>

            <!-- Upload count stats -->
            <div class="text-xs text-gray-500 dark:text-gray-400">
                Displaying <span class="font-bold text-gray-900 dark:text-white">{{ $resources->total() }}</span> records
            </div>
        </div>

        <!-- Search & Filter Controls Form -->
        <form method="GET" action="{{ route('admin.resources.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <input type="hidden" name="status" value="{{ $activeTab }}">
            
            <!-- Search Keyword -->
            <div class="sm:col-span-6 relative">
                <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search title, creator name, email, or category..."
                       class="w-full pl-9 pr-3 py-2 text-xs rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:outline-none focus:border-brand-500">
            </div>

            <!-- Category Filter -->
            <div class="sm:col-span-4">
                <select name="category_id" class="w-full px-3 py-2 text-xs rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:outline-none focus:border-brand-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Buttons -->
            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" class="w-full py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs transition-colors shadow-sm">
                    Filter
                </button>
                @if(request()->has('search') || request()->has('category_id'))
                    <a href="{{ route('admin.resources.index', ['status' => $activeTab]) }}" class="p-2 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:text-rose-500 text-xs" title="Reset Filters">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- MAIN RESOURCES DATA TABLE -->
    <div class="bg-white dark:bg-[#0F1623] rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 dark:bg-gray-800/40 text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="px-4 py-3.5">Thumbnail</th>
                        <th class="px-4 py-3.5">Resource Details</th>
                        <th class="px-4 py-3.5">Creator / Seller</th>
                        <th class="px-4 py-3.5">Category</th>
                        <th class="px-4 py-3.5">Pricing</th>
                        <th class="px-4 py-3.5">Uploaded</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5 text-right">Moderation Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($resources as $res)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                            
                            <!-- Thumbnail -->
                            <td class="px-4 py-3.5 w-16">
                                <div class="w-14 h-14 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex-shrink-0 cursor-pointer" onclick="previewImageModal('{{ asset('storage/' . $res->preview_image) }}', '{{ addslashes($res->title) }}')">
                                    @if($res->preview_image)
                                        <img src="{{ asset('storage/' . $res->preview_image) }}" class="w-full h-full object-cover hover:scale-110 transition-transform" alt="{{ $res->title }}">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <i class="bi bi-file-earmark-image text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Title & Slug -->
                            <td class="px-4 py-3.5 max-w-xs">
                                <a href="{{ route('resource.show', $res->slug) }}" target="_blank" class="font-bold text-gray-900 dark:text-white hover:text-brand-500 transition-colors block truncate" title="{{ $res->title }}">
                                    {{ $res->title }}
                                </a>
                                <div class="text-[11px] text-gray-400 font-mono flex items-center gap-1.5 mt-0.5">
                                    <span class="uppercase font-semibold text-brand-500">{{ $res->file_type }}</span>
                                    <span>•</span>
                                    <span>{{ number_format($res->downloads) }} downloads</span>
                                    <span>•</span>
                                    <span>{{ number_format($res->views) }} views</span>
                                </div>
                                @if($res->rejection_reason && $res->status === 'rejected')
                                    <div class="mt-1 p-1.5 rounded bg-rose-500/10 text-rose-600 dark:text-rose-400 text-[10px]">
                                        <strong>Reason:</strong> {{ $res->rejection_reason }}
                                    </div>
                                @endif
                            </td>

                            <!-- Creator -->
                            <td class="px-4 py-3.5">
                                <div class="font-semibold text-gray-900 dark:text-gray-200">{{ $res->owner->name ?? 'Unknown' }}</div>
                                <div class="text-[11px] text-gray-400 font-mono">{{ $res->owner->email ?? 'No email' }}</div>
                            </td>

                            <!-- Category -->
                            <td class="px-4 py-3.5">
                                <span class="px-2 py-0.5 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-medium">
                                    {{ $res->category->name ?? 'Uncategorized' }}
                                </span>
                            </td>

                            <!-- Price -->
                            <td class="px-4 py-3.5 font-semibold">
                                @if($res->is_paid)
                                    <span class="text-teal-600 dark:text-teal-400 font-mono font-bold">
                                        ৳{{ number_format($res->price, 2) }}
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400">
                                        Free
                                    </span>
                                @endif
                            </td>

                            <!-- Upload Date -->
                            <td class="px-4 py-3.5 text-gray-500 dark:text-gray-400 text-[11px] whitespace-nowrap">
                                <div>{{ $res->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-gray-400">{{ $res->created_at->diffForHumans() }}</div>
                            </td>

                            <!-- Status Badge -->
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @if($res->status === 'approved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                        <i class="bi bi-check-circle-fill"></i> Approved
                                    </span>
                                @elseif($res->status === 'rejected')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                        <i class="bi bi-x-circle-fill"></i> Rejected
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 animate-pulse">
                                        <i class="bi bi-hourglass-split"></i> Pending
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    
                                    <!-- Preview Modal Trigger -->
                                    <button type="button"
                                            onclick="openQuickPreview({{ json_encode([
                                                'title' => $res->title,
                                                'slug' => $res->slug,
                                                'preview_image' => asset('storage/' . $res->preview_image),
                                                'description' => $res->description,
                                                'creator' => $res->owner->name ?? 'Unknown',
                                                'category' => $res->category->name ?? 'None',
                                                'file_type' => strtoupper($res->file_type),
                                                'price' => $res->is_paid ? '৳' . number_format($res->price, 2) : 'Free',
                                                'tags' => $res->tags ?? [],
                                                'demo_link' => $res->demo_link,
                                                'download_url' => route('admin.resources.download', $res->id),
                                                'approve_url' => route('admin.resources.approve', $res->id),
                                                'id' => $res->id,
                                                'status' => $res->status
                                            ]) }})"
                                            title="Inspect Asset Details"
                                            class="p-1.5 rounded-lg text-gray-500 hover:text-brand-600 hover:bg-brand-50 dark:hover:bg-brand-500/10 dark:text-gray-400 transition-colors">
                                        <i class="bi bi-eye-fill text-sm"></i>
                                    </button>

                                    <!-- Admin Free Download (Bypasses paywall) -->
                                    <a href="{{ route('admin.resources.download', $res->id) }}"
                                       title="Super Admin Free Download (Bypasses paywall)"
                                       class="p-1.5 rounded-lg text-teal-600 hover:bg-teal-50 dark:text-teal-400 dark:hover:bg-teal-500/10 transition-colors">
                                        <i class="bi bi-download text-sm"></i>
                                    </a>

                                    @if($res->status !== 'approved')
                                        <!-- Approve -->
                                        <form method="POST" action="{{ route('admin.resources.approve', $res->id) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    onclick="return confirm('Approve and publish \"{{ addslashes($res->title) }}\" to the live marketplace?')"
                                                    title="Approve & Publish"
                                                    class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-colors flex items-center gap-1 shadow-sm">
                                                <i class="bi bi-check-lg"></i>
                                                <span class="hidden sm:inline">Approve</span>
                                            </button>
                                        </form>
                                    @endif

                                    @if($res->status !== 'rejected')
                                        <!-- Reject Trigger -->
                                        <button type="button"
                                                onclick="openRejectModal('{{ $res->id }}', '{{ addslashes($res->title) }}')"
                                                title="Reject Asset"
                                                class="px-2.5 py-1.5 rounded-lg bg-rose-500/10 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white font-bold text-xs transition-colors border border-rose-500/30 flex items-center gap-1">
                                            <i class="bi bi-x-lg"></i>
                                            <span class="hidden sm:inline">Reject</span>
                                        </button>
                                    @endif

                                    <!-- Delete Permanently -->
                                    <form method="POST" action="{{ route('admin.resources.destroy', $res->id) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('⚠️ PERMANENT ACTION: Are you sure you want to permanently delete \"{{ addslashes($res->title) }}\" and wipe its files from storage?')"
                                                title="Delete Permanently"
                                                class="p-1.5 rounded-lg text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
                                            <i class="bi bi-trash-fill text-sm"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                <i class="bi bi-folder-x text-4xl text-gray-400 block mb-2"></i>
                                <span class="font-semibold text-sm">No resources match the selected criteria.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($resources->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                {{ $resources->links() }}
            </div>
        @endif
    </div>

</div>

<!-- REJECTION MODAL -->
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
                Specify the rationale for declining <strong id="rejectResourceTitle" class="text-gray-900 dark:text-white"></strong>:
            </p>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Feedback for Creator</label>
                <textarea name="rejection_reason" rows="3" required placeholder="e.g., Preview cover quality does not meet our minimum resolution guidelines, or missing design artboards." class="w-full px-3 py-2 rounded-xl text-xs bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:outline-none focus:border-rose-500"></textarea>
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

<!-- QUICK ASSET INSPECTION MODAL -->
<div id="quickPreviewModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-[#0F1623] rounded-2xl max-w-2xl w-full p-6 border border-gray-200 dark:border-gray-800 shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-800 mb-4">
            <h3 class="font-bold text-sm text-gray-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-shield-check text-brand-500"></i> Asset Detailed Inspection
            </h3>
            <button type="button" onclick="closeQuickPreview()" class="text-gray-400 hover:text-gray-600 dark:hover:text-white">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-1">
            <div class="rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800 h-56 flex items-center justify-center border border-gray-200 dark:border-gray-700">
                <img id="modalPreviewImg" src="" class="w-full h-full object-contain" alt="">
            </div>

            <div>
                <h4 id="modalTitle" class="text-base font-extrabold text-gray-900 dark:text-white mb-1"></h4>
                <div class="flex flex-wrap gap-2 text-xs text-gray-500 dark:text-gray-400">
                    <span>Creator: <strong id="modalCreator" class="text-gray-800 dark:text-gray-200"></strong></span>
                    <span>•</span>
                    <span>Category: <strong id="modalCategory" class="text-gray-800 dark:text-gray-200"></strong></span>
                    <span>•</span>
                    <span>Format: <strong id="modalFormat" class="text-brand-500 uppercase font-mono"></strong></span>
                    <span>•</span>
                    <span>Pricing: <strong id="modalPrice" class="text-teal-500 font-mono"></strong></span>
                </div>
            </div>

            <div>
                <h5 class="text-xs font-bold uppercase text-gray-400 mb-1">Description</h5>
                <p id="modalDesc" class="text-xs text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line"></p>
            </div>

            <div id="modalTagsContainer" class="flex flex-wrap gap-1.5 pt-2">
                <!-- tags injected here -->
            </div>
        </div>

        <div class="mt-5 pt-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
            <a id="modalDownloadBtn" href="" class="px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold flex items-center gap-1.5 transition-colors">
                <i class="bi bi-download"></i> Admin Free Download
            </a>

            <div class="flex items-center gap-2">
                <button type="button" onclick="closeQuickPreview()" class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800">
                    Close
                </button>
            </div>
        </div>
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

    function openQuickPreview(data) {
        document.getElementById('modalPreviewImg').src = data.preview_image;
        document.getElementById('modalTitle').textContent = data.title;
        document.getElementById('modalCreator').textContent = data.creator;
        document.getElementById('modalCategory').textContent = data.category;
        document.getElementById('modalFormat').textContent = data.file_type;
        document.getElementById('modalPrice').textContent = data.price;
        document.getElementById('modalDesc').textContent = data.description;
        document.getElementById('modalDownloadBtn').href = data.download_url;

        const tagsContainer = document.getElementById('modalTagsContainer');
        tagsContainer.innerHTML = '';
        if (data.tags && Array.isArray(data.tags)) {
            data.tags.forEach(t => {
                const badge = document.createElement('span');
                badge.className = 'px-2 py-0.5 rounded-full text-[10px] bg-brand-50 dark:bg-brand-900/30 text-brand-600 dark:text-brand-300 font-mono';
                badge.textContent = '#' + t;
                tagsContainer.appendChild(badge);
            });
        }

        document.getElementById('quickPreviewModal').classList.remove('hidden');
    }

    function closeQuickPreview() {
        document.getElementById('quickPreviewModal').classList.add('hidden');
    }

    function previewImageModal(url, title) {
        openQuickPreview({
            title: title,
            preview_image: url,
            description: 'Image cover preview',
            creator: '',
            category: '',
            file_type: 'IMAGE',
            price: '',
            tags: [],
            download_url: '#'
        });
    }
</script>
@endsection
