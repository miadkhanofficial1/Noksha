<!-- GLOBAL LIGHT FOOTER -->
<footer class="bg-[#f8fafc] text-slate-600 border-t border-slate-200 mt-auto">
    <style>
        .footer-social-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #64748b;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            font-size: 0.95rem;
        }
        .footer-social-btn:hover {
            background: #20165c;
            color: #ffffff !important;
            border-color: #20165c;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(32, 22, 92, 0.15);
        }
    </style>
    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
            <!-- Column 1 (md:col-span-5): Brand Info -->
            <div class="md:col-span-5">
                <div class="flex items-center gap-2 mb-3">
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="Noksha" 
                         class="noksha-brand-logo h-10 w-auto object-contain shrink-0"
                         height="40"
                         style="height: 40px; width: auto; max-height: 40px; object-fit: contain; display: block;">
                    <span class="font-bold text-2xl text-[#121826] whitespace-nowrap tracking-tight">
                        Noksha
                    </span>
                </div>
                <p class="text-slate-600 text-sm leading-relaxed max-w-xs">
                    Bangladesh's premier creative marketplace to discover design assets, hire verified creators, and run design contests.
                </p>
                <div class="flex items-center gap-3 mt-5">
                    <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="footer-social-btn" title="Facebook" aria-label="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="footer-social-btn" title="Instagram" aria-label="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" class="footer-social-btn" title="LinkedIn" aria-label="LinkedIn">
                        <i class="bi bi-linkedin"></i>
                    </a>
                    <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" class="footer-social-btn" title="X (Twitter)" aria-label="X (Twitter)">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                </div>
            </div>

            <!-- Column 2 (md:col-span-2): Marketplace -->
            <div class="md:col-span-2">
                <h6 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-4 font-mono">Marketplace</h6>
                <ul class="list-none p-0 m-0 text-slate-600 text-sm space-y-2.5">
                    <li><a href="{{ route('templates.index') }}" class="text-slate-600 hover:text-[#20165c] transition-colors no-underline">All Templates</a></li>
                    <li><a href="{{ route('services.index') }}" class="text-slate-600 hover:text-[#20165c] transition-colors no-underline">Services</a></li>
                    <li><a href="{{ route('creators.index') }}" class="text-slate-600 hover:text-[#20165c] transition-colors no-underline">Top Creators</a></li>
                    <li><a href="{{ route('contests.index') }}" class="text-slate-600 hover:text-[#20165c] transition-colors no-underline">Design Contests</a></li>
                    <li><a href="{{ route('contributor.apply') }}" class="text-slate-600 hover:text-[#20165c] transition-colors no-underline">Become a Contributor</a></li>
                </ul>
            </div>

            <!-- Column 3 (md:col-span-3): Legal & Trust -->
            <div class="md:col-span-3">
                <h6 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-4 font-mono">Legal &amp; Trust</h6>
                <ul class="list-none p-0 m-0 text-slate-600 text-sm space-y-2.5">
                    <li>
                        <a href="{{ route('legal.licenses') }}" class="text-slate-600 hover:text-[#20165c] transition-colors no-underline inline-flex items-center gap-2">
                            <i class="bi bi-file-earmark-text text-[#20165c]"></i>
                            <span>Licensing Terms</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('legal.terms') }}" class="text-slate-600 hover:text-[#20165c] transition-colors no-underline inline-flex items-center gap-2">
                            <i class="bi bi-shield-check text-[#20165c]"></i>
                            <span>Terms of Service</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('legal.privacy') }}" class="text-slate-600 hover:text-[#20165c] transition-colors no-underline inline-flex items-center gap-2">
                            <i class="bi bi-lock-fill text-emerald-600"></i>
                            <span>Privacy Policy</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('legal.refunds') }}" class="text-slate-600 hover:text-[#20165c] transition-colors no-underline inline-flex items-center gap-2">
                            <i class="bi bi-arrow-counterclockwise text-[#f47402]"></i>
                            <span>Refund &amp; Escrow Policy</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 4 (md:col-span-2): Help & Support -->
            <div class="md:col-span-2">
                <h6 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-4 font-mono">Help &amp; Support</h6>
                <ul class="list-none p-0 m-0 text-slate-600 text-sm space-y-2.5">
                    <li>
                        <a href="{{ route('help.index') }}" class="text-slate-600 hover:text-[#20165c] transition-colors no-underline inline-flex items-center gap-2">
                            <i class="bi bi-question-circle-fill text-[#20165c]"></i>
                            <span>Help Center &amp; FAQ</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact.index') }}" class="text-[#20165c] hover:text-[#181045] font-semibold transition-colors no-underline inline-flex items-center gap-2">
                            <i class="bi bi-envelope-fill text-[#20165c]"></i>
                            <span>Contact Support</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom Copyright Bar -->
        <div class="border-t border-slate-200 mt-10 pt-6 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-500 gap-3">
            <div>
                &copy; {{ date('Y') }} <strong>Noksha</strong>. All rights reserved.
            </div>
            <div>
                Empowering Creative Professionals
            </div>
        </div>
    </div>
</footer>
