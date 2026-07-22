<section class="p-4 sm:p-0">
    <header>
        <h2 class="text-xl sm:text-lg font-semibold sm:font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-2 sm:mt-1 text-sm text-gray-600 leading-relaxed">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Input Nama -->
        <div class="space-y-1">
            <x-input-label for="name" :value="__('Name')" class="text-sm font-medium" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full min-h-[44px] sm:min-h-0 text-base sm:text-sm" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Input Email -->
        <div class="space-y-1">
            <x-input-label for="email" :value="__('Email')" class="text-sm font-medium" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full min-h-[44px] sm:min-h-0 text-base sm:text-sm" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <p class="text-sm text-gray-800 leading-relaxed">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="block sm:inline underline text-sm text-indigo-600 hover:text-indigo-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mt-2 sm:mt-0 pt-1 pb-1">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Tombol Aksi -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-4 pt-2">
            <x-primary-button class="w-full sm:w-auto justify-center min-h-[44px] sm:min-h-0 text-center">
                {{ __('Save') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 sm:text-gray-600 text-center sm:text-left font-medium sm:font-normal"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>