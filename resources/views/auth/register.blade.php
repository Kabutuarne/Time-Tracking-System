{{-- <x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- First_Name -->
        <div>
            <x-input-label for="first_name" :value="__('First Name')" />
            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name"
                :value="old('first_name')" required autofocus autocomplete="first_name" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>
        <!-- Last_Name -->
        <div>
            <x-input-label for="last_name" :value="__('Last Name')" />
            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name"
                :value="old('last_name')" required autofocus autocomplete="last_name" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>
        <!-- Username -->
        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')"
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>
        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}
<x-guest-layout>
    <div class="flex items-center justify-center min-h-[80vh]">
        <article class="w-full max-w-md group/card">
            <div class="relative rounded-xl bg-dark p-8 shadow-xl transition-all duration-300">
                <div
                    class="absolute inset-0 rounded-xl bg-gradient-to-r from-primary via-secondary to-primary opacity-0 group-hover/card:opacity-100 transition-opacity duration-300 blur-sm -z-10">
                </div>
                <div
                    class="absolute inset-[-2px] rounded-xl bg-gradient-to-r from-primary via-secondary to-primary opacity-0 group-hover/card:opacity-100 group-hover/card:animate-background group-hover/card:bg-[length:400%_400%] group-hover/card:[animation-duration:_4s] transition-opacity duration-300 -z-10">
                </div>
                <h2
                    class="text-2xl font-bold text-center mb-8 bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                    Become a Member
                </h2>

                <form method="POST" action="/register" class="space-y-5">
                    @csrf

                    {{-- first name --}}
                    <div>
                        <x-forms.input id="first_name" type="text" name="first_name" :value="old('first_name')" required
                            autofocus autocomplete="username" placeholder="First Name" />
                        <x-forms.input-error :messages="$errors->get('first_name')" class="mt-2" />
                    </div>
                    {{-- last name --}}
                    <div>
                        <x-forms.input id="last_name" type="text" name="last_name" :value="old('last_name')" required
                            autofocus autocomplete="username" placeholder="Last Name" />
                        <x-forms.input-error :messages="$errors->get('last_name')" class="mt-2" />
                    </div>
                    {{-- username --}}
                    <div>
                        <x-forms.input id="username" type="text" name="username" :value="old('username')" required
                            autofocus autocomplete="username" placeholder="Username" />
                        <x-forms.input-error :messages="$errors->get('username')" class="mt-2" />
                    </div>

                    {{-- email --}}
                    <div>
                        <x-forms.input id="email" type="email" name="email" :value="old('email')" required autofocus
                            autocomplete="username" placeholder="example@email.com" />
                        <x-forms.input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- password --}}
                    <div>
                        <x-forms.input id="password" type="password" name="password" required
                            autocomplete="current-password" placeholder="••••••••" />
                        <x-forms.input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    {{-- repeat password --}}
                    <div>
                        <x-forms.input id="password_confirmation" type="password" name="password_confirmation" required
                            autocomplete="current-password" placeholder="••••••••" />
                        <x-forms.input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                    {{-- remember me --}}
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="w-4 h-4 rounded bg-darker border-textcol/20 text-primary accent-primary focus:ring-primary cursor-pointer" />
                            <label for="remember_me"
                                class="ms-2 text-sm text-textcol/80 cursor-pointer">{{ __('Remember me') }}</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm text-primary hover:text-secondary transition-colors">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Buttons -->
                    <div class="space-y-3 pt-2">
                        <x-forms.button class="w-full">
                            Register
                        </x-forms.button>

                        <x-forms.button href="{{ route('login') }}" class="w-full" :secondary="true">
                            Already a Member?
                        </x-forms.button>
                    </div>
                </form>
            </div>
        </article>
    </div>
</x-guest-layout>