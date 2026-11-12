@extends('layouts.admin')

@section('title', 'Broadcast History — Admin')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-[#121826] flex items-center gap-2">
                <span class="w-10 h-10 rounded-xl bg-purple-50 text-[#20165c] border border-purple-200 flex items-center justify-center">
                    <i class="bi bi-megaphone-fill text-lg"></i>
                </span>
                Broadcast History
            </h1>
            <p class="text-sm text-slate-500 mt-0.5">All system-wide announcements sent to users.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}"
           class="text-xs font-semibold text-[#20165c] hover:text-[#181045] flex items-center gap-1 transition">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Broadcasts Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        @if($broadcasts->count() > 0)
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Title</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Message</th>
                        <th class="px-5 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Recipients</th>
                        <th class="px-5 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Read</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Sent</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($broadcasts as $bc)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-purple-50 text-[#20165c] border border-purple-200 flex items-center justify-center flex-shrink-0">
                                        <i class="bi bi-megaphone-fill text-xs"></i>
                                    </div>
                                    <span class="font-semibold text-[#121826] text-sm">{{ $bc['title'] }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-slate-500 text-xs max-w-xs truncate" title="{{ $bc['message'] }}">
                                    {{ $bc['message'] }}
                                </p>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="badge bg-purple-50 text-[#20165c] border border-purple-200 rounded-full px-2.5 py-0.5 text-xs font-bold">
                                    {{ $bc['recipients'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                @php $pct = $bc['recipients'] > 0 ? round(($bc['read_count'] / $bc['recipients']) * 100) : 0; @endphp
                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-xs font-semibold text-slate-700">{{ $bc['read_count'] }}/{{ $bc['recipients'] }}</span>
                                    <div class="w-20 h-1.5 bg-slate-100 rounded-full overflow-hidden border border-slate-200">
                                        <div class="h-full rounded-full bg-[#20165c]" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="text-[10px] text-slate-400">{{ $pct }}% read</span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-xs text-slate-500 font-mono">
                                    {{ $bc['sent_at']->format('d M Y, g:i A') }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="py-16 text-center">
                <div class="w-16 h-16 rounded-full bg-purple-50 text-[#20165c] border border-purple-200 flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-megaphone fs-3"></i>
                </div>
                <h3 class="font-bold text-[#121826] mb-1">No Broadcasts Yet</h3>
                <p class="text-sm text-slate-500">Broadcasts you send from the dashboard will appear here.</p>
                <a href="{{ route('admin.dashboard') }}" class="mt-4 inline-block text-xs font-semibold text-[#20165c] hover:underline">
                    Go Send a Broadcast →
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
