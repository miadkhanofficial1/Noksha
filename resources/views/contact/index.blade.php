@extends('layouts.app')

@section('title', 'Contact Support & Help Center - Noksha')

@section('content')
<style>
    /* Dark Theme Support Styling */
    .contact-wrapper {
        background-color: #020617; /* slate-950 */
        color: #F1F5F9; /* slate-100 */
        min-height: calc(100vh - 70px);
    }

    .contact-hero-glow {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 600px;
        height: 350px;
        background: radial-gradient(circle, rgba(124, 58, 237, 0.22) 0%, rgba(79, 70, 229, 0.08) 50%, transparent 70%);
        pointer-events: none;
        z-index: 0;
    }

    /* Info Cards */
    .support-info-card {
        background: rgba(15, 23, 42, 0.7); /* slate-900 with glass */
        backdrop-filter: blur(12px);
        border: 1px solid rgba(148, 163, 184, 0.15);
        border-radius: 1.25rem;
        padding: 1.75rem;
        transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
        height: 100%;
    }

    .support-info-card:hover {
        transform: translateY(-4px);
        border-color: rgba(124, 58, 237, 0.4);
        box-shadow: 0 12px 30px -8px rgba(124, 58, 237, 0.25);
    }

    .support-icon-box {
        width: 52px;
        height: 52px;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        margin-bottom: 1.25rem;
    }

    /* Interactive Support Form Card */
    .support-form-card {
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(148, 163, 184, 0.18);
        border-radius: 1.5rem;
        padding: 2.25rem;
        box-shadow: 0 20px 45px -10px rgba(0, 0, 0, 0.5);
    }

    @media (min-width: 768px) {
        .support-form-card {
            padding: 3rem;
        }
    }

    .form-label-dark {
        font-size: 0.775rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #94A3B8; /* slate-400 */
        margin-bottom: 0.45rem;
        display: block;
    }

    .form-control-dark {
        background-color: #0F172A; /* slate-900 */
        border: 1.5px solid #334155; /* slate-700 */
        color: #F8FAFC !important;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        font-size: 0.925rem;
        transition: all 0.2s ease;
    }

    .form-control-dark:focus {
        background-color: #1E293B;
        border-color: #8B5CF6;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
        outline: none;
    }

    .form-control-dark::placeholder {
        color: #64748B;
    }

    /* Submit Button */
    .btn-submit-support {
        background: linear-gradient(135deg, #7C3AED 0%, #4F46E5 100%);
        color: #FFFFFF !important;
        border: none;
        border-radius: 50rem;
        padding: 0.85rem 2rem;
        font-weight: 700;
        font-size: 0.95rem;
        transition: all 0.25s ease;
        box-shadow: 0 4px 18px rgba(124, 58, 237, 0.35);
    }

    .btn-submit-support:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(124, 58, 237, 0.5);
    }
</style>

<div class="contact-wrapper position-relative py-5">
    <div class="contact-hero-glow"></div>

    <div class="container position-relative z-1" style="max-width: 1040px;">
        
        <!-- Header -->
        <div class="text-center mb-5">
            <span class="badge px-3 py-1.5 rounded-pill text-uppercase tracking-wider fw-bold extra-small mb-2" style="background: rgba(124, 58, 237, 0.15); color: #A78BFA; border: 1px solid rgba(124, 58, 237, 0.3);">
                <i class="bi bi-shield-check me-1"></i> Dedicated Support
            </span>
            <h1 class="fw-extrabold text-white mb-2 display-6">How Can We Help You?</h1>
            <p class="text-slate-400 small mx-auto" style="max-width: 560px; color: #94A3B8;">
                Have questions about design assets, contest escrow payouts, copyright claims, or AI credits? Our team is here to assist.
            </p>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-lg py-3 px-4 mb-4 d-flex align-items-center gap-3" style="background-color: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3) !important;" role="alert">
                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                <div class="fw-semibold text-emerald-300 small">{{ session('success') }}</div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-lg py-3 px-4 mb-4" style="background-color: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3) !important;" role="alert">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                    <strong class="small text-red-300">Please correct the following:</strong>
                </div>
                <ul class="mb-0 small text-red-300 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- 3 Info Cards -->
        <div class="row g-4 mb-5">
            <!-- Card 1: Support Email -->
            <div class="col-12 col-md-4">
                <div class="support-info-card">
                    <div class="support-icon-box" style="background: rgba(124, 58, 237, 0.15); color: #A78BFA;">
                        <i class="bi bi-envelope-open-fill"></i>
                    </div>
                    <h5 class="fw-bold text-white mb-1.5">Direct Support</h5>
                    <p class="text-slate-400 extra-small mb-3" style="color: #94A3B8;">
                        Reach out for order inquiries, download issues, or seller verification assistance.
                    </p>
                    <a href="mailto:support@noksha.com" class="text-decoration-none fw-bold small text-purple-400 d-inline-flex align-items-center gap-1" style="color: #A78BFA;">
                        <span>support@noksha.com</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 2: AI Assistance -->
            <div class="col-12 col-md-4">
                <div class="support-info-card">
                    <div class="support-icon-box" style="background: rgba(59, 130, 246, 0.15); color: #60A5FA;">
                        <i class="bi bi-cpu-fill"></i>
                    </div>
                    <h5 class="fw-bold text-white mb-1.5">AI Customizer Help</h5>
                    <p class="text-slate-400 extra-small mb-3" style="color: #94A3B8;">
                        Assistance with AI template vector generators, credit top-ups, and commercial licensing.
                    </p>
                    <span class="badge rounded-pill px-2.5 py-1 extra-small fw-semibold" style="background: rgba(59, 130, 246, 0.2); color: #93C5FD;">
                        <i class="bi bi-lightning-charge-fill me-1"></i> Instant AI Guidance
                    </span>
                </div>
            </div>

            <!-- Card 3: Headquarters -->
            <div class="col-12 col-md-4">
                <div class="support-info-card">
                    <div class="support-icon-box" style="background: rgba(16, 185, 129, 0.15); color: #34D399;">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <h5 class="fw-bold text-white mb-1.5">Global Headquarters</h5>
                    <p class="text-slate-400 extra-small mb-3" style="color: #94A3B8;">
                        Noksha Creative Studio Hub & Marketplace Operations, Dhaka, Bangladesh.
                    </p>
                    <span class="text-slate-300 font-monospace extra-small" style="color: #CBD5E1;">
                        <i class="bi bi-globe2 me-1 text-emerald-400"></i> Serving Creators Globally
                    </span>
                </div>
            </div>
        </div>

        <!-- Support Message Form -->
        <div class="support-form-card mb-4">
            <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom" style="border-color: rgba(148, 163, 184, 0.15) !important;">
                <i class="bi bi-chat-left-dots-fill fs-4" style="color: #A78BFA;"></i>
                <div>
                    <h4 class="fw-bold text-white mb-0">Send Us a Message</h4>
                    <span class="extra-small" style="color: #94A3B8;">Typical response time: within 2 to 4 business hours.</span>
                </div>
            </div>

            <form action="{{ route('contact.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <!-- Name -->
                    <div class="col-12 col-md-6">
                        <label for="inputName" class="form-label-dark">Your Full Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="name" 
                               id="inputName" 
                               class="form-control form-control-dark @error('name') is-invalid @enderror" 
                               value="{{ old('name', auth()->check() ? auth()->user()->name : '') }}" 
                               required 
                               placeholder="e.g. Miad Khan">
                        @error('name')
                            <div class="text-danger extra-small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-12 col-md-6">
                        <label for="inputEmail" class="form-label-dark">Email Address <span class="text-danger">*</span></label>
                        <input type="email" 
                               name="email" 
                               id="inputEmail" 
                               class="form-control form-control-dark @error('email') is-invalid @enderror" 
                               value="{{ old('email', auth()->check() ? auth()->user()->email : '') }}" 
                               required 
                               placeholder="name@example.com">
                        @error('email')
                            <div class="text-danger extra-small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Subject Dropdown -->
                    <div class="col-12">
                        <label for="selectSubject" class="form-label-dark">Inquiry Subject <span class="text-danger">*</span></label>
                        <select name="subject" id="selectSubject" class="form-select form-control-dark @error('subject') is-invalid @enderror" required>
                            <option value="" disabled {{ old('subject') ? '' : 'selected' }}>Select an inquiry topic...</option>
                            <option value="General Inquiry" {{ old('subject') === 'General Inquiry' ? 'selected' : '' }}>General Inquiry & Platform Assistance</option>
                            <option value="AI Credits & Customizer" {{ old('subject') === 'AI Credits & Customizer' ? 'selected' : '' }}>AI Credits & Customizer Assistance</option>
                            <option value="Design Contests & Escrow" {{ old('subject') === 'Design Contests & Escrow' ? 'selected' : '' }}>Design Contests, Bounties & Escrow Releases</option>
                            <option value="Copyright & Intellectual Property" {{ old('subject') === 'Copyright & Intellectual Property' ? 'selected' : '' }}>Copyright & Intellectual Property Infringement</option>
                            <option value="Billing & Payouts" {{ old('subject') === 'Billing & Payouts' ? 'selected' : '' }}>Billing, Wallets & Contributor Payouts</option>
                        </select>
                        @error('subject')
                            <div class="text-danger extra-small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Message -->
                    <div class="col-12">
                        <label for="inputMessage" class="form-label-dark">Message Details <span class="text-danger">*</span></label>
                        <textarea name="message" 
                                  id="inputMessage" 
                                  rows="5" 
                                  class="form-control form-control-dark @error('message') is-invalid @enderror" 
                                  required 
                                  maxlength="3000"
                                  placeholder="Describe your question, issue, or feedback in detail...">{{ old('message') }}</textarea>
                        @error('message')
                            <div class="text-danger extra-small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-submit-support d-inline-flex align-items-center gap-2">
                            <i class="bi bi-send-fill"></i>
                            <span>Send Support Message</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
