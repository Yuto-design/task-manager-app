<x-guest-layout>
    <div class="flex justify-center pt-24" style="background-color: #fffef0;">
        <div class="w-full max-w-md px-6">
            <!-- Title -->
            <h1 class="+ text-3xl font-semibold text-center text-gray-900 mt-3">
                Task Manager App
            </h1>
            <p class="text-base text-center text-gray-600 mt-2 mb-10">
                Register
            </p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input 
                        id="name" 
                        class="block mt-1 w-full" 
                        type="text" 
                        name="name" 
                        :value="old('name')" 
                        required 
                        autofocus 
                        autocomplete="name"
                        oninvalid="this.setCustomValidity('名前を入力してください')"
                        oninput="this.setCustomValidity('')"
                    />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input 
                        id="email" 
                        class="block mt-1 w-full" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autocomplete="username"
                        oninvalid="this.setCustomValidity('メールアドレスを入力してください')"
                        oninput="this.setCustomValidity('')"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input 
                        id="password" 
                        class="block mt-1 w-full"
                        type="password"
                        name="password"
                        required 
                        autocomplete="new-password"
                        oninvalid="this.setCustomValidity('パスワードを入力してください')"
                        oninput="this.setCustomValidity('')"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input 
                        id="password_confirmation" 
                        class="block mt-1 w-full"
                        type="password"
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        oninvalid="this.setCustomValidity('確認用パスワードを入力してください')"
                        oninput="this.setCustomValidity('')"
                    />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="pt-2 flex justify-center mt-6">
                    <button
                        type="submit"
                        style="
                            color: #9a3412;
                            background-color: #ffffff;
                            border: 2px solid #ffffff;
                            padding: 0.5rem 1.5rem;
                            font-size: 0.875rem;
                            font-weight: 600;
                            border-radius: 0.25rem;
                            transition: all 0.2s;
                        "
                        onmouseover="this.style.backgroundColor='#c2410c'; this.style.color='#ffffff';"
                        onmouseout="this.style.backgroundColor='#ffffff'; this.style.color='#c2410c';"
                    >
                        {{ __('Register') }}
                    </button>
                </div>

                <div class="flex items-center justify-center mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900
                            rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2
                            focus:ring-indigo-500" href="{{ route('login') }}"
                    style="color: #9a3412;"
                    >
                        {{ __('Already registered?') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>