@extends('layouts.app')

@section('title', 'Launch a Design Contest - Noksha')

@section('content')
<div class="py-4 py-lg-5" style="background: linear-gradient(180deg, #F8F5FF 0%, #FFFFFF 100%); min-height: 100vh;">
    <div class="container max-w-4xl mx-auto py-2">
        
        <!-- BREADCRUMB -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small fw-semibold text-muted mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-primary"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contests.index') }}" class="text-decoration-none text-primary">Contests</a></li>
                <li class="breadcrumb-item active text-dark" aria-current="page">Launch Contest</li>
            </ol>
        </nav>

        <!-- FLASH ERROR MESSAGES -->
        @if ($errors->any())
            <div class="alert alert-danger rounded-4 shadow-sm border-0 p-3 mb-4">
                <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1.5"></i>Please resolve the following errors:</div>
                <ul class="mb-0 small ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- MAIN CARD -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="border: 1px solid rgba(124, 58, 237, 0.12) !important;">
            
            <!-- HEADER -->
            <div class="p-4 p-md-5 text-white" style="background: linear-gradient(135deg, #1E1B4B 0%, #312E81 50%, #4338CA 100%);">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-extrabold extra-small text-uppercase">
                        <i class="bi bi-trophy-fill me-1"></i> Freelancer-Style Contest Hub
                    </span>
                    @if(auth()->user()->isAdmin())
                        <span class="badge bg-success rounded-pill px-3 py-1.5 fw-bold extra-small">
                            <i class="bi bi-shield-check me-1"></i> Admin Instant Publishing Mode
                        </span>
                    @endif
                </div>
                <h2 class="fw-extrabold text-white mb-2">Launch Your Design Contest</h2>
                <p class="text-white text-opacity-90 mb-0 small" style="max-width: 620px;">
                    Receive dozens of unique, high-quality design concepts from verified creators. Select the best design and award the prize bounty.
                </p>
            </div>

            <!-- FORM BODY -->
            <form action="{{ route('contests.store') }}" method="POST" enctype="multipart/form-data" class="p-4 p-md-5 bg-white">
                @csrf

                <!-- SECTION 1: BASIC CONTEST INFO -->
                <div class="mb-4">
                    <h5 class="fw-extrabold text-dark pb-2 border-bottom d-flex align-items-center gap-2">
                        <i class="bi bi-pencil-square text-primary"></i>
                        <span>1. Contest Brief & Deliverables</span>
                    </h5>

                    <!-- Title -->
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small">
                            Contest Title <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="title" 
                               value="{{ old('title') }}" 
                               required 
                               placeholder="e.g., Food Delivery Mobile App UI & Brand Identity" 
                               class="form-control form-control-lg rounded-3 text-sm">
                        <div class="form-text extra-small">Keep it clear and specific so creators understand your design requirements.</div>
                    </div>

                    <div class="row g-3">
                        <!-- Category -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark small">
                                Category <span class="text-danger">*</span>
                            </label>
                            <select name="category" class="form-select rounded-3 text-sm" required>
                                <option value="">-- Select Category --</option>
                                <option value="Logo Design" {{ old('category') === 'Logo Design' ? 'selected' : '' }}>🎨 Logo Design & Branding</option>
                                <option value="Social Media Banner" {{ old('category') === 'Social Media Banner' ? 'selected' : '' }}>📱 Social Media & Ad Banners</option>
                                <option value="UI/UX Mobile & Web" {{ old('category') === 'UI/UX Mobile & Web' ? 'selected' : '' }}>💻 UI/UX Mobile & Web Design</option>
                                <option value="Packaging & Label" {{ old('category') === 'Packaging & Label' ? 'selected' : '' }}>📦 Packaging & Label Design</option>
                                <option value="Merch & T-Shirt" {{ old('category') === 'Merch & T-Shirt' ? 'selected' : '' }}>👕 Merchandise & Apparel</option>
                                <option value="Illustration & Vector" {{ old('category') === 'Illustration & Vector' ? 'selected' : '' }}>✏️ Illustration & Vector Graphics</option>
                                <option value="Typography" {{ old('category') === 'Typography' ? 'selected' : '' }}>🔤 Typography & Lettering</option>
                                <option value="Other Design" {{ old('category') === 'Other Design' ? 'selected' : '' }}>✨ Other Creative Design</option>
                            </select>
                        </div>

                        <!-- Required Dimensions -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark small">
                                Required Dimensions & Format
                            </label>
                            <input type="text" 
                                   name="required_dimensions" 
                                   value="{{ old('required_dimensions', 'Vector AI, SVG, High-Res PNG (1920x1080px)') }}" 
                                   placeholder="e.g., Vector AI / SVG, 1080x1080px, 300 DPI" 
                                   class="form-control rounded-3 text-sm">
                            <div class="form-text extra-small">Specify expected file types, resolutions, or canvas dimensions.</div>
                        </div>
                    </div>

                    <!-- Description / Brief -->
                    <div class="mt-3">
                        <label class="form-label fw-bold text-dark small">
                            Detailed Creative Brief <span class="text-danger">*</span>
                        </label>
                        <textarea name="description" 
                                  rows="5" 
                                  required 
                                  placeholder="Describe your brand, color palette preferences, visual style, target audience, and any elements to avoid..." 
                                  class="form-control rounded-3 text-sm">{{ old('description') }}</textarea>
                    </div>

                    <!-- Attachment File -->
                    <div class="mt-3">
                        <label class="form-label fw-bold text-dark small">
                            Reference Assets / Brief Attachment (Optional)
                        </label>
                        <input type="file" 
                               name="attachment_file" 
                               accept=".jpg,.jpeg,.png,.pdf,.zip,.rar,.docx,.txt"
                               class="form-control rounded-3 text-sm">
                        <div class="form-text extra-small">Upload sketches, brand guidelines, or reference files (PDF, ZIP, Image max 20MB).</div>
                    </div>
                </div>

                <!-- SECTION 2: TIMELINE & PRIZE POOL -->
                <div class="mb-4">
                    <h5 class="fw-extrabold text-dark pb-2 border-bottom d-flex align-items-center gap-2">
                        <i class="bi bi-cash-stack text-success"></i>
                        <span>2. Prize Bounty & Timeline</span>
                    </h5>

                    <div class="row g-3">
                        <!-- Prize Amount -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark small">
                                Winner Prize Bounty (BDT) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light fw-bold font-mono">৳ BDT</span>
                                <input type="number" 
                                       id="prizeAmountInput" 
                                       name="prize_amount" 
                                       value="{{ old('prize_amount', 5000) }}" 
                                       min="1000" 
                                       step="500" 
                                       required 
                                       class="form-control form-control-lg fw-bold text-success text-sm font-mono"
                                       oninput="updateFeeCalculation()">
                            </div>
                            <div class="d-flex gap-1.5 mt-2 flex-wrap">
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-2.5 py-0.5 extra-small" onclick="setPrize(2000)">৳2,000</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-2.5 py-0.5 extra-small" onclick="setPrize(5000)">৳5,000</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-2.5 py-0.5 extra-small" onclick="setPrize(10000)">৳10,000</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-2.5 py-0.5 extra-small" onclick="setPrize(25000)">৳25,000</button>
                            </div>
                            <div class="form-text extra-small">Minimum prize bounty is ৳1,000 BDT. 100% of the prize goes directly to the winner.</div>
                        </div>

                        <!-- Duration / Deadline -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark small">
                                Contest Duration <span class="text-danger">*</span>
                            </label>
                            <select name="deadline_days" class="form-select form-select-lg rounded-3 text-sm" required>
                                <option value="3" {{ old('deadline_days') == '3' ? 'selected' : '' }}>⚡ 3 Days (Fast Turnaround Sprint)</option>
                                <option value="5" {{ old('deadline_days') == '5' ? 'selected' : '' }}>🚀 5 Days (Standard Duration)</option>
                                <option value="7" {{ old('deadline_days', '7') == '7' ? 'selected' : '' }} selected>⭐ 7 Days (Recommended - Maximum Entries)</option>
                                <option value="14" {{ old('deadline_days') == '14' ? 'selected' : '' }}>⏳ 14 Days (In-depth Research & Branding)</option>
                            </select>
                            <div class="form-text extra-small">The countdown timer starts as soon as the contest goes live.</div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: ESCROW PAYMENT & VERIFICATION -->
                @if(auth()->user()->isAdmin())
                    <!-- Admin Bypasses Escrow Payment -->
                    <div class="alert alert-success rounded-3 p-3.5 d-flex align-items-center gap-2 mb-4">
                        <i class="bi bi-shield-fill-check fs-3 text-success"></i>
                        <div class="small">
                            <strong>Super Admin Privilege:</strong> No posting fee or Transaction ID verification required. This contest will be published immediately with the 'Guaranteed' shield.
                        </div>
                    </div>
                @else
                    <div class="mb-4">
                        <h5 class="fw-extrabold text-dark pb-2 border-bottom d-flex align-items-center gap-2">
                            <i class="bi bi-shield-lock-fill text-indigo-600"></i>
                            <span>3. Escrow Deposit & Platform Posting Fee</span>
                        </h5>

                        <!-- Calculation Breakdown Box -->
                        <div class="p-3.5 rounded-3 mb-4 border" style="background: #F8F7FF; border-color: rgba(124,58,237,0.2) !important;">
                            <div class="d-flex justify-content-between align-items-center pb-2 border-bottom small">
                                <span class="text-muted">Contest Prize Bounty (100% Escrow Protected):</span>
                                <span class="fw-bold text-dark font-mono" id="breakdownPrize">৳5,000 BDT</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom small">
                                <span class="text-muted">Platform Listing & Moderation Fee:</span>
                                <span class="fw-bold text-dark font-mono">৳500 BDT</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-2 fs-6">
                                <span class="fw-extrabold text-dark">Total Escrow Deposit Due:</span>
                                <span class="fw-extrabold text-primary font-mono fs-5" id="breakdownTotal">৳5,500 BDT</span>
                            </div>
                        </div>

                        <!-- Manual MFS Payment Guidelines -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <div class="p-3 rounded-3 border bg-white h-100 text-center">
                                    <div class="fw-bold text-pink-600 mb-1" style="color: #E2136E;">bKash</div>
                                    <div class="extra-small text-muted mb-1">Personal / Send Money:</div>
                                    <div class="font-mono fw-bold text-dark small bg-light p-1 rounded">01700-000000</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 rounded-3 border bg-white h-100 text-center">
                                    <div class="fw-bold text-orange-600 mb-1" style="color: #F7931E;">Nagad</div>
                                    <div class="extra-small text-muted mb-1">Personal / Send Money:</div>
                                    <div class="font-mono fw-bold text-dark small bg-light p-1 rounded">01800-000000</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 rounded-3 border bg-white h-100 text-center">
                                    <div class="fw-bold text-primary mb-1">Bank Transfer</div>
                                    <div class="extra-small text-muted mb-1">City Bank / Noksha HQ:</div>
                                    <div class="font-mono fw-bold text-dark small bg-light p-1 rounded">11022334455</div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <!-- Payment Method -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark small">
                                    Payment Method <span class="text-danger">*</span>
                                </label>
                                <select name="payment_method" class="form-select rounded-3 text-sm" required>
                                    <option value="bkash" {{ old('payment_method') === 'bkash' ? 'selected' : '' }}>bKash</option>
                                    <option value="nagad" {{ old('payment_method') === 'nagad' ? 'selected' : '' }}>Nagad</option>
                                    <option value="rocket" {{ old('payment_method') === 'rocket' ? 'selected' : '' }}>Rocket</option>
                                    <option value="bank" {{ old('payment_method') === 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                                </select>
                            </div>

                            <!-- Transaction ID -->
                            <div class="col-md-8">
                                <label class="form-label fw-bold text-dark small">
                                    Transaction ID (TrxID) / Bank Reference <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="payment_reference" 
                                       value="{{ old('payment_reference') }}" 
                                       required 
                                       placeholder="e.g., 9J47KLP89M or Bank Deposit Slip Reference" 
                                       class="form-control rounded-3 font-mono text-sm">
                                <div class="form-text extra-small">Enter the TrxID received from your MFS or bank statement. Our team verifies payments within 15–30 minutes.</div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- SUBMIT BUTTON -->
                <div class="pt-3 border-top d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <a href="{{ route('contests.index') }}" class="btn btn-light rounded-pill px-4 py-2.5 fw-bold text-secondary text-sm">
                        &larr; Cancel & Return
                    </a>
                    
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 py-2.5 fw-bold shadow-sm text-sm" style="background: linear-gradient(135deg, #7C3AED 0%, #4F46E5 100%); border: none;">
                        <i class="bi bi-shield-check me-2"></i>
                        {{ auth()->user()->isAdmin() ? 'Publish Contest Instantly' : 'Submit Contest for Escrow Verification' }}
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>

<script>
function setPrize(amount) {
    const input = document.getElementById('prizeAmountInput');
    if (input) {
        input.value = amount;
        updateFeeCalculation();
    }
}

function updateFeeCalculation() {
    const input = document.getElementById('prizeAmountInput');
    const prizeEl = document.getElementById('breakdownPrize');
    const totalEl = document.getElementById('breakdownTotal');

    if (!input || !prizeEl || !totalEl) return;

    let prize = parseFloat(input.value) || 0;
    if (prize < 0) prize = 0;

    const total = prize + 500;

    prizeEl.textContent = '৳' + prize.toLocaleString() + ' BDT';
    totalEl.textContent = '৳' + total.toLocaleString() + ' BDT';
}
</script>
@endsection
