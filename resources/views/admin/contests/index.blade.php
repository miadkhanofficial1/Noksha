@extends('layouts.admin')

@section('title', 'Design Contest Management - Noksha Admin HQ')
@section('page_title', 'Contest Control')
@section('page_heading', 'Design Contest Management Hub')

@section('content')
<div class="space-y-6">

    <!-- STATS OVERVIEW CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm">
            <span class="text-xs text-gray-500 uppercase font-semibold">Total Tournaments</span>
            <div class="text-2xl font-black text-gray-900 dark:text-white mt-1">{{ number_format($totalContests) }}</div>
        </div>

        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm">
            <span class="text-xs text-amber-500 uppercase font-semibold">Active & Live</span>
            <div class="text-2xl font-black text-amber-500 mt-1">{{ number_format($activeContests) }}</div>
        </div>

        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm">
            <span class="text-xs text-emerald-500 uppercase font-semibold">Completed</span>
            <div class="text-2xl font-black text-emerald-500 mt-1">{{ number_format($completedContests) }}</div>
        </div>

        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm">
            <span class="text-xs text-purple-500 uppercase font-semibold">Total Entries</span>
            <div class="text-2xl font-black text-purple-500 mt-1">{{ number_format($totalSubmissions) }}</div>
        </div>

        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm">
            <span class="text-xs text-teal-500 uppercase font-semibold">Prize Pool Distributed</span>
            <div class="text-2xl font-black text-teal-500 mt-1 font-mono">৳{{ number_format($totalPrizePool, 2) }}</div>
        </div>
    </div>

    <!-- TABS & ACTIONS BAR -->
    <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm flex items-center justify-between flex-wrap gap-3">
        <!-- Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto">
            <a href="{{ route('admin.contests.index', ['status' => 'all']) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $activeTab === 'all' ? 'bg-brand-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                All Contests
            </a>
            <a href="{{ route('admin.contests.index', ['status' => 'active']) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $activeTab === 'active' ? 'bg-amber-500 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                Active
            </a>
            <a href="{{ route('admin.contests.index', ['status' => 'upcoming']) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $activeTab === 'upcoming' ? 'bg-sky-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                Upcoming
            </a>
            <a href="{{ route('admin.contests.index', ['status' => 'completed']) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $activeTab === 'completed' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                Completed
            </a>
            <a href="{{ route('admin.contests.index', ['status' => 'cancelled']) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $activeTab === 'cancelled' ? 'bg-rose-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                Cancelled
            </a>
        </div>

        <!-- Launch Contest Trigger -->
        <button type="button" onclick="openCreateContestModal()" class="px-4 py-2 rounded-xl bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-700 hover:to-indigo-700 text-white text-xs font-bold shadow-md shadow-brand-500/20 transition-all flex items-center gap-1.5">
            <i class="bi bi-plus-circle-fill"></i> Launch New Contest
        </button>
    </div>

    <!-- CONTESTS TABLE -->
    <div class="bg-white dark:bg-[#0F1623] rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 dark:bg-gray-800/40 text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="px-5 py-3.5">Contest Tournament</th>
                        <th class="px-4 py-3.5">Category</th>
                        <th class="px-4 py-3.5">Prize Bounty</th>
                        <th class="px-4 py-3.5">Duration</th>
                        <th class="px-4 py-3.5">Entries</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5">Winner</th>
                        <th class="px-5 py-3.5 text-right">Executive Controls</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($contests as $contest)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                            
                            <!-- Title -->
                            <td class="px-5 py-3.5 max-w-xs">
                                <a href="{{ route('contests.show', $contest->slug) }}" target="_blank" class="font-bold text-gray-900 dark:text-white hover:text-brand-500 block truncate" title="{{ $contest->title }}">
                                    {{ $contest->title }}
                                </a>
                                <div class="text-[11px] text-gray-400 font-mono">slug: {{ $contest->slug }}</div>
                            </td>

                            <!-- Category -->
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-medium">
                                    {{ $contest->category->name ?? 'General' }}
                                </span>
                            </td>

                            <!-- Prize -->
                            <td class="px-4 py-3.5 whitespace-nowrap font-mono font-bold text-teal-600 dark:text-teal-400">
                                ৳{{ number_format($contest->prize_amount, 2) }}
                            </td>

                            <!-- Duration -->
                            <td class="px-4 py-3.5 text-gray-500 dark:text-gray-400 whitespace-nowrap text-[11px]">
                                <div>{{ $contest->start_date ? \Carbon\Carbon::parse($contest->start_date)->format('M d') : 'N/A' }} → {{ $contest->end_date ? \Carbon\Carbon::parse($contest->end_date)->format('M d, Y') : 'N/A' }}</div>
                            </td>

                            <!-- Entries Count -->
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <a href="{{ route('admin.contests.submissions', $contest->id) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-300 font-bold hover:underline">
                                    <i class="bi bi-images"></i>
                                    <span>{{ $contest->submissions ? $contest->submissions->count() : 0 }} designs</span>
                                </a>
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @if($contest->status === 'active')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                        <i class="bi bi-play-fill"></i> Active
                                    </span>
                                @elseif($contest->status === 'completed')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                        <i class="bi bi-trophy-fill"></i> Completed
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                        <i class="bi bi-x-circle"></i> Cancelled
                                    </span>
                                @endif
                            </td>

                            <!-- Winner -->
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @if($contest->winner)
                                    <span class="font-bold text-amber-600 dark:text-amber-400 flex items-center gap-1">
                                        <i class="bi bi-award-fill"></i> {{ $contest->winner->name }}
                                    </span>
                                @else
                                    <span class="text-gray-400 italic">None selected</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    
                                    <!-- View & Declare Winner link -->
                                    <a href="{{ route('admin.contests.submissions', $contest->id) }}"
                                       title="Inspect All Submissions & Select Winner"
                                       class="px-2.5 py-1 rounded-lg bg-brand-50 hover:bg-brand-100 dark:bg-brand-900/20 dark:hover:bg-brand-900/40 text-brand-600 dark:text-brand-300 font-bold text-xs transition-colors flex items-center gap-1">
                                        <i class="bi bi-trophy"></i>
                                        <span>Entries</span>
                                    </a>

                                    <!-- Force Edit Trigger -->
                                    <button type="button"
                                            onclick="openEditContestModal({{ json_encode([
                                                'id' => $contest->id,
                                                'title' => $contest->title,
                                                'description' => $contest->description,
                                                'prize_amount' => $contest->prize_amount,
                                                'category_id' => $contest->category_id,
                                                'start_date' => $contest->start_date ? \Carbon\Carbon::parse($contest->start_date)->format('Y-m-d\TH:i') : '',
                                                'end_date' => $contest->end_date ? \Carbon\Carbon::parse($contest->end_date)->format('Y-m-d\TH:i') : '',
                                                'status' => $contest->status,
                                                'update_url' => route('admin.contests.update', $contest->id),
                                            ]) }})"
                                            title="Force Edit Contest"
                                            class="p-1.5 text-gray-500 hover:text-brand-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                                        <i class="bi bi-pencil-square text-sm"></i>
                                    </button>

                                    <!-- Force Cancel -->
                                    @if($contest->status !== 'cancelled')
                                        <form method="POST" action="{{ route('admin.contests.cancel', $contest->id) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    onclick="return confirm('Cancel contest \"{{ addslashes($contest->title) }}\"?')"
                                                    title="Force Cancel Contest"
                                                    class="p-1.5 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-500/10 rounded-lg transition-colors">
                                                <i class="bi bi-pause-circle text-sm"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Force Delete -->
                                    <form method="POST" action="{{ route('admin.contests.destroy', $contest->id) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('⚠️ PERMANENT: Delete contest \"{{ addslashes($contest->title) }}\" and wipe all submissions?')"
                                                title="Force Delete Fraudulent Contest"
                                                class="p-1.5 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-lg transition-colors">
                                            <i class="bi bi-trash-fill text-sm"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-gray-500 dark:text-gray-400">
                                <i class="bi bi-trophy text-4xl text-gray-400 block mb-2"></i>
                                No contests match this filter.
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

<!-- CREATE CONTEST MODAL -->
<div id="createContestModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-[#0F1623] rounded-2xl max-w-lg w-full p-6 border border-gray-200 dark:border-gray-800 shadow-2xl">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-800 mb-4">
            <h3 class="font-bold text-sm text-gray-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-trophy-fill text-brand-500"></i> Launch Design Contest
            </h3>
            <button type="button" onclick="closeCreateContestModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-white">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.contests.store') }}" class="space-y-3 text-xs">
            @csrf
            <div>
                <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Contest Title</label>
                <input type="text" name="title" required placeholder="e.g., Bangladeshi FinTech Mobile App UI" class="w-full px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:outline-none focus:border-brand-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Category</label>
                    <select name="category_id" class="w-full px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Prize Bounty (৳ BDT)</label>
                    <input type="number" step="0.01" min="1" name="prize_amount" required placeholder="5000" class="w-full px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white font-mono">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Start Date</label>
                    <input type="datetime-local" name="start_date" required class="w-full px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">End Date</label>
                    <input type="datetime-local" name="end_date" required class="w-full px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>

            <div>
                <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Contest Brief & Deliverable Guidelines</label>
                <textarea name="description" rows="3" required placeholder="Outline specifications, required artboards, color schemes..." class="w-full px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeCreateContestModal()" class="px-4 py-2 rounded-xl font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold transition-colors">
                    Publish Contest
                </button>
            </div>
        </form>
    </div>
</div>

<!-- FORCE EDIT CONTEST MODAL -->
<div id="editContestModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-[#0F1623] rounded-2xl max-w-lg w-full p-6 border border-gray-200 dark:border-gray-800 shadow-2xl">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-800 mb-4">
            <h3 class="font-bold text-sm text-gray-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-pencil-square text-brand-500"></i> Force Edit Contest
            </h3>
            <button type="button" onclick="closeEditContestModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-white">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="editContestForm" method="POST" action="" class="space-y-3 text-xs">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Contest Title</label>
                <input id="editTitle" type="text" name="title" required class="w-full px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Category</label>
                    <select id="editCategory" name="category_id" class="w-full px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Prize Bounty (৳ BDT)</label>
                    <input id="editPrize" type="number" step="0.01" min="1" name="prize_amount" required class="w-full px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white font-mono">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Start Date</label>
                    <input id="editStartDate" type="datetime-local" name="start_date" required class="w-full px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">End Date</label>
                    <input id="editEndDate" type="datetime-local" name="end_date" required class="w-full px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>

            <div>
                <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select id="editStatus" name="status" class="w-full px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white">
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <div>
                <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Contest Description</label>
                <textarea id="editDesc" name="description" rows="3" required class="w-full px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeEditContestModal()" class="px-4 py-2 rounded-xl font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold transition-colors">
                    Save Changes
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

    function openEditContestModal(data) {
        document.getElementById('editTitle').value = data.title;
        document.getElementById('editCategory').value = data.category_id || '';
        document.getElementById('editPrize').value = data.prize_amount;
        document.getElementById('editStartDate').value = data.start_date;
        document.getElementById('editEndDate').value = data.end_date;
        document.getElementById('editStatus').value = data.status;
        document.getElementById('editDesc').value = data.description;
        document.getElementById('editContestForm').action = data.update_url;

        document.getElementById('editContestModal').classList.remove('hidden');
    }
    function closeEditContestModal() {
        document.getElementById('editContestModal').classList.add('hidden');
    }
</script>
@endsection
