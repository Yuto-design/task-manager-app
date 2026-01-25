<x-guest-layout>
    <div class="flex justify-center pt-24" style="background-color: #f0f9ff;">
        <div class="w-full max-w-md px-6">
            <!-- Title -->
            <h1 class="text-3xl font-semibold text-center text-gray-900 mt-3">
                Task Manager App
            </h1>
            <p class="text-base text-center text-gray-600 mt-2 mb-6">
                Password Reset
            </p>

            <!-- Description -->
            <div class="mb-6 text-sm text-gray-600 text-center">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="pt-2 flex justify-center mt-6">
                    <button
                        type="submit"
                        style="
                            color: #1e40af;
                            background-color: #ffffff;
                            border: 2px solid #ffffff;
                            padding: 0.5rem 1.5rem;
                            font-size: 0.875rem;
                            font-weight: 600;
                            border-radius: 0.25rem;
                            transition: all 0.2s;
                        "
                        onmouseover="this.style.backgroundColor='#2563eb'; this.style.color='#ffffff';"
                        onmouseout="this.style.backgroundColor='#ffffff'; this.style.color='#1e40af';"
                    >
                        {{ __('Email Password Reset Link') }}
                    </button>
                </div>

                <div class="flex items-center justify-center mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900
                              rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2
                              focus:ring-indigo-500" href="{{ route('login') }}"
                       style="color: #1e40af;"
                    >
                        {{ __('Back to Login') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>