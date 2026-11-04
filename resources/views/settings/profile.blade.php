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
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
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
        background: rgba(7, 11, 20, 0.65);
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
        background: rgba(11, 19, 41, 0.9);
        backdrop-filter: blur(8px);
        color: #f1f5f9;
        border: 1px solid rgba(255, 255, 255, 0.15);
        padding: 0.5rem 1.15rem;
        border-radius: 50rem;
        font-size: 0.825rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.4);
    }

    .cover-upload-btn:hover {
        background: #1e1b4b;
        border-color: #6366f1;
        color: #ffffff;
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
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.6);
        object-fit: cover;
        background: #0b1329;
        display: block;
    }

    .settings-avatar-initial {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.6);
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
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
        background: #4f46e5;
        color: #FFFFFF;
        border: 3px solid #0b1329;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.4);
    }

    .avatar-upload-badge:hover {
        background: #6366f1;
        transform: scale(1.08);
    }

    /* Skills interactive tags */
    .skill-tag {
        background: rgba(99, 102, 241, 0.15);
        color: #a5b4fc;
        border: 1px solid rgba(99, 102, 241, 0.3);
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
        color: #f43f5e;
        font-size: 1rem;
        line-height: 1;
        transition: color 0.15s;
    }

    .skill-tag-remove:hover {
        color: #fb7185;
    }

    .skill-suggestion {
        cursor: pointer;
        background: #0f172a;
        color: #94a3b8;
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 0.3rem 0.75rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
        transition: all 0.15s ease;
    }

    .skill-suggestion:hover {
        background: rgba(99, 102, 241, 0.2);
        color: #c7d2fe;
        border-color: rgba(99, 102, 241, 0.4);
    }
</style>

<div class="min-h-screen bg-[#070b14] text-gray-100 py-10 px-4 sm:px-6 lg:px-8">
    <div class="settings-container">

        <!-- Top Breadcrumb & Page Title -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-6">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 text-xs uppercase font-semibold">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-indigo-400 hover:text-indigo-300 text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-indigo-400 hover:text-indigo-300 text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active text-gray-300" aria-current="page">Profile Settings</li>
                    </ol>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight mb-1">Profile & Account Settings</h1>
                <p class="text-xs text-gray-400 mb-0">Customize your public creative portfolio, social handles, and security credentials.</p>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('user.profile', $user->username ?? $user->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-gray-300 bg-[#0b1329] border border-gray-700/80 hover:bg-[#131d36] hover:text-white transition shadow-sm" title="View Public Profile">
                    <i class="bi bi-box-arrow-up-right text-indigo-400"></i>
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
                <button type="button" class="text-emerald-400 hover:text-white" onclick="this.parentElement.remove()">
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
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
            @csrf

            <!-- SECTION 1: COVER & AVATAR PREVIEWS -->
            <div class="bg-[#0b1329]/90 border border-gray-800/80 rounded-2xl shadow-xl backdrop-blur-md mb-8 overflow-hidden">
                @php
                    $coverUrl = $user->cover_image ? (str_starts_with($user->cover_image, 'http') ? $user->cover_image : asset('storage/' . ltrim(str_replace('storage/', '', $user->cover_image), '/'))) : null;
                    $avatarUrl = $user->avatar ? (str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . ltrim(str_replace('storage/', '', $user->avatar), '/'))) : null;
                @endphp

                <!-- Cover Image Banner -->
                <div class="relative w-full h-48 md:h-64 rounded-t-2xl overflow-hidden border-b border-gray-800 bg-[#0b1329] bg-cover bg-center"
                     style="background-image: url('{{ $coverUrl }}'); background-size: cover; background-position: center; background-color: #111a33;">
                    <label for="cover_upload" class="absolute top-4 right-4 bg-gray-900/80 hover:bg-black text-white text-xs px-4 py-2 rounded-xl cursor-pointer backdrop-blur-md border border-gray-700 flex items-center gap-2 shadow-lg transition">
                        <i class="bi bi-camera-fill fa fa-camera"></i>
                        <span>Change Cover Photo</span>
                        <input type="file" id="cover_upload" name="cover_image" class="hidden d-none" accept="image/*">
                    </label>
                </div>

                <!-- Avatar and Profile Header Meta -->
                <div class="p-6 pt-0">
                    <div class="flex items-end justify-between flex-wrap gap-4">
                        <div class="flex items-end gap-4 flex-wrap">
                            <!-- Avatar Circle -->
                            <div class="relative -mt-14 w-28 h-28 rounded-full overflow-hidden border-4 border-[#0b1329] shadow-2xl bg-[#0b1329]">
                                @if($avatarUrl)
                                    <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="w-full h-full object-cover rounded-full" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center bg-indigo-600 text-white text-2xl font-bold\'>{{ strtoupper(substr($user->name, 0, 2)) }}</div>';">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-indigo-600 text-white text-2xl font-bold">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                @endif
                                <label for="avatar_upload" class="absolute bottom-1 right-1 bg-indigo-600 hover:bg-indigo-500 text-white p-2 rounded-full cursor-pointer shadow-lg flex items-center justify-center" title="Change Avatar Photo">
                                    <i class="bi bi-camera-fill fa fa-camera text-xs"></i>
                                    <input type="file" id="avatar_upload" name="avatar" class="hidden d-none" accept="image/*">
                                </label>
                            </div>

                            <!-- Name & Handle Brief -->
                            <div class="mb-2">
                                <h3 class="text-xl font-bold text-white mb-0.5">{{ $user->name }}</h3>
                                <div class="text-xs text-gray-400 flex items-center gap-2">
                                    <span class="font-mono text-indigo-400 font-semibold">&#64;{{ $user->username }}</span>
                                    <span>•</span>
                                    <span class="badge bg-[#070d1e] text-gray-300 border border-gray-700/60 font-mono text-[11px] px-2 py-0.5 rounded">{{ $user->getRoleBadgeLabel() }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Availability Quick Switch -->
                        <div class="mb-2">
                            <div class="flex items-center gap-2.5 p-2 px-3 rounded-xl bg-[#070d1e] border border-gray-800">
                                <input class="w-10 h-5 accent-indigo-500 rounded cursor-pointer" type="checkbox" name="is_available" value="1" id="availabilitySwitch" {{ old('is_available', $user->is_available ?? true) ? 'checked' : '' }}>
                                <label class="text-xs font-semibold cursor-pointer" for="availabilitySwitch" id="availabilityLabel">
                                    @if(old('is_available', $user->is_available ?? true))
                                        <span class="text-emerald-400 flex items-center gap-1.5"><i class="bi bi-record-fill"></i> Available for Hire</span>
                                    @else
                                        <span class="text-gray-400 flex items-center gap-1.5"><i class="bi bi-dash-circle"></i> Busy / Not Available</span>
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: BASIC INFORMATION -->
            <div class="bg-[#0b1329]/90 border border-gray-800/80 rounded-2xl p-6 sm:p-8 shadow-xl backdrop-blur-md mb-8">
                <div class="mb-6 pb-4 border-b border-gray-800">
                    <div class="text-lg font-bold text-white tracking-wide flex items-center gap-2">
                        <i class="bi bi-person-badge-fill text-indigo-400"></i>
                        <span>Basic Information</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Public identification details visible on your creator storefront and design submissions.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Full Legal Name -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2" for="inputName">
                            Full Legal Name <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="inputName" 
                               class="w-full bg-[#070d1e] border border-gray-800 text-gray-200 placeholder-gray-500 rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all duration-200" 
                               value="{{ old('name', $user->name) }}" 
                               required 
                               placeholder="e.g. Miad Khan">
                        @error('name')
                            <div class="text-rose-400 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Unique Username Handle -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2" for="inputUsername">
                            Username Handle <span class="text-rose-400">*</span>
                        </label>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3.5 py-3 rounded-l-xl bg-[#050814] border border-r-0 border-gray-800 text-gray-400 font-mono text-sm">&#64;</span>
                            <input type="text" 
                                   name="username" 
                                   id="inputUsername" 
                                   class="w-full bg-[#070d1e] border border-gray-800 text-gray-200 placeholder-gray-500 rounded-r-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm font-mono transition-all duration-200" 
                                   value="{{ old('username', $user->username) }}" 
                                   required 
                                   placeholder="username">
                        </div>
                        <div class="text-[11px] text-gray-400 mt-1">Letters, numbers, underscores and dashes only.</div>
                        @error('username')
                            <div class="text-rose-400 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Professional Headline -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2" for="inputHeadline">
                            Professional Headline
                        </label>
                        <input type="text" 
                               name="headline" 
                               id="inputHeadline" 
                               class="w-full bg-[#070d1e] border border-gray-800 text-gray-200 placeholder-gray-500 rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all duration-200" 
                               value="{{ old('headline', $user->headline) }}" 
                               maxlength="150" 
                               placeholder="e.g. Brand Identity Designer & Vector Specialist">
                        <div class="text-[11px] text-gray-400 mt-1">Brief summary displayed directly below your name (max 150 characters).</div>
                        @error('headline')
                            <div class="text-rose-400 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Bio / About -->
                    <div class="md:col-span-2">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-0" for="inputBio">
                                Bio / Creative Background
                            </label>
                            <span class="text-xs text-gray-400 font-mono" id="bioCounter">0 / 1000</span>
                        </div>
                        <textarea name="bio" 
                                  id="inputBio" 
                                  rows="4" 
                                  class="w-full bg-[#070d1e] border border-gray-800 text-gray-200 placeholder-gray-500 rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all duration-200" 
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
            <div class="bg-[#0b1329]/90 border border-gray-800/80 rounded-2xl p-6 sm:p-8 shadow-xl backdrop-blur-md mb-8">
                <div class="mb-6 pb-4 border-b border-gray-800">
                    <div class="text-lg font-bold text-white tracking-wide flex items-center gap-2">
                        <i class="bi bi-tools text-indigo-400"></i>
                        <span>Skills & Creative Tools</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Highlight your core design competencies, software proficiencies, and categories.</p>
                </div>

                <!-- Interactive Tag Container -->
                <div class="mb-4">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2">Selected Skills</label>
                    <div class="p-3.5 bg-[#070d1e] rounded-xl border border-gray-800 flex flex-wrap gap-2 items-center min-h-[56px]" id="skillsTagContainer">
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
                            <span class="text-gray-400 text-xs" id="noSkillsPrompt">No skills added yet. Type below or select from suggestions.</span>
                        @endif
                    </div>
                </div>

                <!-- Input to add custom skill -->
                <div class="flex flex-col sm:flex-row gap-2.5 mb-4">
                    <div class="flex-grow">
                        <input type="text" 
                               id="newSkillInput" 
                               class="w-full bg-[#070d1e] border border-gray-800 text-gray-200 placeholder-gray-500 rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all" 
                               placeholder="Type a skill (e.g. Logo Design, Typography, Figma) and press Enter" 
                               onkeydown="handleSkillKeyDown(event)">
                    </div>
                    <button type="button" 
                            class="px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs transition shadow-lg shadow-indigo-600/20 whitespace-nowrap" 
                            onclick="addSkillFromInput()">
                        <i class="bi bi-plus-lg me-1"></i> Add Skill
                    </button>
                </div>

                <!-- Popular Suggestions -->
                <div>
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider me-2 block sm:inline-block mb-1.5 sm:mb-0">Popular Suggestions:</span>
                    <div class="inline-flex flex-wrap gap-1.5 items-center">
                        @foreach(['Logo Design', 'Branding', 'Figma', 'Illustrator', 'Photoshop', 'Typography', 'Vector Art', 'Packaging', 'Social Media', 'UI/UX'] as $suggested)
                            <span class="skill-suggestion" onclick="addSuggestedSkill('{{ $suggested }}')">+ {{ $suggested }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- SECTION 4: SOCIAL & PORTFOLIO LINKS -->
            <div class="bg-[#0b1329]/90 border border-gray-800/80 rounded-2xl p-6 sm:p-8 shadow-xl backdrop-blur-md mb-8">
                <div class="mb-6 pb-4 border-b border-gray-800">
                    <div class="text-lg font-bold text-white tracking-wide flex items-center gap-2">
                        <i class="bi bi-globe text-indigo-400"></i>
                        <span>Social & Portfolio Presence</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Connect your external creative profiles to establish authority and trust.</p>
                </div>

                @php
                    $socialLinks = old('social_links', $user->social_links ?? []);
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Behance -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2" for="linkBehance">Behance Portfolio</label>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3.5 py-3 rounded-l-xl bg-[#050814] border border-r-0 border-gray-800 text-indigo-400"><i class="bi bi-behance fs-6"></i></span>
                            <input type="url" name="social_links[behance]" id="linkBehance" class="w-full bg-[#070d1e] border border-gray-800 text-gray-200 placeholder-gray-500 rounded-r-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all" value="{{ $socialLinks['behance'] ?? '' }}" placeholder="https://behance.net/username">
                        </div>
                    </div>

                    <!-- Dribbble -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2" for="linkDribbble">Dribbble Profile</label>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3.5 py-3 rounded-l-xl bg-[#050814] border border-r-0 border-gray-800 text-rose-400"><i class="bi bi-dribbble fs-6"></i></span>
                            <input type="url" name="social_links[dribbble]" id="linkDribbble" class="w-full bg-[#070d1e] border border-gray-800 text-gray-200 placeholder-gray-500 rounded-r-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all" value="{{ $socialLinks['dribbble'] ?? '' }}" placeholder="https://dribbble.com/username">
                        </div>
                    </div>

                    <!-- Personal Website / Portfolio -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2" for="linkWebsite">Personal Website / Studio</label>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3.5 py-3 rounded-l-xl bg-[#050814] border border-r-0 border-gray-800 text-indigo-400"><i class="bi bi-globe2 fs-6"></i></span>
                            <input type="url" name="social_links[website]" id="linkWebsite" class="w-full bg-[#070d1e] border border-gray-800 text-gray-200 placeholder-gray-500 rounded-r-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all" value="{{ $socialLinks['website'] ?? '' }}" placeholder="https://yourportfolio.com">
                        </div>
                    </div>

                    <!-- LinkedIn -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2" for="linkLinkedin">LinkedIn Profile</label>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3.5 py-3 rounded-l-xl bg-[#050814] border border-r-0 border-gray-800 text-cyan-400"><i class="bi bi-linkedin fs-6"></i></span>
                            <input type="url" name="social_links[linkedin]" id="linkLinkedin" class="w-full bg-[#070d1e] border border-gray-800 text-gray-200 placeholder-gray-500 rounded-r-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all" value="{{ $socialLinks['linkedin'] ?? '' }}" placeholder="https://linkedin.com/in/username">
                        </div>
                    </div>

                    <!-- Twitter / X -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2" for="linkTwitter">Twitter / X Handle</label>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3.5 py-3 rounded-l-xl bg-[#050814] border border-r-0 border-gray-800 text-gray-300"><i class="bi bi-twitter-x fs-6"></i></span>
                            <input type="url" name="social_links[twitter]" id="linkTwitter" class="w-full bg-[#070d1e] border border-gray-800 text-gray-200 placeholder-gray-500 rounded-r-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all" value="{{ $socialLinks['twitter'] ?? '' }}" placeholder="https://x.com/username">
                        </div>
                    </div>

                    <!-- GitHub -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2" for="linkGithub">GitHub Profile</label>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3.5 py-3 rounded-l-xl bg-[#050814] border border-r-0 border-gray-800 text-gray-300"><i class="bi bi-github fs-6"></i></span>
                            <input type="url" name="social_links[github]" id="linkGithub" class="w-full bg-[#070d1e] border border-gray-800 text-gray-200 placeholder-gray-500 rounded-r-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all" value="{{ $socialLinks['github'] ?? '' }}" placeholder="https://github.com/username">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 5: CONTACT INFORMATION -->
            <div class="bg-[#0b1329]/90 border border-gray-800/80 rounded-2xl p-6 sm:p-8 shadow-xl backdrop-blur-md mb-8">
                <div class="mb-6 pb-4 border-b border-gray-800">
                    <div class="text-lg font-bold text-white tracking-wide flex items-center gap-2">
                        <i class="bi bi-envelope-check-fill text-indigo-400"></i>
                        <span>Contact & Account Verification</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Credentials used for system notifications, contest payouts, and order invoices.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Email Address (Readonly / Status) -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2">Email Address</label>
                        <div class="flex items-center">
                            <input type="email" class="w-full bg-[#050814] text-gray-400 border border-gray-800/60 cursor-not-allowed rounded-l-xl px-4 py-3 text-sm" value="{{ $user->email }}" readonly disabled>
                            <span class="inline-flex items-center px-3.5 py-3 bg-[#050814] border border-l-0 border-gray-800 rounded-r-xl">
                                @if($user->hasVerifiedEmail())
                                    <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 px-2.5 py-0.5 rounded-full text-xs font-semibold">Verified ✓</span>
                                @else
                                    <a href="{{ route('verification.notice') }}" class="bg-amber-500/10 text-amber-400 border border-amber-500/30 px-2.5 py-0.5 rounded-full text-xs font-semibold hover:bg-amber-500/20 text-decoration-none">Verify Now</a>
                                @endif
                            </span>
                        </div>
                        <div class="text-[11px] text-gray-400 mt-1">To change your email address, contact support.</div>
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2" for="inputPhone">Phone / WhatsApp Number</label>
                        <input type="tel" 
                               name="phone" 
                               id="inputPhone" 
                               class="w-full bg-[#070d1e] border border-gray-800 text-gray-200 placeholder-gray-500 rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all duration-200" 
                               value="{{ old('phone', $user->phone) }}" 
                               placeholder="+880 1XXXXXXXXX">
                        <div class="text-[11px] text-gray-400 mt-1">Used for payment confirmations and contest winner notifications.</div>
                    </div>
                </div>
            </div>

            <!-- SAVE PROFILE BUTTON -->
            <div class="flex items-center justify-end gap-3 mb-8">
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl border border-gray-700/80 text-gray-300 hover:bg-gray-800 font-semibold text-sm transition">
                    Cancel
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-xl px-6 py-2.5 transition shadow-lg shadow-indigo-600/20 text-sm inline-flex items-center gap-2">
                    <i class="bi bi-check2-circle text-base"></i>
                    <span>Save Profile Changes</span>
                </button>
            </div>
        </form>

        <!-- SECTION 6: PASSWORD & SECURITY (SEPARATE FORM) -->
        <div class="bg-[#0b1329]/90 border border-gray-800/80 rounded-2xl p-6 sm:p-8 shadow-xl backdrop-blur-md mb-8">
            <div class="mb-6 pb-4 border-b border-gray-800">
                <div class="text-lg font-bold text-white tracking-wide flex items-center gap-2">
                    <i class="bi bi-shield-lock-fill text-rose-400"></i>
                    <span>Password & Security</span>
                </div>
                <p class="text-xs text-gray-400 mt-1">Ensure your Noksha account is secured with a strong, distinct password.</p>
            </div>

            <form action="{{ route('settings.password.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4 max-w-xl">
                    <!-- Current Password -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2" for="current_password">
                            Current Password <span class="text-rose-400">*</span>
                        </label>
                        <div class="flex items-center">
                            <input type="password" 
                                   name="current_password" 
                                   id="current_password" 
                                   class="w-full bg-[#070d1e] border border-gray-800 text-gray-200 placeholder-gray-500 rounded-l-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all" 
                                   required 
                                   placeholder="Enter current password">
                            <button type="button" class="px-3.5 py-3 bg-[#070d1e] border border-l-0 border-gray-800 text-gray-400 hover:text-white rounded-r-xl transition" onclick="togglePasswordVisibility('current_password', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <div class="text-rose-400 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2" for="password">
                            New Password <span class="text-rose-400">*</span>
                        </label>
                        <div class="flex items-center">
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="w-full bg-[#070d1e] border border-gray-800 text-gray-200 placeholder-gray-500 rounded-l-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all" 
                                   required 
                                   placeholder="At least 8 characters">
                            <button type="button" class="px-3.5 py-3 bg-[#070d1e] border border-l-0 border-gray-800 text-gray-400 hover:text-white rounded-r-xl transition" onclick="togglePasswordVisibility('password', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-rose-400 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2" for="password_confirmation">
                            Confirm New Password <span class="text-rose-400">*</span>
                        </label>
                        <div class="flex items-center">
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="w-full bg-[#070d1e] border border-gray-800 text-gray-200 placeholder-gray-500 rounded-l-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all" 
                                   required 
                                   placeholder="Re-type new password">
                            <button type="button" class="px-3.5 py-3 bg-[#070d1e] border border-l-0 border-gray-800 text-gray-400 hover:text-white rounded-r-xl transition" onclick="togglePasswordVisibility('password_confirmation', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-xl px-5 py-2.5 transition shadow-lg shadow-indigo-600/20 text-sm inline-flex items-center gap-2">
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
    // Automatic Form Submission on Image Change
    const coverUpload = document.getElementById('cover_upload');
    if (coverUpload) {
        coverUpload.onchange = () => document.getElementById('profileForm').submit();
    }
    const avatarUpload = document.getElementById('avatar_upload');
    if (avatarUpload) {
        avatarUpload.onchange = () => document.getElementById('profileForm').submit();
    }

    // Availability Switch Label Toggle
    const availabilitySwitch = document.getElementById('availabilitySwitch');
    const availabilityLabel = document.getElementById('availabilityLabel');
    if (availabilitySwitch && availabilityLabel) {
        availabilitySwitch.addEventListener('change', function() {
            if (this.checked) {
                availabilityLabel.innerHTML = '<span class="text-emerald-400 flex items-center gap-1.5"><i class="bi bi-record-fill"></i> Available for Hire</span>';
            } else {
                availabilityLabel.innerHTML = '<span class="text-gray-400 flex items-center gap-1.5"><i class="bi bi-dash-circle"></i> Busy / Not Available</span>';
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
            prompt.className = 'text-gray-400 text-xs';
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
