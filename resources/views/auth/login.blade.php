<x-guest-layout>
    <div class="flex justify-center pt-24" style="background-color: #f0fdf4;">
        <div class="w-full max-w-md px-6">

            <!-- Title -->
            <h1 class="+ text-3xl font-semibold text-center text-gray-900 mt-3">
                Task Manager App
            </h1>
            <p class="text-base text-center text-gray-600 mt-2 mb-10">
                Login
            </p>

            <x-auth-session-status class="mt-6 text-sm" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-7">
                @csrf

                <!-- Email -->
                <div>
                    <x-input-label
                        for="email"
                        value="Email Address"
                        class="text-sm font-medium text-gray-700 mt-3"
                    />

                    <x-text-input
                        id="email"
                        class="block w-full mt-3
                               rounded-md border-gray-400
                               bg-white
                               text-gray-900 text-base
                               placeholder-gray-400
                               focus:border-indigo-500
                               focus:ring-indigo-500"
                        type="email"
                        name="email"
                        required
                        autofocus
                        oninvalid="this.setCustomValidity('メールアドレスを入力してください')"
                        oninput="this.setCustomValidity('')"
                    />

                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label
                        for="password"
                        value="Password"
                        class="text-sm font-medium text-gray-700 mt-3"
                    />

                    <x-text-input
                        id="password"
                        class="block w-full mt-3
                               rounded-md border-gray-400
                               bg-white
                               text-gray-900 text-base
                               placeholder-gray-400
                               focus:border-indigo-500
                               focus:ring-indigo-500"
                        type="password"
                        name="password"
                        required
                        oninvalid="this.setCustomValidity('パスワードを入力してください')"
                        oninput="this.setCustomValidity('')"
                    />

                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm" />
                </div>

                <!-- Remember -->
                <div class="flex items-center">
                    <input
                        id="remember_me"
                        type="checkbox"
                        name="remember"
                        class="h-4 w-4 rounded
                               border-gray-400
                               text-indigo-600
                               focus:ring-indigo-500
                               mt-3"
                    >
                    <label for="remember_me" class="ms-2 text-sm text-gray-700 mt-3">
                        Keep me logged in
                    </label>
                </div>

                <!-- Button -->
                <div class="pt-2 flex justify-center">
                    <button
                        type="submit"
                        class="
                            px-6 py-2
                            text-sm font-semibold
                            rounded
                            transition-all duration-200
                            focus:outline-none
                            focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
                        "
                        style="
                            color: #065f46;
                            background-color: #ffffff;
                            border: 2px solid white;
                        "
                        onmouseover="this.style.backgroundColor='#115e59'; this.style.color='#ffffff';"
                        onmouseout="this.style.backgroundColor='#ffffff'; this.style.color='#115e59';"
                    >
                        Login
                    </button>
                </div>

                <!-- Register and Forgot Password Links -->
                <div class="text-center pt-2 space-y-2">
                    <div href="{{ route('register') }}"
                         class="text-sm font-medium
                                text-indigo-600
                                hover:text-indigo-700
                                underline"
                         style="color: #065f46;"
                    >
                        Don't have an account? Sign up now.
                    </div>

                    @if (Route::has('password.request'))
                        <div href="{{ route('password.request') }}"
                             class="text-sm font-medium
                                    text-indigo-600
                                    hover:text-indigo-700
                                    underline"
                             style="color: #065f46;"
                        >
                            Forgot your password?
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
