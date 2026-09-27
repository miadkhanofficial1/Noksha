@extends('layouts.admin')

@section('title', 'Broadcast History — Admin')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center">
                    <i class="bi bi-megaphone-fill text-lg"></i>
                </span>
                Broadcast History
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">All system-wide announcements sent to users.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}"
           class="text-xs font-semibold text-indigo-500 hover:text-indigo-700 flex items-center gap-1">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Broadcasts Table -->
    <div class="bg-white dark:bg-[#0F1623] rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">

        @if($broadcasts->count() > 0)
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Message</th>
                        <th class="px-5 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Recipients</th>
                        <th class="px-5 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Read</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sent</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($broadcasts as $bc)
                        <tr class="hover:bg-indigo-50/30 dark:hover:bg-indigo-500/5 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-indigo-500/10 text-indigo-500 flex items-center justify-center flex-shrink-0">
                                        <i class="bi bi-megaphone-fill text-xs"></i>
                                    </div>
                                    <span class="font-semibold text-gray-900 dark:text-white text-sm">{{ $bc['title'] }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-gray-500 dark:text-gray-400 text-xs max-w-xs truncate" title="{{ $bc['message'] }}">
                                    {{ $bc['message'] }}
                                </p>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="badge bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 rounded-full px-2.5 py-0.5 text-xs font-bold">
                                    {{ $bc['recipients'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                @php $pct = $bc['recipients'] > 0 ? round(($bc['read_count'] / $bc['recipients']) * 100) : 0; @endphp
                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">{{ $bc['read_count'] }}/{{ $bc['recipients'] }}</span>
                                    <div class="w-20 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-violet-500" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="text-[10px] text-gray-400">{{ $pct }}% read</span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-mono">
                                    {{ $bc['sent_at']->format('d M Y, g:i A') }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="py-16 text-center">
                <div class="w-16 h-16 rounded-full bg-indigo-500/10 text-indigo-500 flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-megaphone fs-3"></i>
                </div>
                <h3 class="font-bold text-gray-700 dark:text-gray-300 mb-1">No Broadcasts Yet</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Broadcasts you send from the dashboard will appear here.</p>
                <a href="{{ route('admin.dashboard') }}" class="mt-4 inline-block text-xs font-semibold text-indigo-500 hover:underline">
                    Go Send a Broadcast →
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
