@extends('layouts.admin')

@section('title', 'Support Messages Inbox - Noksha Admin HQ')
@section('page_title', 'Support Inbox')
@section('page_heading', 'Customer Support Messages & Inquiries')

@section('content')
<div x-data="{
    activeModal: false,
    selectedMsg: null,
    replyText: '',
    viewMessage(msg) {
        this.selectedMsg = msg;
        this.replyText = '';
        this.activeModal = true;
    }
}" class="space-y-6">

    <!-- 1. TOP STAT METRICS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total Inquiries -->
        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-5 border border-gray-200 dark:border-gray-800 shadow-sm relative overflow-hidden group hover:border-brand-500/50 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Inquiries</span>
                <div class="w-9 h-9 rounded-xl bg-sky-500/10 text-sky-500 flex items-center justify-center">
                    <i class="bi bi-inbox-fill text-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($totalCount) }}</div>
                <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Submitted via /contact page</div>
            </div>
        </div>

        <!-- Unread Messages (Highlighted in Rose/Amber) -->
        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-5 border {{ $unreadCount > 0 ? 'border-amber-400/80 dark:border-amber-500/50 bg-amber-500/[0.03]' : 'border-gray-200 dark:border-gray-800' }} shadow-sm relative overflow-hidden group transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold {{ $unreadCount > 0 ? 'text-amber-500' : 'text-gray-500 dark:text-gray-400' }} uppercase tracking-wider">
                    Unread Messages
                </span>
                <div class="w-9 h-9 rounded-xl {{ $unreadCount > 0 ? 'bg-amber-500/20 text-amber-400 animate-pulse' : 'bg-gray-500/10 text-gray-400' }} flex items-center justify-center">
                    <i class="bi bi-envelope-exclamation-fill text-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black {{ $unreadCount > 0 ? 'text-amber-400' : 'text-gray-900 dark:text-white' }}">
                    {{ number_format($unreadCount) }}
                </div>
                <div class="text-[11px] mt-1">
                    @if($unreadCount > 0)
                        <span class="text-amber-500 font-bold flex items-center gap-1">
                            <i class="bi bi-lightning-charge-fill"></i> Awaiting review
                        </span>
                    @else
                        <span class="text-emerald-500 font-semibold">Inbox all caught up</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Replied / Resolved -->
        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-5 border border-gray-200 dark:border-gray-800 shadow-sm relative overflow-hidden group hover:border-emerald-500/50 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Replied / Resolved</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                    <i class="bi bi-check2-circle text-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($repliedCount) }}</div>
                <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Support answered</div>
            </div>
        </div>

        <!-- Read / Reviewed -->
        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-5 border border-gray-200 dark:border-gray-800 shadow-sm relative overflow-hidden group hover:border-brand-500/50 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Read / Pending Reply</span>
                <div class="w-9 h-9 rounded-xl bg-purple-500/10 text-purple-500 flex items-center justify-center">
                    <i class="bi bi-envelope-open-fill text-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($readCount) }}</div>
                <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Reviewed by staff</div>
            </div>
        </div>

    </div>

    <!-- 2. FILTER CONTROLS & SEARCH BAR -->
    <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-4 border border-gray-200 dark:border-gray-800 shadow-sm space-y-4">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            
            <!-- Status Tabs (All, Unread, Read, Replied) -->
            <div class="flex flex-wrap items-center gap-1.5 p-1 bg-gray-100 dark:bg-gray-900/80 rounded-xl border border-gray-200 dark:border-gray-800">
                <a href="{{ route('admin.messages.index', array_merge(request()->query(), ['status' => 'all'])) }}"
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ $status === 'all' ? 'bg-brand-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-white' }}">
                    All ({{ $totalCount }})
                </a>
                <a href="{{ route('admin.messages.index', array_merge(request()->query(), ['status' => 'unread'])) }}"
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 {{ $status === 'unread' ? 'bg-amber-600 text-white shadow-sm' : 'text-amber-500 dark:text-amber-400 hover:text-white' }}">
                    <span>Unread</span>
                    @if($unreadCount > 0)
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'unread' ? 'bg-white/20 text-white' : 'bg-amber-500/20 text-amber-400' }}">{{ $unreadCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.messages.index', array_merge(request()->query(), ['status' => 'read'])) }}"
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ $status === 'read' ? 'bg-brand-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-white' }}">
                    Read ({{ $readCount }})
                </a>
                <a href="{{ route('admin.messages.index', array_merge(request()->query(), ['status' => 'replied'])) }}"
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ $status === 'replied' ? 'bg-emerald-600 text-white shadow-sm' : 'text-emerald-500 dark:text-emerald-400 hover:text-white' }}">
                    Replied ({{ $repliedCount }})
                </a>
            </div>

            <!-- Subject Category & Search Input -->
            <form method="GET" action="{{ route('admin.messages.index') }}" class="flex flex-wrap items-center gap-2">
                @if($status !== 'all')
                    <input type="hidden" name="status" value="{{ $status }}">
                @endif

                <!-- Subject Dropdown -->
                <select name="subject" onchange="this.form.submit()" class="px-3 py-2 rounded-xl text-xs bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:outline-none focus:border-brand-500">
                    <option value="all" {{ $subject === 'all' ? 'selected' : '' }}>All Subjects</option>
                    @foreach($subjects as $subj)
                        <option value="{{ $subj }}" {{ $subject === $subj ? 'selected' : '' }}>{{ $subj }}</option>
                    @endforeach
                </select>

                <!-- Search Input -->
                <div class="relative">
                    <input type="text" name="q" value="{{ $search }}" placeholder="Search name, email, query..."
                           class="w-56 pl-8 pr-3 py-2 rounded-xl text-xs bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-brand-500">
                    <i class="bi bi-search absolute left-2.5 top-2.5 text-gray-400 text-xs"></i>
                </div>

                <button type="submit" class="px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold transition">
                    Filter
                </button>

                @if($search || $subject !== 'all' || $status !== 'all')
                    <a href="{{ route('admin.messages.index') }}" class="p-2 text-gray-400 hover:text-white text-xs" title="Reset Filters">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </form>

        </div>

    </div>

    <!-- 3. MESSAGES TABLE / INBOX LIST -->
    <div class="bg-white dark:bg-[#0F1623] rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600 dark:text-gray-300">
                <thead class="bg-gray-50 dark:bg-gray-900/60 uppercase text-[11px] font-bold tracking-wider text-gray-400 border-b border-gray-200 dark:border-gray-800">
                    <tr>
                        <th class="px-5 py-3.5">Sender</th>
                        <th class="px-5 py-3.5">Subject</th>
                        <th class="px-5 py-3.5">Message Preview</th>
                        <th class="px-5 py-3.5">Received</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800/60 font-sans">
                    @forelse($messages as $msg)
                        <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-800/30 transition-colors {{ $msg->status === 'unread' ? 'bg-amber-500/[0.02]' : '' }}">
                            
                            <!-- Sender Column -->
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center font-bold text-xs {{ $msg->status === 'unread' ? 'bg-amber-500 text-gray-950 font-black' : 'bg-gray-800 text-gray-300' }}">
                                        {{ strtoupper(substr($msg->name, 0, 1)) }}
                                    </div>
                                    <div class="truncate max-w-[160px]">
                                        <div class="font-bold text-gray-900 dark:text-white flex items-center gap-1.5 truncate">
                                            <span>{{ $msg->name }}</span>
                                            @if($msg->user)
                                                <i class="bi bi-patch-check-fill text-brand-400 text-[10px]" title="Registered User"></i>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-gray-400 font-mono truncate">{{ $msg->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Subject Category Pill -->
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                    {{ $msg->subject }}
                                </span>
                            </td>

                            <!-- Message Excerpt -->
                            <td class="px-5 py-3.5 cursor-pointer max-w-xs" 
                                @click="viewMessage({{ json_encode([
                                    'id' => $msg->id,
                                    'name' => $msg->name,
                                    'email' => $msg->email,
                                    'subject' => $msg->subject,
                                    'message' => $msg->message,
                                    'status' => $msg->status,
                                    'created_at' => $msg->created_at->format('M d, Y • h:i A'),
                                    'diff' => $msg->created_at->diffForHumans(),
                                    'is_registered' => (bool)$msg->user_id,
                                    'username' => $msg->user?->username ?? $msg->user?->name,
                                    'profile_url' => $msg->user ? route('user.profile', $msg->user->username ?? $msg->user->id) : null,
                                ]) }})">
                                <div class="truncate text-gray-700 dark:text-gray-300 {{ $msg->status === 'unread' ? 'font-semibold text-gray-900 dark:text-white' : '' }}">
                                    {{ Str::limit($msg->message, 80) }}
                                </div>
                                <span class="text-[10px] text-brand-400 hover:underline">Click to read full message &rarr;</span>
                            </td>

                            <!-- Date Received -->
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $msg->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-gray-500 dark:text-gray-400 font-mono">{{ $msg->created_at->diffForHumans() }}</div>
                            </td>

                            <!-- Status Badge -->
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                @if($msg->status === 'unread')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/30 flex items-center gap-1.5 w-max">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-ping"></span>
                                        <span>Unread</span>
                                    </span>
                                @elseif($msg->status === 'replied')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 flex items-center gap-1 w-max">
                                        <i class="bi bi-check-all"></i>
                                        <span>Replied</span>
                                    </span>
                                @elseif($msg->status === 'read')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-500/10 text-slate-400 border border-slate-500/30 flex items-center gap-1 w-max">
                                        <i class="bi bi-eye"></i>
                                        <span>Read</span>
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-500/10 text-purple-400 border border-purple-500/30 w-max">
                                        {{ ucfirst($msg->status) }}
                                    </span>
                                @endif
                            </td>

                            <!-- Quick Actions -->
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    
                                    <!-- View Modal Trigger -->
                                    <button type="button"
                                            @click="viewMessage({{ json_encode([
                                                'id' => $msg->id,
                                                'name' => $msg->name,
                                                'email' => $msg->email,
                                                'subject' => $msg->subject,
                                                'message' => $msg->message,
                                                'status' => $msg->status,
                                                'created_at' => $msg->created_at->format('M d, Y • h:i A'),
                                                'diff' => $msg->created_at->diffForHumans(),
                                                'is_registered' => (bool)$msg->user_id,
                                                'username' => $msg->user?->username ?? $msg->user?->name,
                                                'profile_url' => $msg->user ? route('user.profile', $msg->user->username ?? $msg->user->id) : null,
                                            ]) }})"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-brand-400 hover:bg-brand-500/10 transition cursor-pointer"
                                            title="View Full Message">
                                        <i class="bi bi-eye-fill text-sm"></i>
                                    </button>

                                    <!-- Quick Mailto -->
                                    <a href="mailto:{{ $msg->email }}?subject={{ rawurlencode('Re: ' . $msg->subject . ' - Noksha Support') }}"
                                       class="p-1.5 rounded-lg text-gray-400 hover:text-emerald-400 hover:bg-emerald-500/10 transition"
                                       title="Quick Reply via Email">
                                        <i class="bi bi-reply-fill text-sm"></i>
                                    </a>

                                    <!-- Delete Inquiry -->
                                    <form method="POST" action="{{ route('admin.messages.destroy', $msg->id) }}" class="inline" onsubmit="return confirm('Delete this support inquiry from {{ addslashes($msg->name) }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-rose-400 hover:bg-rose-500/10 transition cursor-pointer" title="Delete Inquiry">
                                            <i class="bi bi-trash3-fill text-sm"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                                <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-800 text-gray-400 flex items-center justify-center mx-auto mb-3">
                                    <i class="bi bi-inbox fs-3 opacity-60"></i>
                                </div>
                                <h5 class="text-sm font-bold text-gray-900 dark:text-white mb-1">No Messages Found</h5>
                                <p class="text-xs text-gray-500">There are no customer inquiries matching your filter criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($messages->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-gray-800/80 flex justify-center">
                {{ $messages->links() }}
            </div>
        @endif

    </div>

    <!-- 4. ALPINE.JS MESSAGE DETAIL MODAL -->
    <div x-show="activeModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
         style="display: none;"
         @keydown.escape.window="activeModal = false">
        
        <div class="bg-gray-900 border border-gray-800 rounded-2xl max-w-xl w-full p-6 shadow-2xl relative space-y-4 max-h-[92vh] overflow-y-auto"
             @click.away="activeModal = false">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3 border-b border-gray-800">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-brand-500/20 text-brand-400 flex items-center justify-center font-bold">
                        <i class="bi bi-envelope-open-fill"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-white" x-text="selectedMsg?.subject || 'Support Message'"></h4>
                        <div class="text-[11px] text-gray-400" x-text="'Received: ' + (selectedMsg?.created_at || '')"></div>
                    </div>
                </div>
                <button type="button" @click="activeModal = false" class="text-gray-400 hover:text-white cursor-pointer">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <!-- Sender Information Card -->
            <div class="p-3.5 rounded-xl bg-gray-950/70 border border-gray-800 text-xs space-y-2">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <div>
                        <span class="text-gray-400 text-[11px] block">Sender Name:</span>
                        <strong class="text-white text-sm" x-text="selectedMsg?.name"></strong>
                    </div>
                    <div class="text-right">
                        <span class="text-gray-400 text-[11px] block">Sender Email:</span>
                        <a :href="'mailto:' + selectedMsg?.email" class="text-brand-400 hover:underline font-mono" x-text="selectedMsg?.email"></a>
                    </div>
                </div>

                <template x-if="selectedMsg?.is_registered">
                    <div class="pt-2 border-t border-gray-800/60 flex items-center justify-between">
                        <span class="text-[11px] text-emerald-400 flex items-center gap-1 font-semibold">
                            <i class="bi bi-patch-check-fill"></i> Authenticated Noksha User
                        </span>
                        <a :href="selectedMsg?.profile_url" target="_blank" class="text-[11px] text-brand-400 hover:underline">
                            View Public Profile &rarr;
                        </a>
                    </div>
                </template>
            </div>

            <!-- Message Body -->
            <div>
                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Full Message Body</label>
                <div class="p-4 rounded-xl bg-gray-950 border border-gray-800 text-xs text-gray-200 leading-relaxed max-h-48 overflow-y-auto whitespace-pre-wrap font-sans select-text"
                     x-text="selectedMsg?.message">
                </div>
            </div>

            <!-- Dual-Channel Support Reply Interface (Email + In-App Notification) -->
            <form method="POST" :action="'/admin/messages/' + selectedMsg?.id + '/reply'" class="space-y-3 pt-3 border-t border-gray-800">
                @csrf
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <label for="admin_reply_message" class="block text-[11px] font-bold text-gray-300 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="bi bi-reply-fill text-brand-400 text-sm"></i> Official Dual-Channel Reply
                    </label>
                    <div class="flex items-center gap-2 text-[10px] text-gray-400">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-sky-500/10 text-sky-400 border border-sky-500/20">
                            <i class="bi bi-envelope-at-fill"></i> Email Dispatch
                        </span>
                        <template x-if="selectedMsg?.is_registered">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                <i class="bi bi-bell-fill"></i> In-App Notification
                            </span>
                        </template>
                    </div>
                </div>

                <div class="relative">
                    <textarea id="admin_reply_message"
                              name="admin_reply_message"
                              rows="4"
                              x-model="replyText"
                              required
                              placeholder="Type your official administrative response here... It will be emailed to the sender and delivered as an in-app database notification if they are a registered user."
                              class="w-full p-3.5 rounded-xl bg-gray-950 border border-gray-800 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-xs text-gray-200 placeholder-gray-500 leading-relaxed font-sans transition resize-none"></textarea>
                </div>

                <div class="flex items-center justify-between flex-wrap gap-2">
                    <!-- Secondary Mailto Direct Client Link -->
                    <a :href="'mailto:' + selectedMsg?.email + '?subject=' + encodeURIComponent('Re: ' + (selectedMsg?.subject || 'Support Inquiry') + ' - Noksha Support')"
                       class="text-[11px] text-gray-400 hover:text-gray-200 transition flex items-center gap-1">
                        <i class="bi bi-box-arrow-up-right text-[10px]"></i> Open in Native Email App
                    </a>

                    <!-- Submit Official Reply Button -->
                    <button type="submit"
                            :disabled="!replyText || replyText.trim().length < 3"
                            class="px-4 py-2 rounded-xl text-xs font-bold bg-brand-600 hover:bg-brand-500 disabled:opacity-50 disabled:cursor-not-allowed text-white flex items-center gap-2 transition shadow-lg shadow-brand-950/40 cursor-pointer">
                        <i class="bi bi-send-fill text-xs"></i>
                        <span>Send Official Reply</span>
                    </button>
                </div>
            </form>

            <!-- Quick Status & Management Controls -->
            <div class="pt-3 border-t border-gray-800 flex flex-wrap items-center justify-between gap-2">
                <span class="text-[11px] font-semibold text-gray-500">Quick Actions:</span>
                <div class="flex items-center gap-1.5">
                    
                    <!-- Mark Read Form -->
                    <form method="POST" :action="'/admin/messages/' + selectedMsg?.id + '/status'">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="read">
                        <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-gray-800 hover:bg-gray-700 text-gray-300 transition cursor-pointer">
                            Mark Read
                        </button>
                    </form>

                    <!-- Mark Replied Form -->
                    <form method="POST" :action="'/admin/messages/' + selectedMsg?.id + '/status'">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="replied">
                        <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-600/20 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-600 hover:text-white transition cursor-pointer">
                            Mark Replied
                        </button>
                    </form>

                    <!-- Delete Form -->
                    <form method="POST" :action="'/admin/messages/' + selectedMsg?.id" onsubmit="return confirm('Permanently delete this inquiry?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-600/20 text-rose-400 border border-rose-500/30 hover:bg-rose-600 hover:text-white transition cursor-pointer">
                            Delete
                        </button>
                    </form>

                </div>
            </div>

        </div>

    </div>

</div>
@endsection
