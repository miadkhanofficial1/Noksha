<!-- GLOBAL LIGHT FOOTER -->
<footer class="bg-[#f8fafc] text-slate-600 pt-5 pb-4 mt-auto border-t border-slate-200">
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
                <p class="text-slate-600 small mb-3 leading-relaxed">
                    Noksha is an AI-powered creative graphics template marketplace and freelancer design contest platform connecting designers, verified contributors, and buyers with automated tagging, intelligent search, and high-quality creative assets.
                </p>
                <div class="d-flex gap-3 text-slate-500 fs-5">
                    <a href="#" class="text-slate-500 hover:text-[#20165c] transition"><i class="bi bi-github"></i></a>
                    <a href="#" class="text-slate-500 hover:text-[#20165c] transition"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="text-slate-500 hover:text-[#20165c] transition"><i class="bi bi-linkedin"></i></a>
                    <a href="#" class="text-slate-500 hover:text-[#20165c] transition"><i class="bi bi-discord"></i></a>
                </div>
            </div>

            <!-- Column 2: Marketplace & Discovery -->
            <div class="col-lg-2 col-md-6">
                <h6 class="text-uppercase fw-bold text-[#121826] mb-3 tracking-wider extra-small font-monospace">Marketplace</h6>
                <ul class="list-unstyled text-slate-600 small d-grid gap-2">
                    <li><a href="{{ route('templates.index') }}" class="text-slate-600 text-decoration-none hover:text-[#20165c] transition">All Templates</a></li>
                    <li><a href="{{ route('creators.index') }}" class="text-slate-600 text-decoration-none hover:text-[#20165c] transition">Top Creators</a></li>
                    <li><a href="{{ route('contests.index') }}" class="text-slate-600 text-decoration-none hover:text-[#20165c] transition">Design Contests</a></li>
                    <li><a href="{{ route('wallet.index') }}" class="text-slate-600 text-decoration-none hover:text-[#20165c] transition">Central Wallet</a></li>
                    <li><a href="{{ route('contributor.apply') }}" class="text-slate-600 text-decoration-none hover:text-[#20165c] transition">Become a Contributor</a></li>
                </ul>
            </div>

            <!-- Column 3: Legal & Trust -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-uppercase fw-bold text-[#121826] mb-3 tracking-wider extra-small font-monospace">Legal & Trust</h6>
                <ul class="list-unstyled text-slate-600 small d-grid gap-2">
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
                            <span>Refund & Payout Policy</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 4: Help & Support -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-uppercase fw-bold text-[#121826] mb-3 tracking-wider extra-small font-monospace">Help & Support</h6>
                <p class="text-slate-600 small mb-2">
                    <a href="{{ route('help.index') }}" class="text-slate-600 hover:text-[#20165c] text-decoration-none d-inline-flex align-items-center gap-1.5 transition">
                        <i class="bi bi-question-circle-fill text-[#20165c]"></i>
                        <span>Help Center & FAQ</span>
                    </a>
                </p>
                <p class="text-slate-600 small mb-2">
                    <a href="{{ route('contact.index') }}" class="text-[#20165c] hover:text-[#181045] text-decoration-none fw-semibold d-inline-flex align-items-center gap-1 transition">
                        <i class="bi bi-envelope-fill me-1"></i> Contact Support Desk &rarr;
                    </a>
                </p>
                <p class="text-slate-600 small mb-2">
                    <i class="bi bi-shield-lock-fill me-1 text-emerald-600"></i> Escrow Protected & Verified KYC
                </p>
                <p class="text-slate-500 extra-small mb-0">
                    <i class="bi bi-code-slash me-1 text-[#20165c]"></i> Built with Laravel 12, Tailwind CSS & HTML5 Canvas
                </p>
            </div>
        </div>

        <hr class="border-slate-200 opacity-100 my-4">

        <!-- Bottom Copyright & Legal Links -->
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between text-slate-500 extra-small gap-3">
            <div>
                &copy; {{ date('Y') }} <strong>Noksha</strong>. All rights reserved. Creative Marketplace Platform.
            </div>
            <div class="d-flex align-items-center flex-wrap gap-3">
                <a href="{{ route('legal.licenses') }}" class="text-slate-600 text-decoration-none hover:text-[#20165c] transition">Licenses</a>
                <span>•</span>
                <a href="{{ route('legal.terms') }}" class="text-slate-600 text-decoration-none hover:text-[#20165c] transition">Terms of Service</a>
                <span>•</span>
                <a href="{{ route('legal.privacy') }}" class="text-slate-600 text-decoration-none hover:text-[#20165c] transition">Privacy Policy</a>
                <span>•</span>
                <a href="{{ route('legal.refunds') }}" class="text-slate-600 text-decoration-none hover:text-[#20165c] transition">Refunds & Escrow</a>
                <span>•</span>
                <a href="{{ route('help.index') }}" class="text-slate-600 text-decoration-none hover:text-[#20165c] transition">Help & FAQ</a>
            </div>
        </div>
    </div>
</footer>
