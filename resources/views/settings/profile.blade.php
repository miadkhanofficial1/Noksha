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
        background: linear-gradient(135deg, #20165c 0%, #3b2a8c 50%, #f47402 100%);
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
        background: rgba(18, 24, 38, 0.4);
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
        color: #121826;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 1.15rem;
        border-radius: 50rem;
        font-size: 0.825rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .cover-upload-btn:hover {
        background: #ffffff;
        border-color: #20165c;
        color: #20165c;
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
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
        object-fit: cover;
        background: #ffffff;
        display: block;
    }

    .settings-avatar-initial {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
        background: linear-gradient(135deg, #20165c 0%, #3b2a8c 100%);
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
        background: #20165c;
        color: #FFFFFF;
        border: 3px solid #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(32, 22, 92, 0.25);
    }

    .avatar-upload-badge:hover {
        background: #181045;
        transform: scale(1.08);
    }

    /* Skills interactive tags */
    .skill-tag {
        background: rgba(32, 22, 92, 0.08);
        color: #20165c;
        border: 1px solid rgba(32, 22, 92, 0.15);
        padding: 0.35rem 0.85rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .skill-tag-remove {
        cursor: pointer;
        color: #ef4444;
        font-size: 1rem;
        line-height: 1;
        transition: color 0.15s;
    }

    .skill-tag-remove:hover {
        color: #dc2626;
    }

    .skill-suggestion {
        cursor: pointer;
        background: #f8fafc;
        color: #475569;
        border: 1px solid #e2e8f0;
        padding: 0.3rem 0.75rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
        transition: all 0.15s ease;
    }

    .skill-suggestion:hover {
        background: rgba(32, 22, 92, 0.06);
        color: #20165c;
        border-color: #20165c;
    }
</style>

<div class="min-h-screen bg-[#ffffff] text-slate-800 py-10 px-4 sm:px-6 lg:px-8">
    <div class="settings-container">

        <!-- Top Breadcrumb & Page Title -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-6">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 text-xs uppercase font-semibold">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-[#20165c] hover:underline text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-[#20165c] hover:underline text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active text-slate-700" aria-current="page">Profile Settings</li>
                    </ol>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#121826] tracking-tight mb-1">Profile & Account Settings</h1>
                <p class="text-xs text-slate-500 mb-0">Customize your public creative portfolio, social handles, and security credentials.</p>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('user.profile', $user->username ?? $user->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 hover:border-[#20165c] hover:text-[#20165c] transition shadow-sm" title="View Public Profile">
                    <i class="bi bi-box-arrow-up-right text-[#20165c]"></i>
                    <span>Preview Public Profile</span>
                </a>
            </div>
        </div>

        <!-- Session Status & Errors -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs flex items-center justify-between gap-3 shadow-lg">
                <div class="flex items-center gap-2.5 font-semibold">
                    <i class="bi bi-check-circle-fill text-emerald-400 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="text-emerald-400 hover:text-[#121826]" onclick="this.parentElement.remove()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs shadow-lg">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <i class="bi bi-exclamation-triangle-fill text-rose-400 text-base"></i>
                    <span>Please correct the errors below:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-slate-300 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- MAIN PROFILE FORM -->
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileSettingsForm">
            @csrf

            <!-- SECTION 1: COVER & AVATAR PREVIEWS -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm mb-8 overflow-hidden">
                @php
                    $coverUrl = $user->cover_url;
                    $avatarUrl = $user->avatar_url;
                    $userInitials = $user->initials ?? strtoupper(substr($user->name ?? 'NO', 0, 2));
                @endphp

                <!-- Cover Image Banner Box -->
                <div id="cover_banner_box" class="bg-gradient-to-r from-[#20165c] via-[#3b2a8c] to-[#f47402] h-48 md:h-56 w-full rounded-t-2xl relative overflow-hidden border-b border-slate-200"
                     style="{{ $coverUrl ? "background-image: url('{$coverUrl}'); background-size: cover; background-position: center;" : '' }}">
                    
                    @if($coverUrl)
                        <img id="cover_preview" src="{{ $coverUrl }}" alt="Cover Preview" class="w-full h-full object-cover object-center block" onerror="this.classList.add('hidden');">
                    @else
                        <img id="cover_preview" src="" alt="Cover Preview" class="w-full h-full object-cover object-center block hidden" onerror="this.classList.add('hidden');">
                    @endif

                    <!-- Hidden file input -->
                    <input type="file" name="cover_image" id="cover_upload" accept="image/*" class="hidden">

                    <!-- Change Cover Photo Button -->
                    <button type="button" onclick="document.getElementById('cover_upload').click();" class="absolute top-4 right-4 bg-white/90 backdrop-blur text-slate-800 border border-slate-200 text-xs font-semibold px-4 py-2 rounded-lg shadow-sm hover:bg-white flex items-center gap-2 cursor-pointer transition">
                        <i class="bi bi-camera-fill text-[#20165c]"></i>
                        <span>Change Cover Photo</span>
                    </button>
                </div>

                <!-- Avatar and Profile Header Meta -->
                <div class="p-6 pt-0">
                    <div class="flex items-end justify-between flex-wrap gap-4">
                        <div class="flex items-end gap-4 flex-wrap">
                            <!-- Avatar Circle (Clickable Trigger with crisp white ring) -->
                            <div class="relative -mt-14 w-28 h-28 shrink-0">
                                <div onclick="document.getElementById('avatar_upload').click();"
                                     class="relative w-28 h-28 rounded-full overflow-hidden flex items-center justify-center bg-[#20165c] text-white font-bold ring-4 ring-white shadow-lg cursor-pointer group">
                                    <img id="avatar_preview" 
                                         src="{{ $avatarUrl ?? '' }}" 
                                         alt="{{ $user->name }}" 
                                         class="w-full h-full object-cover object-center block rounded-full {{ $avatarUrl ? '' : 'hidden' }}"
                                         onerror="this.classList.add('hidden'); document.getElementById('avatar_initials').classList.remove('hidden');">

                                    <div id="avatar_initials" 
                                         class="w-full h-full flex items-center justify-center bg-[#20165c] text-white text-2xl font-bold {{ $avatarUrl ? 'hidden' : '' }}">
                                        {{ $userInitials }}
                                    </div>

                                    <!-- Hover Camera Overlay -->
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-full">
                                        <i class="bi bi-camera-fill text-white text-lg"></i>
                                    </div>
                                </div>

                                <!-- Camera Badge Button -->
                                <button type="button" 
                                        onclick="document.getElementById('avatar_upload').click();" 
                                        class="absolute bottom-1 right-1 bg-[#20165c] hover:bg-[#181045] text-white p-2 rounded-full cursor-pointer shadow-md flex items-center justify-center ring-2 ring-white transition-transform hover:scale-110" 
                                        title="Change Avatar Photo">
                                    <i class="bi bi-camera-fill text-xs"></i>
                                </button>

                                <!-- Hidden file input -->
                                <input type="file" name="avatar" id="avatar_upload" accept="image/*" class="hidden">
                            </div>

                            <!-- Name & Handle Brief -->
                            <div class="mb-2">
                                <h3 class="text-xl font-bold text-[#121826] mb-0.5">{{ $user->name }}</h3>
                                <div class="text-xs text-slate-500 flex items-center gap-2">
                                    <span class="font-mono text-[#20165c] font-semibold">&#64;{{ $user->username }}</span>
                                    <span>•</span>
                                    <span class="badge bg-slate-100 text-slate-700 border border-slate-200 font-mono text-[11px] px-2 py-0.5 rounded">{{ $user->getRoleBadgeLabel() }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Availability Quick Switch -->
                        <div class="mb-2">
                            <div class="flex items-center gap-2.5 p-2 px-3 rounded-xl bg-white border border-slate-200">
                                <input class="w-10 h-5 accent-indigo-500 rounded cursor-pointer" type="checkbox" name="is_available" value="1" id="availabilitySwitch" {{ old('is_available', $user->is_available ?? true) ? 'checked' : '' }}>
                                <label class="text-xs font-semibold cursor-pointer" for="availabilitySwitch" id="availabilityLabel">
                                    @if(old('is_available', $user->is_available ?? true))
                                        <span class="text-emerald-400 flex items-center gap-1.5"><i class="bi bi-record-fill"></i> Available for Hire</span>
                                    @else
                                        <span class="text-slate-500 flex items-center gap-1.5"><i class="bi bi-dash-circle"></i> Busy / Not Available</span>
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: BASIC INFORMATION -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm mb-8">
                <div class="mb-6 pb-4 border-b border-slate-200">
                    <div class="text-lg font-bold text-[#121826] tracking-wide flex items-center gap-2">
                        <i class="bi bi-person-badge-fill text-[#20165c]"></i>
                        <span>Basic Information</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Public identification details visible on your creator storefront and design submissions.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Full Legal Name -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2" for="inputName">
                            Full Legal Name <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="inputName" 
                               class="w-full bg-white border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl px-4 py-3 focus:outline-none focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] text-sm transition-all duration-200" 
                               value="{{ old('name', $user->name) }}" 
                               required 
                               placeholder="e.g. Miad Khan">
                        @error('name')
                            <div class="text-rose-400 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Unique Username Handle -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2" for="inputUsername">
                            Username Handle <span class="text-rose-400">*</span>
                        </label>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3.5 py-3 rounded-l-xl bg-slate-50 border border-r-0 border-slate-200 text-slate-500 font-mono text-sm">&#64;</span>
                            <input type="text" 
                                   name="username" 
                                   id="inputUsername" 
                                   class="w-full bg-white border border-slate-200 text-slate-900 placeholder-slate-400 rounded-r-xl px-4 py-3 focus:outline-none focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] text-sm font-mono transition-all duration-200" 
                                   value="{{ old('username', $user->username) }}" 
                                   required 
                                   placeholder="username">
                        </div>
                        <div class="text-[11px] text-slate-500 mt-1">Letters, numbers, underscores and dashes only.</div>
                        @error('username')
                            <div class="text-rose-400 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Professional Headline -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2" for="inputHeadline">
                            Professional Headline
                        </label>
                        <input type="text" 
                               name="headline" 
                               id="inputHeadline" 
                               class="w-full bg-white border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl px-4 py-3 focus:outline-none focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] text-sm transition-all duration-200" 
                               value="{{ old('headline', $user->headline) }}" 
                               maxlength="150" 
                               placeholder="e.g. Brand Identity Designer & Vector Specialist">
                        <div class="text-[11px] text-slate-500 mt-1">Brief summary displayed directly below your name (max 150 characters).</div>
                        @error('headline')
                            <div class="text-rose-400 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Bio / About -->
                    <div class="md:col-span-2">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-0" for="inputBio">
                                Bio / Creative Background
                            </label>
                            <span class="text-xs text-slate-500 font-mono" id="bioCounter">0 / 1000</span>
                        </div>
                        <textarea name="bio" 
                                  id="inputBio" 
                                  rows="4" 
                                  class="w-full bg-white border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl px-4 py-3 focus:outline-none focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] text-sm transition-all duration-200" 
                                  maxlength="1000" 
                                  placeholder="Share your experience, design principles, tools, and background with clients and fellow contributors..." 
                                  oninput="updateBioCounter()">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio')
                            <div class="text-rose-400 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- SECTION 3: SKILLS & TOOLS TAG MANAGER -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm mb-8">
                <div class="mb-6 pb-4 border-b border-slate-200">
                    <div class="text-lg font-bold text-[#121826] tracking-wide flex items-center gap-2">
                        <i class="bi bi-tools text-[#20165c]"></i>
                        <span>Skills & Creative Tools</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Highlight your core design competencies, software proficiencies, and categories.</p>
                </div>

                <!-- Interactive Tag Container -->
                <div class="mb-4">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">Selected Skills</label>
                    <div class="p-3.5 bg-white rounded-xl border border-slate-200 flex flex-wrap gap-2 items-center min-h-[56px]" id="skillsTagContainer">
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
                            <span class="text-slate-500 text-xs" id="noSkillsPrompt">No skills added yet. Type below or select from suggestions.</span>
                        @endif
                    </div>
                </div>

                <!-- Input to add custom skill -->
                <div class="flex flex-col sm:flex-row gap-2.5 mb-4">
                    <div class="flex-grow">
                        <input type="text" 
                               id="newSkillInput" 
                               class="w-full bg-white border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl px-4 py-3 focus:outline-none focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] text-sm transition-all" 
                               placeholder="Type a skill (e.g. Logo Design, Typography, Figma) and press Enter" 
                               onkeydown="handleSkillKeyDown(event)">
                    </div>
                    <button type="button" 
                            class="px-5 py-3 rounded-xl bg-[#20165c] hover:bg-[#181045] text-white font-semibold text-xs transition shadow-sm whitespace-nowrap" 
                            onclick="addSkillFromInput()">
                        <i class="bi bi-plus-lg me-1"></i> Add Skill
                    </button>
                </div>

                <!-- Popular Suggestions -->
                <div>
                    <span class="text-xs text-slate-500 font-bold uppercase tracking-wider me-2 block sm:inline-block mb-1.5 sm:mb-0">Popular Suggestions:</span>
                    <div class="inline-flex flex-wrap gap-1.5 items-center">
                        @foreach(['Logo Design', 'Branding', 'Figma', 'Illustrator', 'Photoshop', 'Typography', 'Vector Art', 'Packaging', 'Social Media', 'UI/UX'] as $suggested)
                            <span class="skill-suggestion" onclick="addSuggestedSkill('{{ $suggested }}')">+ {{ $suggested }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- SECTION 4: SOCIAL & PORTFOLIO LINKS -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm mb-8">
                <div class="mb-6 pb-4 border-b border-slate-200">
                    <div class="text-lg font-bold text-[#121826] tracking-wide flex items-center gap-2">
                        <i class="bi bi-globe text-[#20165c]"></i>
                        <span>Social & Portfolio Presence</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Connect your external creative profiles to establish authority and trust.</p>
                </div>

                @php
                    $socialLinks = old('social_links', $user->social_links ?? []);
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Behance -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2" for="linkBehance">Behance Portfolio</label>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3.5 py-3 rounded-l-xl bg-slate-50 border border-r-0 border-slate-200 text-[#20165c]"><i class="bi bi-behance fs-6"></i></span>
                            <input type="url" name="social_links[behance]" id="linkBehance" class="w-full bg-white border border-slate-200 text-slate-900 placeholder-slate-400 rounded-r-xl px-4 py-3 focus:outline-none focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] text-sm transition-all" value="{{ $socialLinks['behance'] ?? '' }}" placeholder="https://behance.net/username">
                        </div>
                    </div>

                    <!-- Dribbble -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2" for="linkDribbble">Dribbble Profile</label>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3.5 py-3 rounded-l-xl bg-slate-50 border border-r-0 border-slate-200 text-[#f47402]"><i class="bi bi-dribbble fs-6"></i></span>
                            <input type="url" name="social_links[dribbble]" id="linkDribbble" class="w-full bg-white border border-slate-200 text-slate-900 placeholder-slate-400 rounded-r-xl px-4 py-3 focus:outline-none focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] text-sm transition-all" value="{{ $socialLinks['dribbble'] ?? '' }}" placeholder="https://dribbble.com/username">
                        </div>
                    </div>

                    <!-- Personal Website / Portfolio -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2" for="linkWebsite">Personal Website / Studio</label>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3.5 py-3 rounded-l-xl bg-slate-50 border border-r-0 border-slate-200 text-[#20165c]"><i class="bi bi-globe2 fs-6"></i></span>
                            <input type="url" name="social_links[website]" id="linkWebsite" class="w-full bg-white border border-slate-200 text-slate-900 placeholder-slate-400 rounded-r-xl px-4 py-3 focus:outline-none focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] text-sm transition-all" value="{{ $socialLinks['website'] ?? '' }}" placeholder="https://yourportfolio.com">
                        </div>
                    </div>

                    <!-- LinkedIn -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2" for="linkLinkedin">LinkedIn Profile</label>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3.5 py-3 rounded-l-xl bg-slate-50 border border-r-0 border-slate-200 text-[#0a66c2]"><i class="bi bi-linkedin fs-6"></i></span>
                            <input type="url" name="social_links[linkedin]" id="linkLinkedin" class="w-full bg-white border border-slate-200 text-slate-900 placeholder-slate-400 rounded-r-xl px-4 py-3 focus:outline-none focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] text-sm transition-all" value="{{ $socialLinks['linkedin'] ?? '' }}" placeholder="https://linkedin.com/in/username">
                        </div>
                    </div>

                    <!-- Twitter / X -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2" for="linkTwitter">Twitter / X Handle</label>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3.5 py-3 rounded-l-xl bg-slate-50 border border-r-0 border-slate-200 text-slate-700"><i class="bi bi-twitter-x fs-6"></i></span>
                            <input type="url" name="social_links[twitter]" id="linkTwitter" class="w-full bg-white border border-slate-200 text-slate-900 placeholder-slate-400 rounded-r-xl px-4 py-3 focus:outline-none focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] text-sm transition-all" value="{{ $socialLinks['twitter'] ?? '' }}" placeholder="https://x.com/username">
                        </div>
                    </div>

                    <!-- GitHub -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2" for="linkGithub">GitHub Profile</label>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3.5 py-3 rounded-l-xl bg-slate-50 border border-r-0 border-slate-200 text-slate-800"><i class="bi bi-github fs-6"></i></span>
                            <input type="url" name="social_links[github]" id="linkGithub" class="w-full bg-white border border-slate-200 text-slate-900 placeholder-slate-400 rounded-r-xl px-4 py-3 focus:outline-none focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] text-sm transition-all" value="{{ $socialLinks['github'] ?? '' }}" placeholder="https://github.com/username">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 5: CONTACT INFORMATION -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm mb-8">
                <div class="mb-6 pb-4 border-b border-slate-200">
                    <div class="text-lg font-bold text-[#121826] tracking-wide flex items-center gap-2">
                        <i class="bi bi-envelope-check-fill text-[#20165c]"></i>
                        <span>Contact & Account Verification</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Credentials used for system notifications, contest payouts, and order invoices.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Email Address (Readonly / Status) -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">Email Address</label>
                        <div class="flex items-center gap-2">
                            <input type="email" class="w-full bg-slate-100 border border-slate-200 text-slate-600 rounded-xl px-4 py-3 cursor-not-allowed text-sm" value="{{ $user->email }}" readonly disabled>
                            @if($user->hasVerifiedEmail())
                                <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 font-semibold px-3 py-1.5 rounded-lg text-xs flex items-center gap-1 shrink-0">
                                    <i class="bi bi-check-circle-fill text-emerald-600"></i> Verified
                                </span>
                            @else
                                <a href="{{ route('verification.notice') }}" class="bg-amber-50 text-amber-700 border border-amber-200 font-semibold px-3 py-1.5 rounded-lg text-xs flex items-center gap-1 hover:bg-amber-100 text-decoration-none shrink-0">
                                    <i class="bi bi-exclamation-circle-fill text-amber-600"></i> Verify Now
                                </a>
                            @endif
                        </div>
                        <div class="text-[11px] text-slate-500 mt-1">To change your email address, contact support.</div>
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2" for="inputPhone">Phone / WhatsApp Number</label>
                        <input type="tel" 
                               name="phone" 
                               id="inputPhone" 
                               class="w-full bg-white border border-slate-200 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] rounded-xl px-4 py-3 text-sm transition-all duration-200" 
                               value="{{ old('phone', $user->phone) }}" 
                               placeholder="+880 1XXXXXXXXX">
                        <div class="text-[11px] text-slate-500 mt-1">Used for payment confirmations and contest winner notifications.</div>
                    </div>
                </div>
            </div>

            <!-- SAVE PROFILE BUTTON -->
            <div class="flex items-center justify-end gap-3 mb-8">
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-100 font-semibold text-sm transition">
                    Cancel
                </a>
                <button type="submit" class="bg-[#20165c] hover:bg-[#181045] text-white font-semibold rounded-xl px-6 py-2.5 transition shadow-md text-sm inline-flex items-center gap-2">
                    <i class="bi bi-check2-circle text-base"></i>
                    <span>Save Profile Changes</span>
                </button>
            </div>
        </form>

        <!-- SECTION 6: PASSWORD & SECURITY (SEPARATE FORM) -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm mb-8">
            <div class="mb-6 pb-4 border-b border-slate-200">
                <div class="text-lg font-bold text-[#121826] tracking-wide flex items-center gap-2">
                    <i class="bi bi-shield-lock-fill text-rose-500"></i>
                    <span>Password & Security</span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Ensure your Noksha account is secured with a strong, distinct password.</p>
            </div>

            <form action="{{ route('settings.password.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4 max-w-xl">
                    <!-- Current Password -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2" for="current_password">
                            Current Password <span class="text-rose-400">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="current_password" 
                                   id="current_password" 
                                   class="w-full bg-white border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl pl-4 pr-11 py-3 focus:outline-none focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] text-sm transition-all" 
                                   required 
                                   placeholder="Enter current password">
                            <button type="button" class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 transition cursor-pointer" onclick="togglePasswordVisibility('current_password', this)" aria-label="Toggle password visibility">
                                <i class="bi bi-eye text-base"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <div class="text-rose-400 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2" for="password">
                            New Password <span class="text-rose-400">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="w-full bg-white border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl pl-4 pr-11 py-3 focus:outline-none focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] text-sm transition-all" 
                                   required 
                                   placeholder="At least 8 characters">
                            <button type="button" class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 transition cursor-pointer" onclick="togglePasswordVisibility('password', this)" aria-label="Toggle password visibility">
                                <i class="bi bi-eye text-base"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-rose-400 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2" for="password_confirmation">
                            Confirm New Password <span class="text-rose-400">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="w-full bg-white border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl pl-4 pr-11 py-3 focus:outline-none focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] text-sm transition-all" 
                                   required 
                                   placeholder="Re-type new password">
                            <button type="button" class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 transition cursor-pointer" onclick="togglePasswordVisibility('password_confirmation', this)" aria-label="Toggle password visibility">
                                <i class="bi bi-eye text-base"></i>
                            </button>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="bg-[#20165c] hover:bg-[#181045] text-white font-semibold rounded-xl px-5 py-2.5 transition shadow-md text-sm inline-flex items-center gap-2">
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
    // Instant JavaScript Previews (No instant reload; user sees preview first, then clicks Save)
    const coverUpload = document.getElementById('cover_upload');
    if (coverUpload) {
        coverUpload.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                let reader = new FileReader();
                reader.onload = function(evt) {
                    let banner = document.getElementById('cover_banner_box');
                    if (banner) {
                        banner.style.backgroundImage = 'url(' + evt.target.result + ')';
                        banner.style.backgroundSize = 'cover';
                        banner.style.backgroundPosition = 'center';
                    }
                    let imgPreview = document.getElementById('cover_preview');
                    if (imgPreview) {
                        imgPreview.src = evt.target.result;
                        imgPreview.classList.remove('hidden');
                    }
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    const avatarUpload = document.getElementById('avatar_upload');
    if (avatarUpload) {
        avatarUpload.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                let reader = new FileReader();
                reader.onload = function(evt) {
                    let preview = document.getElementById('avatar_preview');
                    let initials = document.getElementById('avatar_initials');
                    if (preview) {
                        preview.src = evt.target.result;
                        preview.classList.remove('hidden');
                        preview.style.display = 'block';
                    }
                    if (initials) initials.classList.add('hidden');
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    // Availability Switch Label Toggle
    const availabilitySwitch = document.getElementById('availabilitySwitch');
    const availabilityLabel = document.getElementById('availabilityLabel');
    if (availabilitySwitch && availabilityLabel) {
        availabilitySwitch.addEventListener('change', function() {
            if (this.checked) {
                availabilityLabel.innerHTML = '<span class="text-emerald-400 flex items-center gap-1.5"><i class="bi bi-record-fill"></i> Available for Hire</span>';
            } else {
                availabilityLabel.innerHTML = '<span class="text-slate-500 flex items-center gap-1.5"><i class="bi bi-dash-circle"></i> Busy / Not Available</span>';
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
            prompt.className = 'text-slate-500 text-xs';
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
