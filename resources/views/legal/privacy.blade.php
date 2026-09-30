@extends('layouts.app')

@section('title', 'Privacy Policy & Data Protection - Noksha')

@section('content')
<div class="min-h-screen bg-slate-950 text-slate-200">
    <!-- Header Hero Banner -->
    @include('legal.header', [
        'activePolicy' => 'privacy',
        'title' => 'Privacy Policy & Data Security',
        'subtitle' => 'Our commitment to protecting your personal identity, financial credentials, KYC documentation, and browsing privacy.'
    ])

    <!-- Main Container -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">

        <!-- Commitment Callout -->
        <div class="p-6 rounded-2xl bg-emerald-950/20 border border-emerald-500/30 backdrop-blur-sm shadow-xl flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div class="space-y-1 text-sm leading-relaxed">
                <h3 class="font-bold text-white text-base">Privacy by Design</h3>
                <p class="text-slate-300">
                    Noksha does not sell, rent, or trade your personal information to third-party advertisers. All collected data is used exclusively to facilitate account authentication, payment ledger accounting, KYC compliance, and asset delivery.
                </p>
            </div>
        </div>

        <!-- Section 1: Information We Collect -->
        <div class="p-6 sm:p-8 rounded-2xl bg-slate-900/70 border border-slate-800/90 backdrop-blur-sm shadow-xl space-y-5">
            <div class="border-b border-slate-800 pb-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-indigo-400">01.</span> Information We Collect
                </h2>
                <p class="text-xs font-mono text-slate-400 mt-1 uppercase tracking-wider">Data points required for platform operations</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs sm:text-sm text-slate-300">
                <div class="p-4 rounded-xl bg-slate-950/60 border border-slate-800/80 space-y-2">
                    <div class="font-bold text-indigo-400">Account Credentials</div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Full legal name, username, email address, hashed passwords, avatar image, and optional public bio / social links.
                    </p>
                </div>

                <div class="p-4 rounded-xl bg-slate-950/60 border border-slate-800/80 space-y-2">
                    <div class="font-bold text-indigo-400">Financial & Transaction Data</div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        bKash and Nagad sender account phone numbers, transaction IDs (TrxID), bank transfer routing details, deposit timestamps, and purchase order invoices.
                    </p>
                </div>

                <div class="p-4 rounded-xl bg-slate-950/60 border border-slate-800/80 space-y-2">
                    <div class="font-bold text-indigo-400">Contributor KYC Documents</div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        National ID (NID) card numbers, passport numbers, external design portfolio URLs, and scanned government identity verification files.
                    </p>
                </div>

                <div class="p-4 rounded-xl bg-slate-950/60 border border-slate-800/80 space-y-2">
                    <div class="font-bold text-indigo-400">Technical & Usage Logs</div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        IP addresses, browser user-agent, session identifiers, resource download logs, and canvas AI customizer interaction counts.
                    </p>
                </div>
            </div>
        </div>

        <!-- Section 2: Contributor KYC Security -->
        <div class="p-6 sm:p-8 rounded-2xl bg-slate-900/70 border border-slate-800/90 backdrop-blur-sm shadow-xl space-y-5">
            <div class="border-b border-slate-800 pb-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-indigo-400">02.</span> Contributor KYC Document Protection
                </h2>
                <p class="text-xs font-mono text-slate-400 mt-1 uppercase tracking-wider">Strict containment of sensitive government identity files</p>
            </div>

            <div class="space-y-4 text-xs sm:text-sm text-slate-300 leading-relaxed">
                <p>
                    We recognize that government-issued documents require the highest standard of confidentiality. When a creator uploads an NID or passport file for Contributor verification:
                </p>
                <div class="space-y-3">
                    <div class="flex items-start gap-2.5">
                        <span class="text-indigo-400 font-bold mt-0.5">•</span>
                        <span><strong>Encrypted & Isolated Storage:</strong> Verification documents are stored in protected disk directories, shielded from public web crawling, search engines, and indexing robots.</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="text-indigo-400 font-bold mt-0.5">•</span>
                        <span><strong>Restricted Compliance Access:</strong> Only authenticated compliance staff with Super Admin privileges can inspect KYC documents during the verification review.</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="text-indigo-400 font-bold mt-0.5">•</span>
                        <span><strong>Zero Commercial Sharing:</strong> Contributor identity records are never transferred to marketing partners, advertisers, or third parties under any circumstances.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: MFS Financial Privacy -->
        <div class="p-6 sm:p-8 rounded-2xl bg-slate-900/70 border border-slate-800/90 backdrop-blur-sm shadow-xl space-y-5">
            <div class="border-b border-slate-800 pb-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-indigo-400">03.</span> MFS Transaction & Payment Privacy
                </h2>
                <p class="text-xs font-mono text-slate-400 mt-1 uppercase tracking-wider">Protection of mobile financial service information</p>
            </div>

            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                Noksha utilizes manual MFS verification to prevent automated gateway leakage of customer payment instruments. When depositing funds via bKash or Nagad:
            </p>
            <ul class="text-xs sm:text-sm text-slate-400 space-y-2.5">
                <li>• Your mobile wallet phone number is used exclusively to cross-reference the incoming payment transaction.</li>
                <li>• Noksha does not store PINs, OTP codes, or banking secret keys. We will never ask you for your bKash or Nagad secret PIN.</li>
                <li>• Payout details (disbursement phone numbers and bank account routing) are accessible only to finance administrators to execute approved payouts.</li>
            </ul>
        </div>

        <!-- Section 4: Cookies & Local Storage -->
        <div class="p-6 sm:p-8 rounded-2xl bg-slate-900/70 border border-slate-800/90 backdrop-blur-sm shadow-xl space-y-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <span class="text-indigo-400">04.</span> Cookies & Local Browser Storage
            </h2>
            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                Noksha utilizes first-party session cookies and secure local storage strictly for essential platform functionality:
            </p>
            <div class="space-y-2 text-xs text-slate-400">
                <p>• <strong>Authentication Cookies:</strong> Keep your secure login session active across browser navigation (`remember_web`).</p>
                <p>• <strong>Security Tokens:</strong> CSRF tokens preventing Cross-Site Request Forgery on forms and checkout flows.</p>
                <p>• <strong>UI State:</strong> Caching notification seen states and canvas zoom presets to optimize browser performance.</p>
            </div>
            <p class="text-xs text-slate-500">
                We do not employ third-party tracking pixels or behavioral data brokers.
            </p>
        </div>

        <!-- Section 5: Data Rights -->
        <div class="p-6 sm:p-8 rounded-2xl bg-slate-900/70 border border-slate-800/90 backdrop-blur-sm shadow-xl space-y-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <span class="text-indigo-400">05.</span> Your Rights & Account Deletion
            </h2>
            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                You have the right to request a copy of your personal account data, update inaccurate information, or request account closure. To request data deletion or privacy inquiries, contact our data protection team via <a href="{{ route('contact.index') }}" class="text-indigo-400 underline font-semibold">our support portal</a>. Financial transaction ledgers will be retained for 5 years as required by anti-money laundering and tax accounting laws.
            </p>
        </div>

    </div>
</div>
@endsection
