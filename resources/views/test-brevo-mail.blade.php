<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brevo REST API Diagnostic - Noksha Security</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #030712; color: #F3F4F6; }
    </style>
</head>
<body class="p-6 sm:p-12 font-sans antialiased">
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between pb-6 border-b border-slate-800">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 text-xs font-semibold uppercase tracking-wider mb-2">
                    <i class="bi bi-shield-check text-xs"></i>
                    <span>HTTPS Port 443 REST API</span>
                </div>
                <h1 class="text-2xl font-bold text-white">Brevo Mail Delivery Diagnostic</h1>
                <p class="text-sm text-slate-400 mt-1">Live telemetry for outbound Brevo HTTPS REST API (Port 443 - 100% open on Render)</p>
            </div>
            <div>
                @if ($diagnostics['dispatch']['success'])
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-500/15 border border-emerald-500/40 text-emerald-400 font-bold text-sm">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>DELIVERY SUCCESS</span>
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-rose-500/15 border border-rose-500/40 text-rose-400 font-bold text-sm">
                        <i class="bi bi-x-circle-fill"></i>
                        <span>DELIVERY FAILED</span>
                    </span>
                @endif
            </div>
        </div>

        <!-- Recipient Bar -->
        <form action="{{ url('/test-brevo-mail') }}" method="GET" class="flex gap-2">
            <input type="email" 
                   name="to" 
                   value="{{ $recipient }}" 
                   placeholder="Enter destination email" 
                   required
                   class="flex-1 px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-white text-sm focus:outline-none focus:border-cyan-400 font-mono">
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-cyan-500 text-white font-semibold text-sm hover:opacity-90 transition-all flex items-center gap-2">
                <i class="bi bi-send-fill text-xs"></i>
                <span>Test Live Send</span>
            </button>
        </form>

        <!-- Configuration Card -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-5 space-y-3">
            <h2 class="text-xs font-semibold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                <i class="bi bi-sliders text-cyan-400"></i>
                <span>Active API Configuration</span>
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                <div class="p-3 rounded-lg bg-slate-800/60 border border-slate-700/50">
                    <span class="text-slate-500 block">Transport Mode</span>
                    <span class="font-mono text-cyan-300 font-semibold">{{ $config['mode'] }}</span>
                </div>
                <div class="p-3 rounded-lg bg-slate-800/60 border border-slate-700/50">
                    <span class="text-slate-500 block">API Key Status</span>
                    <span class="font-mono {{ str_contains($config['api_key_status'], 'CONFIGURED') ? 'text-emerald-400' : 'text-amber-400' }} truncate block">{{ $config['api_key_status'] }}</span>
                </div>
                <div class="p-3 rounded-lg bg-slate-800/60 border border-slate-700/50">
                    <span class="text-slate-500 block">From Address</span>
                    <span class="font-mono text-slate-300">{{ $config['from_address'] }}</span>
                </div>
                <div class="p-3 rounded-lg bg-slate-800/60 border border-slate-700/50">
                    <span class="text-slate-500 block">Timeout</span>
                    <span class="font-mono text-cyan-300">{{ $config['http_timeout'] }}</span>
                </div>
            </div>
        </div>

        <!-- Step 1: HTTPS REST API Connectivity Check -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-5 space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="text-xs font-semibold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="bi bi-globe2 text-cyan-400"></i>
                    <span>Step 1: Brevo HTTPS REST API Ping (Port 443)</span>
                </h2>
                <span class="text-xs font-mono {{ str_contains($diagnostics['api_connectivity']['status'], 'ONLINE') ? 'text-emerald-400' : 'text-rose-400' }} font-bold">
                    {{ $diagnostics['api_connectivity']['status'] }} ({{ $diagnostics['api_connectivity']['latency_ms'] }} ms)
                </span>
            </div>
            <div class="p-3 rounded-lg bg-slate-950 font-mono text-xs text-slate-300 overflow-x-auto space-y-1">
                <div><span class="text-slate-500">API Endpoint:</span> {{ $diagnostics['api_connectivity']['endpoint'] }}</div>
                @if (isset($diagnostics['api_connectivity']['http_code']))
                    <div><span class="text-slate-500">HTTP Response Code:</span> <span class="text-cyan-400">{{ $diagnostics['api_connectivity']['http_code'] }}</span></div>
                @endif
                @if (isset($diagnostics['api_connectivity']['error']))
                    <div><span class="text-slate-500">Connection Error:</span> <span class="text-rose-400">{{ $diagnostics['api_connectivity']['error'] }}</span></div>
                @endif
            </div>
        </div>

        <!-- Step 2: Email Dispatch Result -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-5 space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="text-xs font-semibold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="bi bi-envelope-paper text-indigo-400"></i>
                    <span>Step 2: Live OTP Dispatch via Brevo REST API</span>
                </h2>
                <span class="text-xs font-mono {{ $diagnostics['dispatch']['success'] ? 'text-emerald-400' : 'text-rose-400' }} font-bold">
                    {{ $diagnostics['dispatch']['success'] ? 'SUCCESS' : 'FAILED' }} ({{ $diagnostics['dispatch']['duration_ms'] }} ms)
                </span>
            </div>
            <div class="p-4 rounded-lg bg-slate-950 font-mono text-xs space-y-2">
                <div><span class="text-slate-500">Recipient:</span> <span class="text-white">{{ $diagnostics['dispatch']['recipient'] }}</span></div>
                <div><span class="text-slate-500">Transport:</span> <span class="text-cyan-400">{{ $diagnostics['dispatch']['transport_used'] }}</span></div>
                <div><span class="text-slate-500">Generated OTP:</span> <span class="text-amber-400 font-bold tracking-widest">{{ $diagnostics['dispatch']['otp_generated'] }}</span></div>
                @if ($diagnostics['dispatch']['message_id'])
                    <div><span class="text-slate-500">Message ID:</span> <span class="text-emerald-400">{{ $diagnostics['dispatch']['message_id'] }}</span></div>
                @endif
                @if ($diagnostics['dispatch']['error'])
                    <div class="mt-2 pt-2 border-t border-slate-800">
                        <span class="text-rose-400 block font-semibold mb-1">API Response / Error:</span>
                        <div class="p-3 rounded bg-rose-950/30 border border-rose-900/50 text-rose-300 whitespace-pre-wrap leading-relaxed">{{ $diagnostics['dispatch']['error'] }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Setup Instructions -->
        <div class="bg-slate-900/60 border border-slate-800/60 rounded-2xl p-5 text-xs text-slate-400 space-y-2">
            <div class="font-semibold text-slate-300 flex items-center gap-1.5">
                <i class="bi bi-key text-cyan-400"></i>
                <span>How to Configure Brevo REST API Key</span>
            </div>
            <p>
                1. Go to <a href="https://app.brevo.com/settings/keys/api" target="_blank" class="text-cyan-400 underline">app.brevo.com &gt; SMTP &amp; API &gt; API Keys</a>.
            </p>
            <p>
                2. Click <strong>Generate a new API key</strong> (starts with <code class="text-cyan-300 bg-slate-800 px-1 py-0.5 rounded">xkeysib-</code>).
            </p>
            <p>
                3. Add to your <code class="text-slate-300">.env</code> or Render Environment Variables:
                <br>
                <code class="text-cyan-300 bg-slate-800 px-2 py-1 rounded mt-1 inline-block">BREVO_API_KEY=xkeysib-your-key-here</code>
            </p>
            <p>
                Note: All generated OTP codes continue to be logged directly into <code class="text-cyan-300 bg-slate-800 px-1 py-0.5 rounded">storage/logs/laravel.log</code> for instant local and staging testing.
            </p>
        </div>

        <div class="text-center pt-2">
            <a href="{{ route('password.request') }}" class="text-xs text-slate-500 hover:text-cyan-400 inline-flex items-center gap-1.5 transition-colors">
                <i class="bi bi-arrow-left"></i>
                <span>Return to Forgot Password</span>
            </a>
        </div>
    </div>
</body>
</html>
