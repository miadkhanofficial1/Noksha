@extends('layouts.admin')

@section('title', 'Live System Logs & Diagnostics - Noksha Admin HQ')
@section('page_title', 'System Logs')
@section('page_heading', 'Live Application Logs & Engine Diagnostics')

@section('content')
<div class="space-y-6">

    <!-- DIAGNOSTICS STATS -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-3.5 border border-gray-200 dark:border-gray-800 shadow-sm">
            <span class="text-[10px] text-gray-400 block uppercase font-bold">PHP Runtime</span>
            <strong class="text-xs text-gray-900 dark:text-white font-mono">{{ $systemInfo['php_version'] }}</strong>
        </div>
        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-3.5 border border-gray-200 dark:border-gray-800 shadow-sm">
            <span class="text-[10px] text-gray-400 block uppercase font-bold">Laravel Engine</span>
            <strong class="text-xs text-brand-500 font-mono">v{{ $systemInfo['laravel_version'] }}</strong>
        </div>
        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-3.5 border border-gray-200 dark:border-gray-800 shadow-sm">
            <span class="text-[10px] text-gray-400 block uppercase font-bold">Environment</span>
            <strong class="text-xs text-emerald-500 font-mono uppercase">{{ $systemInfo['environment'] }}</strong>
        </div>
        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-3.5 border border-gray-200 dark:border-gray-800 shadow-sm">
            <span class="text-[10px] text-gray-400 block uppercase font-bold">Debug Mode</span>
            <strong class="text-xs text-amber-500 font-mono">{{ $systemInfo['debug_mode'] }}</strong>
        </div>
        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-3.5 border border-gray-200 dark:border-gray-800 shadow-sm">
            <span class="text-[10px] text-gray-400 block uppercase font-bold">Log File Size</span>
            <strong class="text-xs text-purple-500 font-mono">{{ $systemInfo['log_file_size'] }}</strong>
        </div>
        <div class="bg-white dark:bg-[#0F1623] rounded-2xl p-3.5 border border-gray-200 dark:border-gray-800 shadow-sm">
            <span class="text-[10px] text-gray-400 block uppercase font-bold">Memory Allocated</span>
            <strong class="text-xs text-teal-500 font-mono">{{ $systemInfo['memory_usage'] }}</strong>
        </div>
    </div>

    <!-- LOG VIEWER CONTAINER -->
    <div class="bg-[#0B0F19] rounded-2xl border border-gray-800 shadow-xl overflow-hidden font-mono text-xs">
        
        <!-- Header -->
        <div class="p-4 bg-[#080B11] border-b border-gray-800 flex items-center justify-between flex-wrap gap-2">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-rose-500 inline-block"></span>
                <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
                <span class="text-gray-400 text-xs ml-2 font-sans font-bold">storage/logs/laravel.log (Latest {{ count($logs) }} events)</span>
            </div>

            <form method="POST" action="{{ route('admin.logs.clear') }}">
                @csrf
                <button type="submit"
                        onclick="return confirm('Wipe and truncate laravel.log?')"
                        class="px-3 py-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500 text-rose-400 hover:text-white border border-rose-500/20 font-sans font-bold text-xs transition-colors flex items-center gap-1.5">
                    <i class="bi bi-trash"></i> Wipe Logs
                </button>
            </form>
        </div>

        <!-- Log entries list -->
        <div class="divide-y divide-gray-800/80 max-h-[650px] overflow-y-auto">
            @forelse($logs as $index => $log)
                <div class="p-4 hover:bg-gray-800/30 transition-colors">
                    <div class="flex items-center gap-3 mb-1.5 flex-wrap text-[11px]">
                        <span class="text-gray-500">{{ $log['date'] }}</span>
                        
                        @php
                            $lvl = strtoupper($log['level']);
                            $badgeClass = match($lvl) {
                                'EMERGENCY', 'ALERT', 'CRITICAL', 'ERROR' => 'bg-rose-500/20 text-rose-300 border-rose-500/40',
                                'WARNING' => 'bg-amber-500/20 text-amber-300 border-amber-500/40',
                                'NOTICE', 'INFO' => 'bg-sky-500/20 text-sky-300 border-sky-500/40',
                                default => 'bg-gray-700/50 text-gray-300 border-gray-600',
                            };
                        @endphp
                        
                        <span class="px-2 py-0.5 rounded font-bold border {{ $badgeClass }} text-[10px]">
                            {{ $lvl }}
                        </span>

                        <span class="text-gray-500 uppercase text-[10px]">{{ $log['env'] }}</span>
                    </div>

                    <pre class="text-gray-300 whitespace-pre-wrap break-all leading-relaxed">{{ $log['message'] }}</pre>
                </div>
            @empty
                <div class="p-12 text-center text-gray-500 font-sans">
                    <i class="bi bi-check-circle-fill text-3xl text-emerald-500 block mb-2"></i>
                    Log file is pristine. No errors or warnings logged.
                </div>
            @endforelse
        </div>

    </div>

</div>
@endsection
