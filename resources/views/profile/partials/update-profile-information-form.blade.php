<section>
    <header class="mb-6 pb-4 border-b border-gray-800">
        <h2 class="text-lg font-bold text-[#121826] tracking-wide flex items-center gap-2">
            <i class="bi bi-person-badge-fill text-[#20165c]"></i>
            <span>{{ __('Profile Information') }}</span>
        </h2>
        <p class="text-xs text-gray-400 mt-1">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form method="post" action="{{ route('settings.profile.update') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('put')

        <div>
            <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2">{{ __('Name') }}</label>
            <input id="name" name="name" type="text" class="w-full bg-white border border-slate-300 text-slate-900 placeholder-gray-500 rounded-xl px-4 py-3 focus:outline-none focus:border-[#20165c] focus:ring-1 focus:ring-[#20165c] text-sm transition-all duration-200" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            @error('name')
                <div class="text-rose-400 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" class="w-full bg-[#050814] text-gray-400 border border-gray-800/60 cursor-not-allowed rounded-xl px-4 py-3 text-sm" value="{{ old('email', $user->email) }}" readonly disabled />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="bg-[#20165c] hover:bg-[#181045] text-[#121826] font-medium rounded-xl px-5 py-2.5 transition shadow-lg shadow-indigo-600/20 text-sm">
                {{ __('Save') }}
            </button>
        </div>
    </form>
</section>

