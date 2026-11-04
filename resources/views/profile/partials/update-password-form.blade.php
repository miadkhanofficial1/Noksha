<section>
    <header class="mb-6 pb-4 border-b border-gray-800">
        <h2 class="text-lg font-bold text-white tracking-wide flex items-center gap-2">
            <i class="bi bi-shield-lock-fill text-rose-400"></i>
            <span>{{ __('Update Password') }}</span>
        </h2>
        <p class="text-xs text-gray-400 mt-1">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('settings.password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2">{{ __('Current Password') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" class="w-full bg-[#070d1e] border border-gray-800 text-gray-200 placeholder-gray-500 rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all duration-200" autocomplete="current-password" />
            @error('current_password')
                <div class="text-rose-400 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="update_password_password" class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2">{{ __('New Password') }}</label>
            <input id="update_password_password" name="password" type="password" class="w-full bg-[#070d1e] border border-gray-800 text-gray-200 placeholder-gray-500 rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all duration-200" autocomplete="new-password" />
            @error('password')
                <div class="text-rose-400 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2">{{ __('Confirm Password') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full bg-[#070d1e] border border-gray-800 text-gray-200 placeholder-gray-500 rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all duration-200" autocomplete="new-password" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-xl px-5 py-2.5 transition shadow-lg shadow-indigo-600/20 text-sm">
                {{ __('Save') }}
            </button>
        </div>
    </form>
</section>
