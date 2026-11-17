<!-- GLOBAL LIGHT FOOTER -->
<footer class="bg-[#f8fafc] text-slate-600 pt-5 pb-4 mt-auto border-t border-slate-200">
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
    <div class="container mx-auto px-4">
        <div class="row g-4 mb-4">
            <!-- Column 1: Brand Info -->
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="Noksha" 
                         class="noksha-brand-logo h-10 w-auto object-contain flex-shrink-0"
                         height="40"
                         style="height: 40px; width: auto; max-height: 40px; object-fit: contain; display: block;">
                    <span class="fw-bold fs-4 text-[#121826] text-nowrap tracking-tight">
                        Noksha
                    </span>
                </div>
                <p class="text-slate-600 small mb-3 leading-relaxed" style="max-width: 360px;">
                    Bangladesh's premier creative marketplace to discover design assets, hire verified creators, and run design contests.
                </p>
                <div class="d-flex align-items-center gap-2">
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

            <!-- Column 2: Marketplace -->
            <div class="col-lg-2 col-md-6">
                <h6 class="text-uppercase fw-bold text-[#121826] mb-3 tracking-wider extra-small font-monospace">Marketplace</h6>
                <ul class="list-unstyled text-slate-600 small d-grid gap-2 mb-0">
                    <li><a href="{{ route('templates.index') }}" class="text-slate-600 text-decoration-none hover:text-[#20165c] transition">All Templates</a></li>
                    <li><a href="{{ route('services.index') }}" class="text-slate-600 text-decoration-none hover:text-[#20165c] transition">Services</a></li>
                    <li><a href="{{ route('creators.index') }}" class="text-slate-600 text-decoration-none hover:text-[#20165c] transition">Top Creators</a></li>
                    <li><a href="{{ route('contests.index') }}" class="text-slate-600 text-decoration-none hover:text-[#20165c] transition">Design Contests</a></li>
                    <li><a href="{{ route('contributor.apply') }}" class="text-slate-600 text-decoration-none hover:text-[#20165c] transition">Become a Contributor</a></li>
                </ul>
            </div>

            <!-- Column 3: Legal & Trust -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-uppercase fw-bold text-[#121826] mb-3 tracking-wider extra-small font-monospace">Legal & Trust</h6>
                <ul class="list-unstyled text-slate-600 small d-grid gap-2 mb-0">
                    <li>
                        <a href="{{ route('legal.licenses') }}" class="text-slate-600 text-decoration-none hover:text-[#20165c] transition d-inline-flex align-items-center gap-1.5">
                            <i class="bi bi-file-earmark-text text-[#20165c]"></i>
                            <span>Licensing Terms</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('legal.terms') }}" class="text-slate-600 text-decoration-none hover:text-[#20165c] transition d-inline-flex align-items-center gap-1.5">
                            <i class="bi bi-shield-check text-[#20165c]"></i>
                            <span>Terms of Service</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('legal.privacy') }}" class="text-slate-600 text-decoration-none hover:text-[#20165c] transition d-inline-flex align-items-center gap-1.5">
                            <i class="bi bi-lock-fill text-emerald-600"></i>
                            <span>Privacy Policy</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('legal.refunds') }}" class="text-slate-600 text-decoration-none hover:text-[#20165c] transition d-inline-flex align-items-center gap-1.5">
                            <i class="bi bi-arrow-counterclockwise text-[#f47402]"></i>
                            <span>Refund & Escrow Policy</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 4: Help & Support -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-uppercase fw-bold text-[#121826] mb-3 tracking-wider extra-small font-monospace">Help & Support</h6>
                <ul class="list-unstyled text-slate-600 small d-grid gap-2 mb-0">
                    <li>
                        <a href="{{ route('help.index') }}" class="text-slate-600 hover:text-[#20165c] text-decoration-none d-inline-flex align-items-center gap-1.5 transition">
                            <i class="bi bi-question-circle-fill text-[#20165c]"></i>
                            <span>Help Center & FAQ</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact.index') }}" class="text-[#20165c] hover:text-[#181045] text-decoration-none fw-semibold d-inline-flex align-items-center gap-1.5 transition">
                            <i class="bi bi-envelope-fill text-[#20165c]"></i>
                            <span>Contact Support</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <hr class="border-slate-200 opacity-100 my-4">

        <!-- Bottom Copyright Bar -->
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between text-slate-500 extra-small gap-3">
            <div>
                &copy; {{ date('Y') }} <strong>Noksha</strong>. All rights reserved.
            </div>
        </div>
    </div>
</footer>
