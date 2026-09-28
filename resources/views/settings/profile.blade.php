@extends('layouts.app')

@section('title', 'Profile & Account Settings - Noksha')

@section('content')
<style>
    /* Settings Hub Aesthetic */
    .settings-container {
        max-width: 960px;
        margin: 0 auto;
    }

    /* Cover Banner with upload overlay */
    .settings-cover-wrapper {
        position: relative;
        height: 200px;
        background: linear-gradient(135deg, #6C4CF1 0%, #8B5CF6 50%, #A855F7 100%);
        border-radius: 1.25rem 1.25rem 0 0;
        overflow: hidden;
    }

    .settings-cover-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .settings-cover-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.35);
        opacity: 0;
        transition: opacity 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .settings-cover-wrapper:hover .settings-cover-overlay {
        opacity: 1;
    }

    .cover-upload-btn {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(8px);
        color: #1E293B;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 50rem;
        font-size: 0.825rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .cover-upload-btn:hover {
        background: #FFFFFF;
        transform: translateY(-1px);
    }

    /* Avatar preview with upload overlay */
    .settings-avatar-wrapper {
        position: relative;
        margin-top: -60px;
        display: inline-block;
        margin-left: 2rem;
    }

    .settings-avatar-img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid #FFFFFF;
        box-shadow: 0 10px 25px -5px rgba(108, 76, 241, 0.3);
        object-fit: cover;
        background: #F8F5FF;
        display: block;
    }

    .settings-avatar-initial {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid #FFFFFF;
        box-shadow: 0 10px 25px -5px rgba(108, 76, 241, 0.3);
        background: linear-gradient(135deg, #7C3AED 0%, #4F46E5 100%);
        color: #FFFFFF;
        font-size: 3rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .avatar-upload-badge {
        position: absolute;
        bottom: 4px;
        right: 4px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #7C3AED;
        color: #FFFFFF;
        border: 3px solid #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 10px rgba(124, 58, 237, 0.35);
    }

    .avatar-upload-badge:hover {
        background: #6D28D9;
        transform: scale(1.08);
    }

    /* Card styling */
    .settings-card {
        background: #FFFFFF;
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 1.25rem;
        box-shadow: 0 4px 20px -5px rgba(79, 70, 229, 0.04);
        overflow: hidden;
    }

    .settings-section-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: #0F172A;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .settings-section-desc {
        font-size: 0.825rem;
        color: #64748B;
        margin-bottom: 0;
    }

    .form-label-custom {
        font-size: 0.775rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #475569;
        margin-bottom: 0.35rem;
        display: block;
    }

    .form-control-custom {
        border-radius: 0.75rem;
        border: 1.5px solid #E2E8F0;
        padding: 0.65rem 0.95rem;
        font-size: 0.9rem;
        color: #0F172A;
        transition: all 0.2s ease;
    }

    .form-control-custom:focus {
        border-color: #7C3AED;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.12);
        outline: none;
    }

    /* Skills interactive tags */
    .skill-tag {
        background: rgba(124, 58, 237, 0.08);
        color: #7C3AED;
        border: 1px solid rgba(124, 58, 237, 0.2);
        padding: 0.3rem 0.75rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .skill-tag-remove {
        cursor: pointer;
        color: #9333EA;
        font-size: 0.95rem;
        line-height: 1;
        transition: color 0.15s;
    }

    .skill-tag-remove:hover {
        color: #DC2626;
    }

    .skill-suggestion {
        cursor: pointer;
        background: #F1F5F9;
        color: #475569;
        border: 1px solid #E2E8F0;
        padding: 0.25rem 0.65rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
        transition: all 0.15s ease;
    }

    .skill-suggestion:hover {
        background: rgba(124, 58, 237, 0.1);
        color: #7C3AED;
        border-color: rgba(124, 58, 237, 0.25);
    }

    /* CTA Button */
    .btn-purple-cta {
        background: linear-gradient(135deg, #7C3AED 0%, #4F46E5 100%);
        color: #FFFFFF !important;
        border: none;
        box-shadow: 0 4px 14px rgba(124, 58, 237, 0.3);
        transition: all 0.2s ease;
    }

    .btn-purple-cta:hover {
        box-shadow: 0 6px 18px rgba(124, 58, 237, 0.45);
        transform: translateY(-1px);
    }
</style>

<div class="py-4 bg-light min-vh-100">
    <div class="container settings-container">

        <!-- Top Breadcrumb & Page Title -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 extra-small text-uppercase fw-bold">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-secondary text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-secondary text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Profile Settings</li>
                    </ol>
                </nav>
                <h3 class="fw-extrabold text-dark mb-0">Profile & Account Settings</h3>
                <p class="text-secondary small mb-0">Customize your public creative portfolio, social handles, and security credentials.</p>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('seller.demo') }}" target="_blank" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-semibold btn-sm d-flex align-items-center gap-1.5" title="View Public Profile">
                    <i class="bi bi-box-arrow-up-right"></i>
                    <span>Preview Public Profile</span>
                </a>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm d-flex align-items-center gap-2 py-3 px-4 mb-4" role="alert">
                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                <div class="fw-semibold small text-success">{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm py-3 px-4 mb-4" role="alert">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                    <strong class="small text-danger">Please correct the errors below:</strong>
                </div>
                <ul class="mb-0 small text-danger ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- MAIN PROFILE FORM -->
        <form action="{{ route('settings.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
            @csrf
            @method('PUT')

            <!-- SECTION 1: COVER & AVATAR PREVIEWS -->
            <div class="settings-card mb-4">
                <!-- Cover Banner Area -->
                <div class="settings-cover-wrapper" id="coverBannerArea">
                    @if($user->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->cover_image))
                        <img src="{{ asset('storage/' . $user->cover_image) }}" alt="Cover" class="settings-cover-img" id="coverPreviewImg">
                    @else
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white text-opacity-75" id="coverDefaultBg">
                            <span class="extra-small text-uppercase tracking-wider fw-bold font-monospace">
                                <i class="bi bi-image me-1"></i> 1200 x 300 Recommended
                            </span>
                        </div>
                    @endif

                    <!-- Upload Trigger Overlay -->
                    <div class="settings-cover-overlay">
                        <label for="coverInput" class="cover-upload-btn">
                            <i class="bi bi-camera-fill"></i>
                            <span>Change Cover Image</span>
                        </label>
                    </div>
                    <input type="file" name="cover_image" id="coverInput" class="d-none" accept="image/png,image/jpeg,image/webp" onchange="previewCoverImage(this)">
                </div>

                <!-- Avatar and Profile Header Meta -->
                <div class="p-4 pt-0">
                    <div class="d-flex align-items-end justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-end gap-3 flex-wrap">
                            <!-- Avatar Wrapper -->
                            <div class="settings-avatar-wrapper">
                                @if($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar))
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="settings-avatar-img" id="avatarPreviewImg">
                                @else
                                    <div class="settings-avatar-initial" id="avatarInitial">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <img src="" alt="{{ $user->name }}" class="settings-avatar-img d-none" id="avatarPreviewImg">
                                @endif

                                <label for="avatarInput" class="avatar-upload-badge" title="Change Avatar Photo">
                                    <i class="bi bi-camera-fill"></i>
                                </label>
                                <input type="file" name="avatar" id="avatarInput" class="d-none" accept="image/png,image/jpeg,image/webp" onchange="previewAvatarImage(this)">
                            </div>

                            <!-- Name & Handle Brief -->
                            <div class="mb-2">
                                <h4 class="fw-extrabold text-dark mb-0">{{ $user->name }}</h4>
                                <div class="text-muted extra-small d-flex align-items-center gap-2 mt-0.5">
                                    <span class="font-monospace text-primary fw-bold">&#64;{{ $user->username }}</span>
                                    <span>•</span>
                                    <span class="badge bg-light text-secondary border font-monospace">{{ $user->getRoleBadgeLabel() }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Availability Quick Switch -->
                        <div class="mb-2">
                            <div class="form-check form-switch d-flex align-items-center gap-2 p-0 m-0">
                                <input class="form-check-input ms-0 me-2" type="checkbox" name="is_available" value="1" id="availabilitySwitch" role="switch" style="width: 2.75rem; height: 1.4rem; cursor: pointer;" {{ old('is_available', $user->is_available ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold small text-dark" for="availabilitySwitch" id="availabilityLabel" style="cursor: pointer;">
                                    @if(old('is_available', $user->is_available ?? true))
                                        <span class="text-success"><i class="bi bi-record-fill me-1"></i> Available for Hire</span>
                                    @else
                                        <span class="text-muted"><i class="bi bi-dash-circle me-1"></i> Busy / Not Available</span>
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: BASIC INFORMATION -->
            <div class="settings-card p-4 p-md-5 mb-4">
                <div class="mb-4 pb-3 border-bottom">
                    <div class="settings-section-title">
                        <i class="bi bi-person-badge-fill text-primary"></i>
                        <span>Basic Information</span>
                    </div>
                    <p class="settings-section-desc">Public identification details visible on your creator storefront and design submissions.</p>
                </div>

                <div class="row g-3">
                    <!-- Full Legal Name -->
                    <div class="col-md-6">
                        <label class="form-label-custom" for="inputName">Full Legal Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="inputName" class="form-control form-control-custom @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required placeholder="e.g. Miad Khan">
                        @error('name')
                            <div class="invalid-feedback extra-small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Unique Username Handle -->
                    <div class="col-md-6">
                        <label class="form-label-custom" for="inputUsername">Username Handle <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted font-monospace">&#64;</span>
                            <input type="text" name="username" id="inputUsername" class="form-control form-control-custom border-start-0 rounded-end-3 font-monospace @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" required placeholder="username">
                        </div>
                        <div class="extra-small text-muted mt-1">Letters, numbers, underscores and dashes only.</div>
                        @error('username')
                            <div class="text-danger extra-small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Professional Headline -->
                    <div class="col-12">
                        <label class="form-label-custom" for="inputHeadline">Professional Headline</label>
                        <input type="text" name="headline" id="inputHeadline" class="form-control form-control-custom @error('headline') is-invalid @enderror" value="{{ old('headline', $user->headline) }}" maxlength="150" placeholder="e.g. Brand Identity Designer & Vector Specialist">
                        <div class="extra-small text-muted mt-1">Brief summary displayed directly below your name (max 150 characters).</div>
                        @error('headline')
                            <div class="invalid-feedback extra-small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Bio / About -->
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <label class="form-label-custom" for="inputBio">Bio / Creative Background</label>
                            <span class="extra-small text-muted font-monospace" id="bioCounter">0 / 1000</span>
                        </div>
                        <textarea name="bio" id="inputBio" rows="4" class="form-control form-control-custom @error('bio') is-invalid @enderror" maxlength="1000" placeholder="Share your experience, design principles, tools, and background with clients and fellow contributors..." oninput="updateBioCounter()">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback extra-small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- SECTION 3: SKILLS & TOOLS TAG MANAGER -->
            <div class="settings-card p-4 p-md-5 mb-4">
                <div class="mb-4 pb-3 border-bottom">
                    <div class="settings-section-title">
                        <i class="bi bi-tools text-primary"></i>
                        <span>Skills & Creative Tools</span>
                    </div>
                    <p class="settings-section-desc">Highlight your core design competencies, software proficiencies, and categories.</p>
                </div>

                <!-- Interactive Tag Container -->
                <div class="mb-3">
                    <label class="form-label-custom">Selected Skills</label>
                    <div class="p-3 bg-light rounded-3 border d-flex flex-wrap gap-2 align-items-center" id="skillsTagContainer" style="min-height: 54px;">
                        @php
                            $userSkills = old('skills', $user->skills ?? []);
                            if (is_string($userSkills)) {
                                $userSkills = array_filter(array_map('trim', explode(',', $userSkills)));
                            }
                        @endphp

                        @if(!empty($userSkills))
                            @foreach($userSkills as $skill)
                                <span class="skill-tag" data-skill="{{ $skill }}">
                                    <span>{{ $skill }}</span>
                                    <span class="skill-tag-remove" onclick="removeSkillTag('{{ addslashes($skill) }}')">&times;</span>
                                    <input type="hidden" name="skills[]" value="{{ $skill }}">
                                </span>
                            @endforeach
                        @else
                            <span class="text-muted extra-small" id="noSkillsPrompt">No skills added yet. Type below or select from suggestions.</span>
                        @endif
                    </div>
                </div>

                <!-- Input to add custom skill -->
                <div class="row g-2 mb-3">
                    <div class="col-sm-9">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-plus-circle"></i></span>
                            <input type="text" id="newSkillInput" class="form-control form-control-custom border-start-0" placeholder="Type a skill (e.g. Logo Design, Typography, Figma) and press Enter" onkeydown="handleSkillKeyDown(event)">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <button type="button" class="btn btn-outline-primary rounded-3 w-100 py-2 fw-semibold extra-small" onclick="addSkillFromInput()">
                            Add Skill
                        </button>
                    </div>
                </div>

                <!-- Popular Suggestions -->
                <div>
                    <span class="extra-small text-muted fw-bold text-uppercase me-2">Popular Suggestions:</span>
                    <div class="d-inline-flex flex-wrap gap-1.5 align-items-center mt-1">
                        @foreach(['Logo Design', 'Branding', 'Figma', 'Illustrator', 'Photoshop', 'Typography', 'Vector Art', 'Packaging', 'Social Media', 'UI/UX'] as $suggested)
                            <span class="skill-suggestion" onclick="addSuggestedSkill('{{ $suggested }}')">+ {{ $suggested }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- SECTION 4: SOCIAL & PORTFOLIO LINKS -->
            <div class="settings-card p-4 p-md-5 mb-4">
                <div class="mb-4 pb-3 border-bottom">
                    <div class="settings-section-title">
                        <i class="bi bi-globe text-primary"></i>
                        <span>Social & Portfolio Presence</span>
                    </div>
                    <p class="settings-section-desc">Connect your external creative profiles to establish authority and trust.</p>
                </div>

                @php
                    $socialLinks = old('social_links', $user->social_links ?? []);
                @endphp

                <div class="row g-3">
                    <!-- Behance -->
                    <div class="col-md-6">
                        <label class="form-label-custom" for="linkBehance">Behance Portfolio</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-primary"><i class="bi bi-behance fs-6"></i></span>
                            <input type="url" name="social_links[behance]" id="linkBehance" class="form-control form-control-custom border-start-0 rounded-end-3" value="{{ $socialLinks['behance'] ?? '' }}" placeholder="https://behance.net/username">
                        </div>
                    </div>

                    <!-- Dribbble -->
                    <div class="col-md-6">
                        <label class="form-label-custom" for="linkDribbble">Dribbble Profile</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-danger"><i class="bi bi-dribbble fs-6"></i></span>
                            <input type="url" name="social_links[dribbble]" id="linkDribbble" class="form-control form-control-custom border-start-0 rounded-end-3" value="{{ $socialLinks['dribbble'] ?? '' }}" placeholder="https://dribbble.com/username">
                        </div>
                    </div>

                    <!-- Personal Website / Portfolio -->
                    <div class="col-md-6">
                        <label class="form-label-custom" for="linkWebsite">Personal Website / Studio</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-secondary"><i class="bi bi-globe2 fs-6"></i></span>
                            <input type="url" name="social_links[website]" id="linkWebsite" class="form-control form-control-custom border-start-0 rounded-end-3" value="{{ $socialLinks['website'] ?? '' }}" placeholder="https://yourportfolio.com">
                        </div>
                    </div>

                    <!-- LinkedIn -->
                    <div class="col-md-6">
                        <label class="form-label-custom" for="linkLinkedin">LinkedIn Profile</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-info"><i class="bi bi-linkedin fs-6"></i></span>
                            <input type="url" name="social_links[linkedin]" id="linkLinkedin" class="form-control form-control-custom border-start-0 rounded-end-3" value="{{ $socialLinks['linkedin'] ?? '' }}" placeholder="https://linkedin.com/in/username">
                        </div>
                    </div>

                    <!-- Twitter / X -->
                    <div class="col-md-6">
                        <label class="form-label-custom" for="linkTwitter">Twitter / X Handle</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-dark"><i class="bi bi-twitter-x fs-6"></i></span>
                            <input type="url" name="social_links[twitter]" id="linkTwitter" class="form-control form-control-custom border-start-0 rounded-end-3" value="{{ $socialLinks['twitter'] ?? '' }}" placeholder="https://x.com/username">
                        </div>
                    </div>

                    <!-- GitHub -->
                    <div class="col-md-6">
                        <label class="form-label-custom" for="linkGithub">GitHub Profile</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-dark"><i class="bi bi-github fs-6"></i></span>
                            <input type="url" name="social_links[github]" id="linkGithub" class="form-control form-control-custom border-start-0 rounded-end-3" value="{{ $socialLinks['github'] ?? '' }}" placeholder="https://github.com/username">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 5: CONTACT INFORMATION -->
            <div class="settings-card p-4 p-md-5 mb-4">
                <div class="mb-4 pb-3 border-bottom">
                    <div class="settings-section-title">
                        <i class="bi bi-envelope-check-fill text-primary"></i>
                        <span>Contact & Account Verification</span>
                    </div>
                    <p class="settings-section-desc">Credentials used for system notifications, contest payouts, and order invoices.</p>
                </div>

                <div class="row g-3">
                    <!-- Email Address (Readonly / Status) -->
                    <div class="col-md-6">
                        <label class="form-label-custom">Email Address</label>
                        <div class="input-group">
                            <input type="email" class="form-control form-control-custom bg-light" value="{{ $user->email }}" readonly disabled>
                            <span class="input-group-text bg-light border-start-0">
                                @if($user->hasVerifiedEmail())
                                    <span class="badge bg-success bg-opacity-10 text-success extra-small fw-bold">Verified ✓</span>
                                @else
                                    <a href="{{ route('verification.notice') }}" class="badge bg-warning bg-opacity-15 text-warning-emphasis text-decoration-none extra-small fw-bold">Verify Now</a>
                                @endif
                            </span>
                        </div>
                        <div class="extra-small text-muted mt-1">To change your email address, contact support.</div>
                    </div>

                    <!-- Phone Number -->
                    <div class="col-md-6">
                        <label class="form-label-custom" for="inputPhone">Phone / WhatsApp Number</label>
                        <input type="tel" name="phone" id="inputPhone" class="form-control form-control-custom" value="{{ old('phone', $user->phone) }}" placeholder="+880 1XXXXXXXXX">
                        <div class="extra-small text-muted mt-1">Used for payment confirmations and contest winner notifications.</div>
                    </div>
                </div>
            </div>

            <!-- SAVE PROFILE BUTTON -->
            <div class="d-flex align-items-center justify-content-end gap-3 mb-5">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2.5 fw-semibold">
                    Cancel
                </a>
                <button type="submit" class="btn btn-purple-cta rounded-pill px-5 py-2.5 fw-bold d-inline-flex align-items-center gap-2">
                    <i class="bi bi-check2-circle fs-5"></i>
                    <span>Save Profile Changes</span>
                </button>
            </div>
        </form>

        <!-- SECTION 6: PASSWORD & SECURITY (SEPARATE FORM) -->
        <div class="settings-card p-4 p-md-5 mb-5">
            <div class="mb-4 pb-3 border-bottom">
                <div class="settings-section-title text-dark">
                    <i class="bi bi-shield-lock-fill text-danger"></i>
                    <span>Password & Security</span>
                </div>
                <p class="settings-section-desc">Ensure your Noksha account is secured with a strong, distinct password.</p>
            </div>

            <form action="{{ route('settings.password.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3" style="max-width: 600px;">
                    <!-- Current Password -->
                    <div class="col-12">
                        <label class="form-label-custom" for="current_password">Current Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="current_password" class="form-control form-control-custom border-end-0 @error('current_password') is-invalid @enderror" required placeholder="Enter current password">
                            <button type="button" class="btn btn-light border border-start-0 rounded-end-3 text-secondary" onclick="togglePasswordVisibility('current_password', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <div class="text-danger extra-small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="col-12">
                        <label class="form-label-custom" for="password">New Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control form-control-custom border-end-0 @error('password') is-invalid @enderror" required placeholder="At least 8 characters">
                            <button type="button" class="btn btn-light border border-start-0 rounded-end-3 text-secondary" onclick="togglePasswordVisibility('password', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-danger extra-small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div class="col-12">
                        <label class="form-label-custom" for="password_confirmation">Confirm New Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-custom border-end-0" required placeholder="Re-type new password">
                            <button type="button" class="btn btn-light border border-start-0 rounded-end-3 text-secondary" onclick="togglePasswordVisibility('password_confirmation', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-12 pt-2">
                        <button type="submit" class="btn btn-dark rounded-pill px-4 py-2.5 fw-bold d-inline-flex align-items-center gap-2">
                            <i class="bi bi-shield-check"></i>
                            <span>Update Password</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
    // Live Image Previews
    function previewCoverImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                let img = document.getElementById('coverPreviewImg');
                const defaultBg = document.getElementById('coverDefaultBg');
                if (!img) {
                    img = document.createElement('img');
                    img.id = 'coverPreviewImg';
                    img.className = 'settings-cover-img';
                    document.getElementById('coverBannerArea').prepend(img);
                }
                img.src = e.target.result;
                if (defaultBg) {
                    defaultBg.style.display = 'none';
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewAvatarImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('avatarPreviewImg');
                const initial = document.getElementById('avatarInitial');
                img.src = e.target.result;
                img.classList.remove('d-none');
                if (initial) {
                    initial.classList.add('d-none');
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Availability Switch Label Toggle
    const availabilitySwitch = document.getElementById('availabilitySwitch');
    const availabilityLabel = document.getElementById('availabilityLabel');
    if (availabilitySwitch && availabilityLabel) {
        availabilitySwitch.addEventListener('change', function() {
            if (this.checked) {
                availabilityLabel.innerHTML = '<span class="text-success"><i class="bi bi-record-fill me-1"></i> Available for Hire</span>';
            } else {
                availabilityLabel.innerHTML = '<span class="text-muted"><i class="bi bi-dash-circle me-1"></i> Busy / Not Available</span>';
            }
        });
    }

    // Bio Character Counter
    function updateBioCounter() {
        const bio = document.getElementById('inputBio');
        const counter = document.getElementById('bioCounter');
        if (bio && counter) {
            counter.textContent = bio.value.length + ' / 1000';
        }
    }
    document.addEventListener('DOMContentLoaded', updateBioCounter);

    // Interactive Skills Tag Manager
    function addSkill(skillName) {
        skillName = skillName.trim();
        if (!skillName) return;

        // Check if skill already exists
        const existing = document.querySelector(`.skill-tag[data-skill="${CSS.escape(skillName)}"]`);
        if (existing) return;

        const container = document.getElementById('skillsTagContainer');
        const prompt = document.getElementById('noSkillsPrompt');
        if (prompt) prompt.remove();

        const tag = document.createElement('span');
        tag.className = 'skill-tag';
        tag.setAttribute('data-skill', skillName);

        const safeSkill = skillName.replace(/"/g, '&quot;');
        tag.innerHTML = `
            <span>${safeSkill}</span>
            <span class="skill-tag-remove" onclick="removeSkillTag('${safeSkill.replace(/'/g, "\\'")}')">&times;</span>
            <input type="hidden" name="skills[]" value="${safeSkill}">
        `;
        container.appendChild(tag);
    }

    function removeSkillTag(skillName) {
        const tag = document.querySelector(`.skill-tag[data-skill="${CSS.escape(skillName)}"]`);
        if (tag) {
            tag.remove();
        }
        const container = document.getElementById('skillsTagContainer');
        if (container.querySelectorAll('.skill-tag').length === 0) {
            const prompt = document.createElement('span');
            prompt.id = 'noSkillsPrompt';
            prompt.className = 'text-muted extra-small';
            prompt.textContent = 'No skills added yet. Type below or select from suggestions.';
            container.appendChild(prompt);
        }
    }

    function addSkillFromInput() {
        const input = document.getElementById('newSkillInput');
        if (!input) return;
        const val = input.value;
        if (val.includes(',')) {
            val.split(',').forEach(s => addSkill(s));
        } else {
            addSkill(val);
        }
        input.value = '';
    }

    function handleSkillKeyDown(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addSkillFromInput();
        }
    }

    function addSuggestedSkill(skill) {
        addSkill(skill);
    }

    // Toggle Password Visibility
    function togglePasswordVisibility(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }
</script>
@endsection
