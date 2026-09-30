@extends('layouts.app')

@section('title', 'Refund Policy, Escrow Disputes & Payout Rules - Noksha')

@section('content')
<div class="min-h-screen bg-slate-950 text-slate-200">
    <!-- Header Hero Banner -->
    @include('legal.header', [
        'activePolicy' => 'refunds',
        'title' => 'Refund Policy & Escrow Disputes',
        'subtitle' => 'Rules governing digital asset purchase refunds, freelancer contest escrow protection, dispute arbitration, and seller cashout conditions.'
    ])

    <!-- Main Container -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">

        <!-- Core Notice -->
        <div class="p-6 rounded-2xl bg-amber-950/20 border border-amber-500/30 backdrop-blur-sm shadow-xl flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div class="space-y-1 text-sm leading-relaxed">
                <h3 class="font-bold text-white text-base">Digital Goods Standard</h3>
                <p class="text-slate-300">
                    Because digital graphics templates (`.psd`, `.ai`, `.eps`, `.fig`) are irrevocable intangible digital goods, <strong>all purchases are non-refundable once the source asset file has been accessed or downloaded</strong>, except in verified cases of technical corruption, listing misrepresentation, or duplicate billing.
                </p>
            </div>
        </div>

        <!-- Section 1: Marketplace Purchases Refund Policy -->
        <div class="p-6 sm:p-8 rounded-2xl bg-slate-900/70 border border-slate-800/90 backdrop-blur-sm shadow-xl space-y-5">
            <div class="border-b border-slate-800 pb-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-indigo-400">01.</span> Template Purchases & Eligible Refund Conditions
                </h2>
                <p class="text-xs font-mono text-slate-400 mt-1 uppercase tracking-wider">When a refund will or will not be issued</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Eligible Cases -->
                <div class="p-5 rounded-xl bg-emerald-950/20 border border-emerald-500/30 space-y-3">
                    <div class="flex items-center gap-2 text-emerald-400 font-bold text-sm">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Eligible for Full Refund or Wallet Credit</span>
                    </div>
                    <ul class="text-xs text-slate-300 space-y-2.5 leading-relaxed">
                        <li>• <strong>Corrupted Archive:</strong> The zip file is demonstrably corrupt, truncated, or unopenable, and the creator fails to provide a working copy within 48 hours.</li>
                        <li>• <strong>Material Misrepresentation:</strong> The downloaded file is materially different from the preview listing (e.g. listed as vector `.ai` but contains only a low-res JPEG).</li>
                        <li>• <strong>Technical Non-Delivery:</strong> You were charged but a server error prevented the download link from generating, verified via server logs.</li>
                        <li>• <strong>Duplicate Payment:</strong> A technical glitch resulted in a double charge for the same item order.</li>
                    </ul>
                </div>

                <!-- Non-Eligible Cases -->
                <div class="p-5 rounded-xl bg-rose-950/20 border border-rose-500/30 space-y-3">
                    <div class="flex items-center gap-2 text-rose-400 font-bold text-sm">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Non-Refundable Scenarios</span>
                    </div>
                    <ul class="text-xs text-slate-300 space-y-2.5 leading-relaxed">
                        <li>• <strong>Change of Mind:</strong> You simply changed your mind or no longer need the asset after completing the download.</li>
                        <li>• <strong>Missing Software:</strong> You lack the requisite professional software (Adobe Photoshop, Illustrator, Figma) or technical skill to edit the file.</li>
                        <li>• <strong>Downloaded Source:</strong> You downloaded the raw source files and have already utilized assets in client or personal work.</li>
                        <li>• <strong>AI Customizer Usage:</strong> AI Credits deducted for prompt generation or canvas editing cannot be refunded once rendered.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Section 2: Freelancer Contest Escrow & Disputes -->
        <div class="p-6 sm:p-8 rounded-2xl bg-slate-900/70 border border-slate-800/90 backdrop-blur-sm shadow-xl space-y-5">
            <div class="border-b border-slate-800 pb-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-indigo-400">02.</span> Design Contest Escrow & Dispute Resolution
                </h2>
                <p class="text-xs font-mono text-slate-400 mt-1 uppercase tracking-wider">How contest prize security is guaranteed</p>
            </div>

            <div class="space-y-4 text-xs sm:text-sm text-slate-300 leading-relaxed">
                <div class="p-4 rounded-xl bg-slate-950/60 border border-slate-800/80 space-y-2">
                    <h3 class="text-sm font-bold text-indigo-300">100% Guaranteed Escrow</h3>
                    <p class="text-xs text-slate-400">
                        When a contest organizer launches a design contest, 100% of the prize pool bounty is pre-funded and held securely in Noksha's platform escrow. The organizer cannot withdraw or cancel a guaranteed contest once valid designer submissions have been posted.
                    </p>
                </div>

                <div class="p-4 rounded-xl bg-slate-950/60 border border-slate-800/80 space-y-2">
                    <h3 class="text-sm font-bold text-indigo-300">Source Handover & Revision Protocol</h3>
                    <p class="text-xs text-slate-400">
                        Upon winner selection, the winning designer must upload original vector deliverables (`.ai`, `.eps`, `.psd`, fonts info) within the handover portal. The organizer has the right to request up to <strong>3 minor revisions</strong> (color tweaks, text sizing, export formats) within 72 hours.
                    </p>
                </div>

                <div class="p-4 rounded-xl bg-slate-950/60 border border-slate-800/80 space-y-2">
                    <h3 class="text-sm font-bold text-indigo-300">Platform Arbitration on Handover Disputes</h3>
                    <p class="text-xs text-slate-400">
                        If a designer fails to provide production-ready source files, or if an organizer unreasonably refuses to release escrow despite receiving compliant files, either party can initiate a dispute. Our design compliance team will arbitrate within 48 hours. Escrow release decisions made by arbitration are final.
                    </p>
                </div>
            </div>
        </div>

        <!-- Section 3: Seller Payouts & Disbursal Conditions -->
        <div class="p-6 sm:p-8 rounded-2xl bg-slate-900/70 border border-slate-800/90 backdrop-blur-sm shadow-xl space-y-5">
            <div class="border-b border-slate-800 pb-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-indigo-400">03.</span> Seller Earnings Payout & Disbursal Rules
                </h2>
                <p class="text-xs font-mono text-slate-400 mt-1 uppercase tracking-wider">Conditions for cashing out creator revenues</p>
            </div>

            <div class="space-y-4 text-xs sm:text-sm text-slate-300 leading-relaxed">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl bg-slate-950/60 border border-slate-800 text-center">
                        <div class="text-lg font-bold text-emerald-400">৳ 500 BDT</div>
                        <div class="text-[11px] text-slate-400 mt-1">Minimum Payout Threshold</div>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-950/60 border border-slate-800 text-center">
                        <div class="text-lg font-bold text-indigo-400">24 – 48 Hours</div>
                        <div class="text-[11px] text-slate-400 mt-1">Standard Clearing Window</div>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-950/60 border border-slate-800 text-center">
                        <div class="text-lg font-bold text-purple-400">0% Cashout Fee</div>
                        <div class="text-[11px] text-slate-400 mt-1">bKash & Nagad Direct Transfers</div>
                    </div>
                </div>

                <div class="space-y-3 pt-2">
                    <div class="flex items-start gap-2.5">
                        <span class="text-emerald-400 font-bold mt-0.5">•</span>
                        <span><strong>Earnings vs. Wallet Balance Isolation:</strong> Only royalties earned through verified template sales (`earnings_balance`) can be cashed out. Wallet funds deposited by a user to purchase items (`wallet_balance`) cannot be withdrawn under any circumstances.</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="text-emerald-400 font-bold mt-0.5">•</span>
                        <span><strong>KYC Approval Prerequisite:</strong> A seller must hold an <em>Approved Contributor</em> status with verified National ID/Passport to request payouts.</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="text-emerald-400 font-bold mt-0.5">•</span>
                        <span><strong>MFS Account Verification:</strong> Payout mobile numbers must be personal bKash or Nagad accounts registered under the contributor's own name.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 4: How to File a Claim -->
        <div class="p-6 sm:p-8 rounded-2xl bg-slate-900/70 border border-slate-800/90 backdrop-blur-sm shadow-xl space-y-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <span class="text-indigo-400">04.</span> How to Request a Refund or File an Inquiry
            </h2>
            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                To request a refund for an eligible technical issue, contact our support desk within <strong>7 days</strong> of order completion via <a href="{{ route('contact.index') }}" class="text-indigo-400 underline font-semibold">Support Contact Form</a>. Please include:
            </p>
            <ul class="text-xs sm:text-sm text-slate-400 space-y-1.5 pl-4 list-disc">
                <li>Your Noksha account email and Order Number (`#ORD-XXXXX`).</li>
                <li>The specific template title and creator name.</li>
                <li>A clear description and screenshot showing the file corruption or listing error.</li>
            </ul>
        </div>

    </div>
</div>
@endsection
