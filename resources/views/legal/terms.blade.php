@extends('layouts.app')

@section('title', 'Terms of Service & Platform Agreement - Noksha')

@section('content')
<div class="min-h-screen bg-slate-950 text-slate-200">
    <!-- Header Hero Banner -->
    @include('legal.header', [
        'activePolicy' => 'terms',
        'title' => 'Terms of Service & Contributor Agreement',
        'subtitle' => 'The legally binding platform agreement outlining rights, conduct, intellectual property protection, and commercial terms on Noksha.'
    ])

    <!-- Main Container -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">

        <!-- Introduction Notice -->
        <div class="p-6 rounded-2xl bg-slate-900/80 border border-slate-800/90 backdrop-blur-sm shadow-xl space-y-3">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <span class="text-indigo-400">01.</span> Agreement to Terms
            </h2>
            <p class="text-sm text-slate-300 leading-relaxed">
                By registering an account, purchasing digital assets, participating in design contests, or applying as a verified contributor on Noksha, you explicitly agree to be bound by these Terms of Service. If you do not agree with any provision of these terms, you must discontinue using the platform immediately.
            </p>
            <p class="text-xs text-slate-400">
                These terms are governed by the laws of the People’s Republic of Bangladesh and relevant international intellectual property treaties.
            </p>
        </div>

        <!-- Section 2: Contributor IP & Representations -->
        <div class="p-6 sm:p-8 rounded-2xl bg-slate-900/70 border border-slate-800/90 backdrop-blur-sm shadow-xl space-y-5">
            <div class="border-b border-slate-800 pb-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-indigo-400">02.</span> Contributor Rights & Intellectual Property Warranties
                </h2>
                <p class="text-xs font-mono text-slate-400 mt-1 uppercase tracking-wider">How creator ownership works on Noksha</p>
            </div>

            <div class="space-y-4 text-sm text-slate-300 leading-relaxed">
                <div class="p-4 rounded-xl bg-slate-950/60 border border-slate-800/80 space-y-2">
                    <h3 class="text-sm font-bold text-indigo-300">Creator Retains Ownership</h3>
                    <p class="text-xs text-slate-400">
                        When you upload a design template to Noksha as an approved Contributor, you retain 100% of your copyright, moral rights, and underlying intellectual property. You do not surrender ownership to Noksha.
                    </p>
                </div>

                <div class="p-4 rounded-xl bg-slate-950/60 border border-slate-800/80 space-y-2">
                    <h3 class="text-sm font-bold text-indigo-300">Non-Exclusive Platform Distribution License</h3>
                    <p class="text-xs text-slate-400">
                        By submitting assets, you grant Noksha a worldwide, non-exclusive, sublicensable license to host, display, preview, watermarked-render, promote, and distribute your templates to buyers under our licensing tiers. Because our license is non-exclusive, you remain free to sell your work elsewhere.
                    </p>
                </div>

                <div class="p-4 rounded-xl bg-slate-950/60 border border-slate-800/80 space-y-2">
                    <h3 class="text-sm font-bold text-indigo-300">Warranties Against Plagiarism & Infringement</h3>
                    <p class="text-xs text-slate-400">
                        Every contributor warrants that uploaded content is their original creation and does not infringe upon any third party's copyright, trademark, patent, or proprietary rights. <strong>Uploading scraped, rip-off, or stolen templates from Freepik, Envato, Canva, or other creators is strictly prohibited and results in immediate permanent ban and forfeiture of pending royalties.</strong>
                    </p>
                </div>
            </div>
        </div>

        <!-- Section 3: Revenue Split & Commissions -->
        <div class="p-6 sm:p-8 rounded-2xl bg-slate-900/70 border border-slate-800/90 backdrop-blur-sm shadow-xl space-y-5">
            <div class="border-b border-slate-800 pb-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-indigo-400">03.</span> Commercial Model & Royalties (85 / 15 Split)
                </h2>
                <p class="text-xs font-mono text-slate-400 mt-1 uppercase tracking-wider">Creator-first remuneration economics</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-5 rounded-xl bg-emerald-950/20 border border-emerald-500/30 space-y-2">
                    <div class="text-2xl font-black text-emerald-400">85% Net Royalty</div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Contributors receive 85% of the gross sale price for every marketplace template license purchase. Royalties are credited immediately into the seller's withdrawable <code class="text-emerald-300">earnings_balance</code>.
                    </p>
                </div>

                <div class="p-5 rounded-xl bg-indigo-950/20 border border-indigo-500/30 space-y-2">
                    <div class="text-2xl font-black text-indigo-400">15% Platform Commission</div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Noksha retains 15% to cover payment gateway interchange costs, cloud infrastructure, AI model serving, automated security audits, and continuous platform development.
                    </p>
                </div>
            </div>
            <p class="text-xs text-slate-400 italic">
                * Note: In design contests, 100% of the guaranteed prize bounty is awarded to the selected winner without platform percentage deductions, funded by the contest organizer's pre-paid escrow.
            </p>
        </div>

        <!-- Section 4: Account Conduct & Security -->
        <div class="p-6 sm:p-8 rounded-2xl bg-slate-900/70 border border-slate-800/90 backdrop-blur-sm shadow-xl space-y-5">
            <div class="border-b border-slate-800 pb-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-indigo-400">04.</span> Account Conduct & User Responsibilities
                </h2>
                <p class="text-xs font-mono text-slate-400 mt-1 uppercase tracking-wider">Rules of the Noksha creative community</p>
            </div>

            <ul class="text-xs sm:text-sm text-slate-300 space-y-3 leading-relaxed">
                <li class="flex items-start gap-2.5">
                    <span class="text-indigo-400 font-bold mt-0.5">•</span>
                    <span><strong>Accurate KYC Information:</strong> You must supply authentic legal identity credentials (NID or Passport) when applying for Contributor status. Creating duplicate or sockpuppet accounts to evade moderation is prohibited.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="text-indigo-400 font-bold mt-0.5">•</span>
                    <span><strong>MFS & Payment Integrity:</strong> Submitting fake bKash / Nagad Transaction IDs (TrxID) or filing fraudulent deposit claims is considered criminal financial fraud. Fraudulent accounts will be blacklisted and reported.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="text-indigo-400 font-bold mt-0.5">•</span>
                    <span><strong>No Malicious Content:</strong> You may not upload zip archives containing trojans, ransomware, macros, cryptominers, or executable binaries (`.exe`, `.bat`, `.scr`).</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="text-indigo-400 font-bold mt-0.5">•</span>
                    <span><strong>Fair Reviews & Feedback:</strong> Product reviews must represent genuine customer experience. Extortion, harassment, review manipulation, or retaliation against designers is strictly banned.</span>
                </li>
            </ul>
        </div>

        <!-- Section 5: Termination & Takedowns -->
        <div class="p-6 sm:p-8 rounded-2xl bg-slate-900/70 border border-slate-800/90 backdrop-blur-sm shadow-xl space-y-5">
            <div class="border-b border-slate-800 pb-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-indigo-400">05.</span> DMCA Takedown & Account Termination
                </h2>
                <p class="text-xs font-mono text-slate-400 mt-1 uppercase tracking-wider">Copyright enforcement and safety measures</p>
            </div>

            <div class="space-y-4 text-xs sm:text-sm text-slate-300 leading-relaxed">
                <p>
                    Noksha adheres to notice-and-takedown procedures consistent with the Digital Millennium Copyright Act (DMCA) and Bangladesh Copyright Act. If you believe your copyrighted work has been improperly uploaded without authorization, you may file a formal takedown notice with our compliance team via <a href="{{ route('contact.index') }}" class="text-indigo-400 underline font-semibold">our contact portal</a> with proof of original work and URLs.
                </p>
                <div class="p-4 rounded-xl bg-rose-950/20 border border-rose-500/30 text-rose-300 text-xs space-y-1">
                    <div class="font-bold">Three-Strike Policy</div>
                    <div>A contributor receiving three verified copyright strikes will have their creator privileges terminated permanently and all active listings unpublished.</div>
                </div>
            </div>
        </div>

        <!-- Section 6: Limitation of Liability -->
        <div class="p-6 sm:p-8 rounded-2xl bg-slate-900/70 border border-slate-800/90 backdrop-blur-sm shadow-xl space-y-3">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <span class="text-indigo-400">06.</span> Disclaimers & Limitation of Liability
            </h2>
            <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                Noksha provides the marketplace platform on an "as is" and "as available" basis. While we conduct automated virus scans and manual KYC checks, Noksha shall not be liable for indirect, incidental, or consequential damages resulting from asset downtime, software incompatibility, or font licensing disputes between buyers and external font foundries.
            </p>
        </div>

    </div>
</div>
@endsection
